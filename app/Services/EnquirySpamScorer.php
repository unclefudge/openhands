<?php

namespace App\Services;

use App\Models\Enquiry;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class EnquirySpamScorer
{
    /**
     * @param  array<string, mixed>  $data
     * @return array{score: int, status: string, reasons: array<int, array{code: string, label: string, points: int}>, fingerprint: string, email_country: ?string, link_countries: array<int, string>}
     */
    /*-----------------------------------------------------------------------
     * Score	Status	    You receive email?          Sender confirmation?
     * ----------------------------------------------------------------------
     * 0–29	    Delivered	Yes                         Yes
     * 30–59	Suspicious	Yes, marked for checking    No
     * 60–79	Quarantined	No                          No
     * 80–100	Blocked	    No                          No
     * ----------------------------------------------------------------------
     * How the score is calculated
     * ----------------------------------------------------------------------
     * 100  Hidden honeypot completed
     * 40   Form completed in under 3 seconds
     * 30   Missing/invalid form timer
     * 5    One link
     * 10   Multiple links
     * 25   Shortened/commonly abused link
     * 5    IP outside Australia
     * 10   IP from configured higher-risk country
     * 15   Email with higher-risk country extension
     * 20   Link with higher-risk country extension
     * 60   Obvious SEO/marketing sales pitch
     * 55   Known promotional/scam language
     * 35   Disposable email address
     * 5    Gmail etc. with no organisation
     *-----------------------------------------------------------------------*/
    public function assess(array $data): array
    {
        $message = Str::lower((string) ($data['message'] ?? ''));
        $email = Str::lower((string) ($data['email'] ?? ''));
        $organisation = trim((string) ($data['organisation'] ?? ''));
        $ipCountry = $this->normaliseCountry($data['ip_country'] ?? null);
        $emailDomain = Str::after($email, '@');
        $emailCountry = $this->countryFromHost($emailDomain);
        $urls = $this->extractUrls($message);
        $linkHosts = array_values(array_filter(array_map(fn (string $url): ?string => $this->hostFromUrl($url), $urls)));
        $linkCountries = array_values(array_unique(array_filter(array_map(fn (string $host): ?string => $this->countryFromHost($host), $linkHosts))));
        $fingerprint = hash('sha256', $this->normaliseForComparison($message));
        $reasons = [];

        $add = function (string $code, string $label, int $points) use (&$reasons): void {
            $reasons[] = compact('code', 'label', 'points');
        };

        if (filled($data['website'] ?? null)) {
            $add('honeypot', 'The hidden honeypot field was completed.', 100);
        }

        $completionSeconds = $data['completion_seconds'] ?? null;
        if ($completionSeconds === null) {
            $add('invalid_timer', 'The protected form timer was missing or invalid.', 30);
        } elseif ($completionSeconds < 3) {
            $add('fast_completion', 'The form was completed in under three seconds.', 40);
        } elseif ($completionSeconds > 43_200) {
            $add('expired_form', 'The form was open for more than twelve hours.', 10);
        }

        if (count($urls) === 1) {
            $add('contains_link', 'The message contains a link.', 5);
        } elseif (count($urls) > 1) {
            $add('multiple_links', 'The message contains multiple links.', 10);
        }

        $shortenedHosts = array_map('strtolower', config('enquiry-spam.shortened_link_hosts', []));
        if ($this->hostListMatches($linkHosts, $shortenedHosts)) {
            $add('shortened_link', 'The message contains a shortened or commonly abused link.', 25);
        }

        $localCountry = strtoupper((string) config('enquiry-spam.local_country_code', 'AU'));
        $higherRiskCountries = array_map('strtoupper', config('enquiry-spam.higher_risk_country_codes', []));

        if ($ipCountry && $ipCountry !== $localCountry) {
            $add('overseas_ip', "The submission originated outside {$localCountry} ({$ipCountry}).", 5);
        }

        if ($ipCountry && in_array($ipCountry, $higherRiskCountries, true)) {
            $add('higher_risk_ip_country', "The IP country ({$ipCountry}) is on the configurable higher-risk list.", 10);
        }

        if ($emailCountry && in_array($emailCountry, $higherRiskCountries, true)) {
            $add('higher_risk_email_country', "The email domain uses a higher-risk country code ({$emailCountry}).", 15);
        }

        $riskyLinkCountries = array_values(array_intersect($linkCountries, $higherRiskCountries));
        if ($riskyLinkCountries !== []) {
            $add('higher_risk_link_country', 'A link uses a higher-risk country code ('.implode(', ', $riskyLinkCountries).').', 20);
        }

        $sellingPhrases = [
            'we provide',
            'we offer',
            'our services',
            'send you our plans',
            'send our plans',
            'plans and pricing',
            'pricing for review',
            'shall i send',
            'can i send',
        ];

        $marketingPhrases = [
            'seo services',
            'search visibility',
            'digital marketing',
            'lead generation',
            'backlinks',
            'guest post',
            'website traffic',
            'domain authority',
        ];

        if (Str::contains($message, $sellingPhrases) && Str::contains($message, $marketingPhrases)) {
            $add('sales_pitch', 'The message appears to be selling marketing or SEO services.', 60);
        }

        if (Str::contains($message, [
            'instant winner',
            'win a new',
            'crypto investment',
            'guaranteed returns',
            'free, no card',
            'publishing 3x more',
            'boost watch time',
        ])) {
            $add('known_spam_language', 'The message contains language commonly found in promotional spam or scams.', 55);
        }

        if (in_array($emailDomain, config('enquiry-spam.disposable_email_domains', []), true)) {
            $add('disposable_email', 'The sender used a disposable email provider.', 35);
        } elseif ($organisation === '' && in_array($emailDomain, config('enquiry-spam.free_email_domains', []), true)) {
            $add('free_email_no_organisation', 'A free email address was used without an organisation.', 5);
        }

        $confirmedSpam = Enquiry::query()
            ->where('review_status', 'spam')
            ->latest('reviewed_at')
            ->limit(200)
            ->get(['message', 'message_fingerprint']);

        if ($confirmedSpam->contains('message_fingerprint', $fingerprint)) {
            $add('confirmed_spam_match', 'The message exactly matches a previously confirmed spam enquiry.', 70);
        } else {
            $spamSimilarity = $this->highestSimilarity($message, $confirmedSpam->pluck('message'));
            if ($spamSimilarity >= 0.70) {
                $add('strong_spam_similarity', 'The wording strongly resembles previously confirmed spam.', 55);
            } elseif ($spamSimilarity >= 0.50) {
                $add('spam_similarity', 'The wording resembles previously confirmed spam.', 30);
            }
        }

        $confirmedGenuine = Enquiry::query()
            ->where('review_status', 'genuine')
            ->latest('reviewed_at')
            ->limit(200)
            ->pluck('message');

        if ($this->highestSimilarity($message, $confirmedGenuine) >= 0.70) {
            $add('genuine_similarity', 'The wording resembles a previously confirmed genuine enquiry.', -20);
        }

        $score = max(0, min(100, array_sum(array_column($reasons, 'points'))));

        return [
            'score' => $score,
            'status' => $this->statusForScore($score),
            'reasons' => $reasons,
            'fingerprint' => $fingerprint,
            'email_country' => $emailCountry,
            'link_countries' => $linkCountries,
        ];
    }

    public function statusForScore(int $score): string
    {
        if ($score >= config('enquiry-spam.thresholds.blocked', 80)) {
            return 'blocked';
        }

        if ($score >= config('enquiry-spam.thresholds.quarantined', 60)) {
            return 'quarantined';
        }

        if ($score >= config('enquiry-spam.thresholds.suspicious', 30)) {
            return 'suspicious';
        }

        return 'delivered';
    }

    /** @return array<int, string> */
    private function extractUrls(string $message): array
    {
        preg_match_all('~(?:https?://|www\.)[^\s<>"\']+|\b(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z]{2,}(?:/[^\s<>"\']*)?~i', $message, $matches);

        return array_values(array_unique(array_map(
            static fn (string $url): string => rtrim($url, '.,);]'),
            $matches[0] ?? [],
        )));
    }

    private function hostFromUrl(string $url): ?string
    {
        $normalised = Str::contains($url, '://') ? $url : 'https://'.$url;
        $host = parse_url($normalised, PHP_URL_HOST);

        return is_string($host) ? Str::lower($host) : null;
    }

    private function countryFromHost(?string $host): ?string
    {
        if (! $host) {
            return null;
        }

        $suffix = Str::afterLast(Str::lower($host), '.');

        return strlen($suffix) === 2 ? strtoupper($suffix) : null;
    }

    private function normaliseCountry(mixed $country): ?string
    {
        $country = strtoupper(trim((string) $country));

        return preg_match('/^[A-Z]{2}$/', $country) && ! in_array($country, ['XX', 'T1'], true)
            ? $country
            : null;
    }

    /**
     * @param  array<int, string>  $hosts
     * @param  array<int, string>  $needles
     */
    private function hostListMatches(array $hosts, array $needles): bool
    {
        foreach ($hosts as $host) {
            foreach ($needles as $needle) {
                if ($host === $needle || Str::endsWith($host, '.'.$needle)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function normaliseForComparison(string $message): string
    {
        $message = Str::lower(Str::ascii($message));
        $message = preg_replace('~(?:https?://|www\.)\S+~i', ' ', $message) ?? $message;
        $message = preg_replace('/[^a-z0-9\s]+/', ' ', $message) ?? $message;

        return trim(preg_replace('/\s+/', ' ', $message) ?? $message);
    }

    /** @return array<int, string> */
    private function tokens(string $message): array
    {
        $stopWords = [
            'a', 'an', 'and', 'are', 'as', 'at', 'be', 'but', 'by', 'can', 'for', 'from',
            'have', 'hi', 'i', 'in', 'is', 'it', 'me', 'my', 'of', 'on', 'or', 'our',
            'the', 'this', 'to', 'we', 'with', 'you', 'your',
        ];

        return array_values(array_unique(array_filter(
            explode(' ', $this->normaliseForComparison($message)),
            static fn (string $word): bool => strlen($word) >= 3 && ! in_array($word, $stopWords, true),
        )));
    }

    /** @param Collection<int, string> $examples */
    private function highestSimilarity(string $message, Collection $examples): float
    {
        $tokens = $this->tokens($message);
        if (count($tokens) < 4) {
            return 0.0;
        }

        $highest = 0.0;
        foreach ($examples as $example) {
            $exampleTokens = $this->tokens($example);
            $union = array_unique([...$tokens, ...$exampleTokens]);
            if ($union === []) {
                continue;
            }

            $similarity = count(array_intersect($tokens, $exampleTokens)) / count($union);
            $highest = max($highest, $similarity);
        }

        return $highest;
    }
}

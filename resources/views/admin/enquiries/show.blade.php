@extends('admin.layout')

@section('title', 'Enquiry from '.$enquiry->name)

@section('body')
    <main class="page">
        <div class="page-head">
            <div><a href="{{ route('admin.enquiries.index') }}">← All enquiries</a><h1 style="margin-top:12px;">{{ $enquiry->name }}</h1><div class="muted">Received {{ $enquiry->created_at->format('D j M Y, g:ia') }}</div></div>
            <div class="actions">
                <form method="POST" action="{{ route('admin.enquiries.genuine', $enquiry) }}">@csrf<button class="button good" type="submit">Mark genuine</button></form>
                <form method="POST" action="{{ route('admin.enquiries.spam', $enquiry) }}">@csrf<button class="button danger" type="submit">Mark spam</button></form>
                <form method="POST" action="{{ route('admin.enquiries.recalculate', $enquiry) }}">@csrf<button class="button secondary" type="submit">Recalculate</button></form>
            </div>
        </div>

        @if (session('status'))<div class="flash">{{ session('status') }}</div>@endif
        @if (session('error'))<div class="flash error">{{ session('error') }}</div>@endif

        <div class="detail-grid">
            <section class="panel">
                <h2>Enquiry</h2>
                <dl class="meta">
                    <dt>Email</dt><dd><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></dd>
                    <dt>Organisation</dt><dd>{{ $enquiry->organisation ?: 'Not provided' }}</dd>
                    <dt>Phone</dt><dd>{{ $enquiry->phone ?: 'Not provided' }}</dd>
                    <dt>Looking for</dt><dd>{{ $enquiry->service }}</dd>
                    <dt>Timeframe</dt><dd>{{ $enquiry->timeframe ?: 'Not provided' }}</dd>
                    <dt>Referral</dt><dd>{{ $enquiry->referral ?: 'Not provided' }}</dd>
                </dl>
                <h2 style="margin-top:28px;">Problem or idea</h2>
                <div class="message">{{ $enquiry->message }}</div>
            </section>

            <div>
                <section class="panel" style="margin-bottom:20px;">
                    <h2>Spam assessment</h2>
                    <p><span class="score">{{ $enquiry->spam_score }}/100</span> <span class="badge {{ $enquiry->spam_status }}">{{ ucfirst($enquiry->spam_status) }}</span> <span class="badge {{ $enquiry->review_status }}">{{ ucfirst($enquiry->review_status) }}</span></p>
                    @if ($enquiry->original_spam_score !== $enquiry->spam_score)<p class="muted">Original score: {{ $enquiry->original_spam_score }}/100</p>@endif
                    <table>
                        <thead><tr><th>Reason</th><th style="text-align:right;">Points</th></tr></thead>
                        <tbody>
                            @forelse ($enquiry->score_reasons ?? [] as $reason)
                                <tr><td>{{ $reason['label'] }}</td><td class="reason-points {{ $reason['points'] < 0 ? 'negative' : '' }}">{{ $reason['points'] > 0 ? '+' : '' }}{{ $reason['points'] }}</td></tr>
                            @empty
                                <tr><td colspan="2" class="muted">No risk indicators were found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </section>

                <section class="panel">
                    <h2>Technical details</h2>
                    <dl class="meta">
                        <dt>IP country</dt><dd>{{ $enquiry->ip_country ?: 'Unknown' }}</dd>
                        <dt>Email country</dt><dd>{{ $enquiry->email_country ?: 'Generic/unknown' }}</dd>
                        <dt>Link countries</dt><dd>{{ $enquiry->link_countries ? implode(', ', $enquiry->link_countries) : 'None' }}</dd>
                        <dt>Completion time</dt><dd>{{ $enquiry->completion_seconds !== null ? $enquiry->completion_seconds.' seconds' : 'Invalid timer' }}</dd>
                        <dt>Turnstile</dt><dd>{{ $enquiry->turnstile_passed === true ? 'Passed' : ($enquiry->turnstile_passed === false ? 'Failed' : 'Not checked') }}</dd>
                        <dt>Owner notification</dt><dd>{{ $enquiry->notification_sent_at?->format('d M Y, g:ia') ?: 'Not sent' }}</dd>
                        <dt>Sender confirmation</dt><dd>{{ $enquiry->confirmation_sent_at?->format('d M Y, g:ia') ?: 'Not sent' }}</dd>
                        <dt>Last scored</dt><dd>{{ $enquiry->last_scored_at?->format('d M Y, g:ia') ?: 'Unknown' }}</dd>
                        <dt>Reviewed by</dt><dd>{{ $enquiry->reviewer?->name ?: 'Not reviewed' }}</dd>
                    </dl>
                </section>
            </div>
        </div>
    </main>
@endsection

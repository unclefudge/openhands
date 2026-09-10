<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $caseStudy['meta'] }}">
    <meta name="theme-color" content="#f7f4ee">
    <title>{{ $caseStudy['title'] }} case study | Open Hands</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <main class="site case-study">
        <div class="site-inner">
            <header class="site-header case-header">
                <a href="{{ route('home') }}" aria-label="Open Hands home"><img src="{{ asset('images/openhands-logo.png') }}" alt="Open Hands" class="brand-logo"></a>
                <a class="case-back" href="{{ route('home') }}#selected-work"><span aria-hidden="true">←</span> All selected work</a>
            </header>

            <section class="case-hero">
                <div class="case-hero-copy">
                    <p class="section-number">{{ $caseStudy['type'] }}</p>
                    <h1>{{ $caseStudy['title'] }}</h1>
                    <p>{{ $caseStudy['lead'] }}</p>
                </div>
                <dl class="case-facts">
                    @foreach ($caseStudy['facts'] as $label => $value)
                        <div><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>
                    @endforeach
                </dl>
            </section>

            <figure class="case-hero-image"><img src="{{ asset($caseStudy['hero']) }}" alt="{{ $caseStudy['hero_alt'] }}"></figure>

            <section class="case-narrative">
                <div class="case-section-label"><p class="section-number">Overview</p></div>
                <div class="case-prose">
                    <h2>{{ $caseStudy['overview_title'] }}</h2>
                    @foreach ($caseStudy['overview'] as $paragraph)<p>{{ $paragraph }}</p>@endforeach
                </div>
            </section>

            <section class="case-capabilities">
                <div class="case-section-label"><p class="section-number">What I built</p></div>
                <div class="capability-grid">
                    @foreach ($caseStudy['capabilities'] as $capability)
                        <article><span>0{{ $loop->iteration }}</span><h3>{{ $capability['title'] }}</h3><p>{{ $capability['copy'] }}</p></article>
                    @endforeach
                </div>
            </section>

            <section class="case-evolution">
                <div>
                    <p class="section-number">How it has evolved</p>
                    <h2>{{ $caseStudy['evolution_title'] }}</h2>
                </div>
                <p>{{ $caseStudy['evolution'] }}</p>
            </section>

            <section class="case-gallery" aria-label="Selected {{ $caseStudy['title'] }} screens">
                <div class="case-gallery-heading"><p class="section-number">Selected screens</p>{{--}}<p>These working screenshots use sample data for layout purposes.</p>--}}</div>
                <div class="gallery-grid">
                    @foreach ($caseStudy['gallery'] as $image)
                        <figure class="gallery-item gallery-item-{{ $loop->iteration }}"><div><img src="{{ asset($image['src']) }}" alt="{{ $image['alt'] }}" loading="lazy"></div><figcaption><strong>{{ $image['title'] }}</strong><span>{{ $image['caption'] }}</span></figcaption></figure>
                    @endforeach
                </div>
            </section>

            <section class="case-technical">
                <div><p class="section-number">Technical overview</p><h2>Built, deployed and cared for independently.</h2></div>
                <div>
                    <p>{{ $caseStudy['technical'] }}</p>
                    <div class="project-tags">@foreach ($caseStudy['tags'] as $tag)<span>{{ $tag }}</span>@endforeach</div>
                </div>
            </section>

            <nav class="case-next" aria-label="Case study navigation">
                <span>Next case study</span>
                <a href="{{ route($caseStudy['next_route']) }}">{{ $caseStudy['next_title'] }} <span aria-hidden="true">↗</span></a>
            </nav>

            <footer><img src="{{ asset('images/openhands-logo.png') }}" alt="Open Hands" class="brand-logo"><p>Custom web applications · Laravel · PHP · Zoho · Automation</p><span>© {{ now()->year }} Open Hands</span></footer>
        </div>
    </main>

    <!-- Cloudflare Web Analytics -->
    <script type='module' src='https://static.cloudflareinsights.com/beacon.min.js' data-cf-beacon='{"token": "7fef8baa320b49d5814cd4acf1c5679c"}'></script><!-- End Cloudflare Web Analytics -->
</body>
</html>

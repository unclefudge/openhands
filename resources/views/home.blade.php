@php
    use Illuminate\Support\Facades\Crypt;

    $services = [
        [
            'number' => '01',
            'title' => 'Custom web applications',
            'copy' => 'Purpose-built Laravel and PHP systems that fit the way your business actually works—from internal portals to booking and client-management platforms.',
            'tags' => ['Laravel', 'PHP', 'MySQL'],
        ],
        [
            'number' => '02',
            'title' => 'Business automation',
            'copy' => 'Replace repetitive steps, disconnected spreadsheets and manual follow-up with practical workflows that save time and reduce mistakes.',
            'tags' => ['Workflows', 'Integrations', 'APIs'],
        ],
        [
            'number' => '03',
            'title' => 'Zoho development',
            'copy' => 'Custom functions, CRM automation and integrations that make Zoho work more naturally for your team and your existing processes.',
            'tags' => ['Zoho CRM', 'Deluge', 'Automation'],
        ],
        [
            'number' => '04',
            'title' => 'Existing system care',
            'copy' => 'Thoughtful improvements, troubleshooting and ongoing development for established PHP applications that still matter to the business.',
            'tags' => ['Upgrades', 'Support', 'Maintenance'],
        ],
    ];

     $outcomes = [
        [
            'label' => 'Business operations',
            'title' => 'One dependable place to manage the work.',
            'copy' => 'Custom applications for jobs, clients, documents, billing, approvals, compliance and reporting—designed around how your team actually works.',
        ],
         [
            'label' => 'Booking and scheduling',
            'title' => 'Bookings made simple for staff and customers.',
            'copy' => 'Online reservations, recurring bookings, availability, conflicts, permissions, customer details and staff administration brought together in one practical system.',
        ],
        [
            'label' => 'Integrations and automation',
            'title' => 'Less double handling between your systems.',
            'copy' => 'APIs, scheduled processes and Zoho CRM automation that move information reliably, trigger follow-up and keep people focused on higher-value work.',
        ],
    ];

    $steps = [
        ['number' => '01', 'title' => 'Understand', 'copy' => 'We start with the problem, the people using the system and what a useful outcome genuinely looks like.'],
        ['number' => '02', 'title' => 'Shape', 'copy' => 'I turn that understanding into a practical approach, with clear priorities and no unnecessary complexity.'],
        ['number' => '03', 'title' => 'Build', 'copy' => 'Development happens in sensible stages, with regular conversations and working progress you can see.'],
        ['number' => '04', 'title' => 'Stay', 'copy' => 'I remain available after launch for improvements, support and the next thing your business needs.'],
    ];

    $projects = [
        [
            'slug' => 'safeworksite',
            'type' => 'Construction operations & compliance',
            'title' => 'SafeWorksite',
            'copy' => 'A long-running Laravel application bringing safety, compliance, inspections, contractor information, planning, reporting and operational workflows into one system.',
            'note' => 'Designed in 2014 and continuously developed since.',
            'image' => 'images/work/safeworksite-planner.jpg',
            'image_alt' => 'SafeWorksite construction planning interface',
            'tags' => ['Laravel', 'Livewire', 'Compliance', 'Automation'],
            'route' => 'work.safeworksite',
        ],
        [
            'slug' => 'c3-booking',
            'type' => 'Venue booking & event operations',
            'title' => 'C3 Booking',
            'copy' => 'A custom platform covering venue bookings, recurring events, room scheduling, catering, labour planning, costing and operational reporting.',
            'note' => 'Built closely with the people who use it every day.',
            'image' => 'images/work/c3-event-pipeline.jpg',
            'image_alt' => 'C3 Booking event workflow board',
            'tags' => ['Laravel', 'Livewire', 'Scheduling', 'Reporting'],
            'route' => 'work.c3-booking',
        ],
        [
            'slug' => 'clientbill',
            'type' => 'Billing & business administration',
            'title' => 'ClientBill',
            'copy' => 'A purpose-built Laravel application used by Open Hands to manage client work, time, invoicing, recurring services and business administration in one connected system.',
            'note' => 'Built from real operational needs and continually refined through day-to-day use.',
            'image' => 'images/work/clientbill-dashboard.jpg',
            'image_alt' => 'ClientBill invoicing dashboard',
            'tags' => ['Laravel 12', 'Livewire', 'Filament', 'Accounting'],
            'route' => 'work.clientbill',
        ],
    ];

    $enquiryTypes = [
        'A custom web application',
        'Help with an existing Laravel system',
        'Zoho or workflow automation',
        'Ongoing development support',
        'Not sure yet',
    ];

    $formShouldOpen = $errors->any() || session()->has('enquiry_sent');
@endphp

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Independent web development in Hobart, specialising in Laravel, PHP, Zoho integrations and practical business automation.">
    <meta name="theme-color" content="#f7f4ee">

    <title>Open Hands | Custom Laravel &amp; PHP Development</title>

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="canonical" href="https://openhands.com.au/">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <main
        class="site"
        id="top"
        x-data="{
            menuOpen: false,
            enquiryOpen: @js($formShouldOpen),
            openEnquiry() {
                this.menuOpen = false;
                this.enquiryOpen = true;
                this.$nextTick(() => this.$refs.firstField?.focus());
            }
        }"
        x-effect="document.body.classList.toggle('modal-open', enquiryOpen)"
        x-on:keydown.escape.window="enquiryOpen = false"
    >
        <div class="site-inner">
            <header class="site-header">
                <a href="#top" aria-label="Open Hands home">
                    <img src="{{ asset('images/openhands-logo.png') }}" alt="Open Hands" class="brand-logo">
                </a>

                <nav class="desktop-nav" aria-label="Main navigation">
                    <a href="#services">Services</a>
                    <a href="#selected-work">Work</a>
                    <a href="#approach">Approach</a>
                    <a href="#about">About</a>
                </nav>

                <button class="header-cta" type="button" x-on:click="openEnquiry()">
                    Start a conversation <span aria-hidden="true">↗</span>
                </button>

                <button class="menu-button" type="button" aria-label="Toggle navigation" x-on:click="menuOpen = ! menuOpen" x-bind:aria-expanded="menuOpen">
                    <span></span>
                    <span></span>
                </button>

                <nav class="mobile-nav" aria-label="Mobile navigation" x-cloak x-show="menuOpen" x-transition x-on:click.outside="menuOpen = false">
                    <a href="#services" x-on:click="menuOpen = false">Services</a>
                    <a href="#selected-work" x-on:click="menuOpen = false">Work</a>
                    <a href="#approach" x-on:click="menuOpen = false">Approach</a>
                    <a href="#about" x-on:click="menuOpen = false">About</a>
                    <button type="button" x-on:click="openEnquiry()">
                        Start a conversation <span aria-hidden="true">↗</span>
                    </button>
                </nav>
            </header>

            <section class="hero">
                <div class="hero-copy">
                    <p class="eyebrow">Independent web developer · Australia</p>
                    <h1>Practical web development. Thoughtfully built.</h1>
                    <p class="hero-lead">
                        I design and build custom web applications, business automation
                        and Zoho integrations for organisations that value practical
                        solutions and a developer who understands the bigger picture.
                    </p>
                    <div class="hero-actions">
                        <button class="button button-primary" type="button" x-on:click="openEnquiry()">
                            Start a conversation <span aria-hidden="true">↗</span>
                        </button>
                        <a class="button button-secondary" href="#services">
                            See how I can help <span aria-hidden="true">↓</span>
                        </a>
                    </div>
                    <div class="availability">
                        <span class="availability-dot"></span>
                        Selectively available for the right ongoing client or focused project.
                    </div>
                </div>

                <div class="proof-grid" aria-label="Open Hands experience">
                    <article class="proof-card proof-years">
                        <span class="card-motif" aria-hidden="true"></span>
                        <strong>20+ years</strong>
                        <span>Building dependable systems</span>
                    </article>

                    <article class="proof-card proof-code">
                        <span class="proof-icon" aria-hidden="true">{ }</span>
                        <strong>Laravel / PHP</strong>
                        <span>Custom systems that fit the way you work</span>
                    </article>

                    <article class="proof-card proof-relationships">
                        <span class="card-motif" aria-hidden="true"></span>
                        <strong>Long-term</strong>
                        <span>Trusted client relationships</span>
                    </article>

                    <article class="proof-card proof-automation">
                        <span class="mini-mark" aria-hidden="true"><i></i></span>
                        <strong>Zoho + Automation</strong>
                    </article>

                    <div class="proof-signoff" aria-label="Thoughtful, practical, dependable">
                        <span class="double-arrow" aria-hidden="true">↠</span>
                        <span>Thoughtful. Practical. Dependable.</span>
                    </div>
                </div>
            </section>

            <section class="section services-section" id="services">
                <div class="section-heading">
                    <p class="section-number">Services</p>
                    <h2>Web development that solves the useful problems.</h2>
                    <p>
                        I’m at my best where business processes and software meet:
                        understanding what people need, simplifying the moving parts and
                        building something dependable around them.
                    </p>
                </div>

                <div class="services-grid">
                    @foreach ($services as $service)
                        <article class="service-card">
                            <div class="service-top">
                                <span>{{ $service['number'] }}</span>
                                <span aria-hidden="true">↗</span>
                            </div>
                            <h3>{{ $service['title'] }}</h3>
                            <p>{{ $service['copy'] }}</p>
                            <div class="tag-row">
                                @foreach ($service['tags'] as $tag)
                                    <span>{{ $tag }}</span>
                                @endforeach
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="engineering-section" aria-labelledby="engineering-title">
                <div class="engineering-copy">
                    <p class="section-number">Built around your business</p>
                    <h2 id="engineering-title">When the pieces need to work together.</h2>
                    <p>
                        The most valuable systems rarely stand alone. I connect the
                        application, business rules, data, CRM and automation into one
                        dependable workflow—without adding complexity just for the sake of it.
                    </p>
                    <div class="engineering-tags">
                        <span>Laravel</span>
                        <span>PHP</span>
                        <span>Zoho</span>
                        <span>APIs</span>
                        <span>Automation</span>
                    </div>
                </div>
                <div class="engineering-art">
                    <img src="{{ asset('images/engineering-wire.jpg') }}" alt="Engineering diagram connecting Laravel, PHP, Zoho and automation workflows">
                </div>
            </section>

            <section class="section work-section" id="work">
                <div class="work-intro">
                    <p class="section-number">What I build</p>
                    <h2>Quietly capable systems, shaped around real work.</h2>
                    <p>
                        The best software often isn’t flashy. It makes a complicated task
                        feel straightforward, gives people confidence, and keeps working
                        long after launch.
                    </p>
                </div>
                <div class="outcomes-list">
                    @foreach ($outcomes as $outcome)
                        <article class="outcome">
                            <span class="outcome-index">0{{ $loop->iteration }}</span>
                            <div>
                                <p class="outcome-label">{{ $outcome['label'] }}</p>
                                <h3>{{ $outcome['title'] }}</h3>
                            </div>
                            <p>{{ $outcome['copy'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="section selected-work-section" id="selected-work">
                <div class="selected-work-heading">
                    <div>
                        <p class="section-number">Selected work</p>
                        <h2>Business systems built for real work.</h2>
                    </div>
                    <p>A few examples of applications I’ve designed, built and continued to develop over time.</p>
                </div>

                <div class="project-grid">
                    @foreach ($projects as $project)
                        <article class="project-card project-card-{{ $project['slug'] }}">
                            <a class="project-image" href="{{ route($project['route']) }}" aria-label="View the {{ $project['title'] }} case study">
                                <img src="{{ asset($project['image']) }}" alt="{{ $project['image_alt'] }}">
                            </a>
                            <div class="project-content">
                                <p class="project-type">{{ $project['type'] }}</p>
                                <h3>{{ $project['title'] }}</h3>
                                <p>{{ $project['copy'] }}</p>
                                <p class="project-note">{{ $project['note'] }}</p>
                                <div class="project-footer">
                                    <div class="project-tags">
                                        @foreach ($project['tags'] as $tag)<span>{{ $tag }}</span>@endforeach
                                    </div>
                                    <a href="{{ route($project['route']) }}">View case study <span aria-hidden="true">↗</span></a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="section approach-section" id="approach">
                <div class="approach-copy">
                    <p class="section-number">How I work</p>
                    <h2>Small enough to stay personal. Experienced enough to see ahead.</h2>
                    <p>
                        You work with the person writing the code. That keeps
                        communication clear, decisions practical and the original purpose
                        of the project close at hand.
                    </p>
                </div>
                <div class="steps">
                    @foreach ($steps as $step)
                        <article class="step">
                            <span>{{ $step['number'] }}</span>
                            <div>
                                <h3>{{ $step['title'] }}</h3>
                                <p>{{ $step['copy'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="section about-section" id="about">
                {{--}}<div class="about-mark" aria-hidden="true"><span></span></div>--}}
                <figure class="about-portrait">
                    <div class="about-portrait-frame">
                        <img
                            src="{{ asset('images/fudge-jordan.jpg') }}"
                            alt="Fudge Jordan, independent web developer behind Open Hands"
                        >
                    </div>

                    <figcaption>
                        <strong>Fudge Jordan</strong>
                        <span>Independent PHP developer</span>
                        <small>Hobart, Tasmania</small>
                    </figcaption>

                    <span class="about-portrait-line" aria-hidden="true"></span>
                </figure>
                <div class="about-copy">
                    <p class="section-number">About Open Hands</p>
                    <h2>Independent by choice.</h2>
                    <p class="about-lead">
                        Open Hands is a small web development business based in Hobart, Tasmania, working with clients across Australia. I’ve spent more than twenty years building and caring
                        for the systems businesses rely on each day.
                    </p>
                    <div class="about-columns">
                        <p>
                            My strength is backend development—particularly Laravel and
                            PHP—along with the patient problem-solving needed to understand
                            an existing business and improve it without creating unnecessary
                            disruption.
                        </p>
                        <p>
                            I deliberately work with a small number of clients. That means I
                            can know their systems well, respond personally and build
                            relationships measured in years rather than individual tickets.
                        </p>
                    </div>
                    <div class="values-row">
                        <span>Clear communication</span>
                        <span>Practical thinking</span>
                        <span>Long-term care</span>
                    </div>
                </div>
            </section>

            <section class="contact-section" id="contact">
                <div>
                    <p class="section-number">Start a conversation</p>
                    <h2>Have a useful problem to solve?</h2>
                    <p>
                        If you need an experienced developer to improve or support an existing Laravel system,
                        automate a Zoho workflow, or build a focused business application, tell me a little about it.
                    </p>
                </div>
                <div class="contact-action">
                    <button type="button" x-on:click="openEnquiry()">
                        Tell me about your project <span aria-hidden="true">↗</span>
                    </button>
                    <span>
                        A short form helps me understand genuine enquiries and respond thoughtfully.
                    </span>
                </div>
            </section>

            <footer>
                <img src="{{ asset('images/openhands-logo.png') }}" alt="Open Hands" class="brand-logo">
                <p>Custom web applications · Laravel · PHP · Zoho · Automation</p>
                <span>© {{ now()->year }} Open Hands</span>
            </footer>
        </div>

        <div class="modal-backdrop" role="presentation" x-cloak x-show="enquiryOpen" x-transition.opacity x-on:click.self="enquiryOpen = false">
            <section class="enquiry-modal" role="dialog" aria-modal="true" aria-labelledby="enquiry-title" x-trap.noscroll="enquiryOpen">
                <div class="modal-head">
                    <div>
                        <p class="eyebrow">Start a conversation</p>
                        <h2 id="enquiry-title">Tell me what you’re working on.</h2>
                        <p>
                            A few useful details will help me understand whether I’m the right
                            person to help. You won’t be added to a mailing list.
                        </p>
                    </div>
                    <button type="button" class="modal-close" x-on:click="enquiryOpen = false" aria-label="Close enquiry form">×</button>
                </div>

                @if (session('enquiry_sent'))
                    <div class="form-status success" role="status">
                        {{ session('enquiry_sent') }}
                    </div>
                @else
                    @if ($errors->any())
                        <div class="form-status error" role="alert">
                            <strong>Please check the highlighted details.</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="enquiry-form" method="POST" action="{{ route('enquiry.store') }}">
                        @csrf

                        <div class="honeypot" aria-hidden="true">
                            <label for="website">Website</label>
                            <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
                        </div>

                        <input type="hidden" name="form_started_at" value="{{ Crypt::encryptString((string) now()->timestamp) }}">

                        <div class="form-grid">
                            <label>
                                Your name <span>*</span>
                                <input x-ref="firstField" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required>
                            </label>
                            <label>
                                Email address <span>*</span>
                                <input name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
                            </label>
                            <label>
                                Business or organisation
                                <input name="organisation" type="text" value="{{ old('organisation') }}" autocomplete="organization">
                            </label>
                            <label>
                                Phone <small>optional</small>
                                <input name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel">
                            </label>
                        </div>

                        <fieldset>
                            <legend>What can I help with? <span>*</span></legend>
                            <div class="choice-grid">
                                @foreach ($enquiryTypes as $type)
                                    <label class="choice">
                                        <input type="radio" name="service" value="{{ $type }}" @checked(old('service') === $type) required>
                                        <span>{{ $type }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>

                        <div class="form-grid form-grid-lower">
                            <label>
                                Approximate timeframe
                                <select name="timeframe">
                                    <option value="">Select if known</option>
                                    @foreach (['As soon as practical', 'Within 1–3 months', 'Within 3–6 months', 'Exploring for later'] as $timeframe)
                                        <option value="{{ $timeframe }}" @selected(old('timeframe') === $timeframe)>
                                            {{ $timeframe }}
                                        </option>
                                    @endforeach
                                </select>
                            </label>
                            <label>
                                How did you hear about Open Hands?
                                <select name="referral">
                                    <option value="">Select one</option>
                                    @foreach (['Personal referral', 'LinkedIn', 'Existing client or colleague', 'Web search', 'Other'] as $referral)
                                        <option value="{{ $referral }}" @selected(old('referral') === $referral)>
                                            {{ $referral }}
                                        </option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        <label class="message-field">
                            Tell me about the problem or idea <span>*</span>
                            <textarea name="message" rows="6" minlength="30" required placeholder="What are you hoping to build, improve or make easier? A short, genuine overview is perfect.">{{ old('message') }}</textarea>
                            <small>Minimum 30 characters</small>
                        </label>

                        <label class="genuine-check">
                            <input name="genuine" type="checkbox" value="yes" @checked(old('genuine')) required>
                            <span>
                                This is a genuine project or development enquiry—not a sales or recruitment message.
                            </span>
                        </label>

                        @if (config('services.turnstile.site_key'))
                            <div class="cf-turnstile turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}" data-theme="light"></div>
                        @endif

                        <div class="form-submit">
                            <button class="button button-primary" type="submit">
                                Send enquiry <span aria-hidden="true">↗</span>
                            </button>
                        </div>
                    </form>
                @endif
            </section>
        </div>
    </main>

    @if (config('services.turnstile.site_key'))
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif

    <!-- Cloudflare Web Analytics -->
    <script type='module' src='https://static.cloudflareinsights.com/beacon.min.js' data-cf-beacon='{"token": "7fef8baa320b49d5814cd4acf1c5679c"}'></script><!-- End Cloudflare Web Analytics -->


</body>
</html>

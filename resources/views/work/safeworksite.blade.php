@php
$caseStudy = [
    'title' => 'SafeWorksite', 'type' => 'Construction operations & compliance platform',
    'meta' => 'SafeWorksite case study: a long-running construction operations and compliance platform built by Open Hands.',
    'lead' => 'A business-critical application that brings construction safety, compliance, planning, inspections, contractor information and reporting into one connected system.',
    'facts' => ['In use since' => '2014', 'Industry' => 'Residential construction', 'My role' => 'Design, development & ongoing care'],
    'hero' => 'images/work/safeworksite-planner.jpg', 'hero_alt' => 'SafeWorksite planning screen showing construction tasks',
    'overview_title' => 'One system supporting work from the office to the job site.',
    'overview' => ['SafeWorksite began as a focused way to manage safety requirements for an Australian construction business. It has grown alongside the organisation into a broad operational platform used across construction teams, contractors and management.', 'Instead of separate spreadsheets, emails and disconnected processes, people can work from dependable information in one place—with the right tools and views for their role. SafeWorksite also connects with the organisation’s other business systems rather than operating as an isolated application.'],
    'capabilities' => [
        ['title' => 'Construction planning', 'copy' => 'Weekly, site, trade and roster planning tools connect people, tasks and changing construction schedules.'],
        ['title' => 'Safety & compliance', 'copy' => 'Contractor documents, inductions, SWMS, toolbox talks and site records help teams meet practical compliance requirements.'],
        ['title' => 'Inspections & follow-up', 'copy' => 'Quality, completion and maintenance workflows keep defects, evidence, notes and responsibility visible.'],
        ['title' => 'Integrations & automation', 'copy' => 'Zoho client enquiries and workflows connect with SafeWorksite, while HIA API integration and scheduled automation reduce manual handling.'],
    ],
    'evolution_title' => 'Continuously improved, not simply launched and left behind.',
    'evolution' => 'The application has moved through multiple generations of Laravel while the business kept using it every day. Recent work has introduced Livewire interfaces, reusable components, modern planning tools and a managed scheduling system. Its connections with Zoho and HIA have also developed alongside the business—improving the experience without discarding years of valuable business knowledge.',
    'gallery' => [
        ['src' => 'images/work/safeworksite-planner.jpg', 'alt' => 'SafeWorksite planning interface', 'title' => 'Site planning', 'caption' => 'Visual task planning across active construction sites.'],
        ['src' => 'images/work/safeworksite-scheduler.jpg', 'alt' => 'SafeWorksite scheduled operations dashboard', 'title' => 'Scheduled operations', 'caption' => 'Monitoring automated reports and operational processes.'],
        ['src' => 'images/work/safeworksite-compliance.jpg', 'alt' => 'SafeWorksite company compliance record', 'title' => 'Contractor compliance', 'caption' => 'Company details, required documents and status in one view.'],
        ['src' => 'images/work/safeworksite-swms.jpg', 'alt' => 'SafeWorksite SWMS document', 'title' => 'Safety documentation', 'caption' => 'Structured Safe Work Method Statements connected to site activity.'],
        ['src' => 'images/work/safeworksite-toolbox.jpg', 'alt' => 'SafeWorksite Toolbox Talks', 'title' => 'Toolbox talks', 'caption' => 'Easy step through process to create Toolbox talks'],
        ['src' => 'images/work/safeworksite-foc.jpg', 'alt' => 'SafeWorksite FOC reporting', 'title' => 'FOC reporting', 'caption' => 'Final inspections and completion checklists.'],
    ],
    'technical' => 'I designed, developed, deployed and continue to maintain SafeWorksite independently. The system combines a long-established Laravel and MySQL foundation with Livewire interfaces, background jobs, scheduled automation and document storage. Zoho integration brings client enquiries into the operational workflow and supports automation between both systems, while the HIA API connects additional industry information and processes.',
    'tags' => ['Laravel', 'PHP', 'Livewire', 'MySQL', 'Zoho CRM', 'HIA API', 'Queues', 'Automation'],
    'next_route' => 'work.c3-booking', 'next_title' => 'C3 Booking',
];
@endphp
@include('work.case-study')

@php
$caseStudy = [
    'title' => 'ClientBill', 'type' => 'Purpose-built business administration',
    'meta' => 'ClientBill case study: a modern internal billing and administration application built by Open Hands.',
    'lead' => 'A purpose-built business system created and used by Open Hands to connect client work, time, invoicing, recurring services and administration.',
    'facts' => ['Built' => '2026', 'Used by' => 'Open Hands', 'My role' => 'Product design & development'],
    'hero' => 'images/work/clientbill-dashboard.jpg', 'hero_alt' => 'ClientBill dark-mode invoice dashboard',
    'overview_title' => 'A focused business platform shaped by real operational needs.',
    'overview' => ['ClientBill is a purpose-built business administration platform I designed and developed for Open Hands. It replaces disconnected manual processes by keeping client work, time entries, recurring services, invoices and transactions connected in one system.', 'Because I use it in the business day to day, new features are driven by genuine operational needs rather than hypothetical requirements. It is also a practical place to refine ideas with the current Laravel ecosystem before applying those lessons in larger client systems.'],
    'capabilities' => [
        ['title' => 'Time & client work', 'copy' => 'Calendar and list views make recorded work easy to review before it becomes an invoice.'],
        ['title' => 'Flexible invoicing', 'copy' => 'Time, hosting, domains and one-off items can be brought together into a clear client invoice.'],
        ['title' => 'Business records', 'copy' => 'Transactions and administrative information stay connected to the business activity they represent.'],
        ['title' => 'Useful automation', 'copy' => 'Recurring obligations, outstanding items and invoice preparation are surfaced at the right time.'],
    ],
    'evolution_title' => 'A modern application that continues to earn its place.',
    'evolution' => 'ClientBill began as a focused answer to Open Hands’ billing needs and has grown into a broader business administration system. Continued day-to-day use reveals the next worthwhile improvement, so additions are measured by whether they make genuine work simpler.',
    'gallery' => [
        ['src' => 'images/work/clientbill-dashboard.jpg', 'alt' => 'ClientBill invoice dashboard', 'title' => 'Invoice dashboard', 'caption' => 'Work ready to bill, renewals and outstanding items at a glance.'],
        ['src' => 'images/work/clientbill-calendar.jpg', 'alt' => 'ClientBill calendar of work', 'title' => 'Work calendar', 'caption' => 'Recorded client activity viewed across the month.'],
        ['src' => 'images/work/clientbill-invoice.jpg', 'alt' => 'ClientBill invoice details', 'title' => 'Invoice preparation', 'caption' => 'Time and custom items combined into a practical invoice.'],
        ['src' => 'images/work/clientbill-transactions.jpg', 'alt' => 'ClientBill transaction list', 'title' => 'Transactions', 'caption' => 'Business income and expenses tracked in context.'],
        ['src' => 'images/work/clientbill-profitloss.jpg', 'alt' => 'ClientBill profit loss', 'title' => 'Profit / Loss', 'caption' => 'Profit / Loss reporting.'],
        ['src' => 'images/work/clientbill-bas.jpg', 'alt' => 'ClientBill BAS calculations', 'title' => 'BAS', 'caption' => 'BAS reporting view for the quarter / year.'],
    ],
    'technical' => 'ClientBill is designed and developed independently using Laravel 12, Livewire, Filament and MySQL. It demonstrates my current approach to product structure, modern reactive interfaces, automation and maintainable business logic.',
    'tags' => ['Laravel 12', 'PHP', 'Livewire', 'Filament', 'MySQL', 'Automation'],
    'next_route' => 'work.safeworksite', 'next_title' => 'SafeWorksite',
];
@endphp
@include('work.case-study')

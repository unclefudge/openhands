@php
$caseStudy = [
    'title' => 'C3 Booking', 'type' => 'Venue booking & event operations',
    'meta' => 'C3 Booking case study: custom venue, event and café booking software built by Open Hands.',
    'lead' => 'A custom platform that connects venue bookings, event planning, rooms, catering, labour, café reservations, costing and reporting.',
    'facts' => ['Developed since' => '2023', 'Industry' => 'Events & hospitality', 'My role' => 'Design, development & ongoing care'],
    'hero' => 'images/work/c3-event-pipeline.jpg', 'hero_alt' => 'C3 Booking event workflow pipeline',
    'overview_title' => 'Complicated events made manageable for the people delivering them.',
    'overview' => ['C3 Booking supports the day-to-day work behind a busy convention centre. It brings event enquiries, quotes, bookings and delivery together so staff can see where every event stands and what still needs attention.', 'The platform also extends into café operations and a customer-facing reservation experience, giving the organisation one tailored system across several connected parts of the venue.'],
    'capabilities' => [
        ['title' => 'Event workflow', 'copy' => 'Enquiries move through quoting, agreements, deposits, planning, delivery and final invoicing with clear status.'],
        ['title' => 'Rooms & scheduling', 'copy' => 'Recurring bookings, room availability and visual schedules help staff identify conflicts and use spaces well.'],
        ['title' => 'Planning & costing', 'copy' => 'Catering, sessions, labour, room setup and event costs are planned around how the venue really operates.'],
        ['title' => 'Connected services', 'copy' => 'Public café bookings, operational views, reports and Xero workflows reduce re-entry across the business.'],
    ],
    'evolution_title' => 'Developed in conversation with the people doing the work.',
    'evolution' => 'The system has expanded as staff have used it and discovered the next useful improvement. New areas such as labour costing, café reservations, operational reports and accounting connections have been added around the core event workflow, with day-to-day feedback shaping each iteration.',
    'gallery' => [
        ['src' => 'images/work/c3-event-pipeline.jpg', 'alt' => 'C3 Booking event pipeline', 'title' => 'Event pipeline', 'caption' => 'A clear view from first enquiry through to confirmed event.'],
        ['src' => 'images/work/c3-event-overview.jpg', 'alt' => 'C3 Booking event overview', 'title' => 'Event readiness', 'caption' => 'Event details, tasks, files and progress brought together.'],
        ['src' => 'images/work/c3-labour-costing.jpg', 'alt' => 'C3 Booking labour costing screen', 'title' => 'Labour costing', 'caption' => 'Staff requirements and room work calculated across event days.'],
        ['src' => 'images/work/c3-cafe-schedule.jpg', 'alt' => 'C3 café bookings timeline', 'title' => 'Café operations', 'caption' => 'Table availability and reservations shown across the day.'],
        ['src' => 'images/work/c3-rivulet-booking.jpg', 'alt' => 'Rivulet Cafe public booking page', 'title' => 'Customer booking', 'caption' => 'A simple, branded reservation flow connected to staff operations.'],
    ],
    'technical' => 'I designed, developed, deployed and continue to maintain the application independently. It uses Laravel, Livewire and MySQL, with calendar-style interfaces, background processing, reporting and Xero integration supporting the operational workflow.',
    'tags' => ['Laravel', 'PHP', 'Livewire', 'Filament', 'MySQL', 'Xero', 'Scheduling'],
    'next_route' => 'work.clientbill', 'next_title' => 'ClientBill',
];
@endphp
@include('work.case-study')

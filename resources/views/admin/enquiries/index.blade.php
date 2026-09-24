@extends('admin.layout')

@section('title', 'Enquiries')

@section('body')
    <main class="page">
        <div class="page-head">
            <div><h1>Website enquiries</h1><div class="muted">The summary covers the past seven days. The review queue covers all time.</div></div>
        </div>

        @if (session('status'))<div class="flash">{{ session('status') }}</div>@endif
        @if (session('error'))<div class="flash error">{{ session('error') }}</div>@endif

        <section class="stats" aria-label="Seven day enquiry summary">
            <div class="stat"><strong>{{ $summary['total'] }}</strong><span>Total received</span></div>
            <div class="stat"><strong>{{ $summary['delivered'] }}</strong><span>Delivered</span></div>
            <div class="stat"><strong>{{ $summary['suspicious'] }}</strong><span>Suspicious</span></div>
            <div class="stat"><strong>{{ $summary['quarantined'] }}</strong><span>Quarantined</span></div>
            <div class="stat"><strong>{{ $summary['blocked'] }}</strong><span>Blocked</span></div>
            <div class="stat"><strong>{{ $summary['awaiting_review'] }}</strong><span>Awaiting review</span></div>
        </section>

        <section class="panel">
            <form class="filters" method="GET">
                <label>Search<input type="search" name="search" value="{{ request('search') }}" placeholder="Name, email, organisation or message"></label>
                <label>System decision<select name="status"><option value="">All decisions</option>@foreach (['delivered', 'suspicious', 'quarantined', 'blocked'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></label>
                <label>Your review<select name="review"><option value="">All reviews</option>@foreach (['unreviewed', 'genuine', 'spam'] as $review)<option value="{{ $review }}" @selected(request('review') === $review)>{{ ucfirst($review) }}</option>@endforeach</select></label>
                <div class="actions"><button class="button" type="submit">Filter</button><a class="button secondary" href="{{ route('admin.enquiries.index') }}">Clear</a></div>
            </form>

            <div class="table-wrap">
                <table>
                    <thead><tr><th>Date</th><th>Sender</th><th>Message</th><th>Score</th><th>Decision</th><th>Review</th></tr></thead>
                    <tbody>
                        @forelse ($enquiries as $enquiry)
                            <tr>
                                <td style="white-space:nowrap;"><a href="{{ route('admin.enquiries.show', $enquiry) }}">{{ $enquiry->created_at->format('d M Y') }}</a><br><span class="muted">{{ $enquiry->created_at->format('g:ia') }}</span></td>
                                <td><strong>{{ $enquiry->name }}</strong><br><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a><br><span class="muted">{{ $enquiry->organisation ?: 'No organisation' }}</span></td>
                                <td class="message-preview">{{ \Illuminate\Support\Str::limit($enquiry->message, 135) }}</td>
                                <td><span class="score">{{ $enquiry->spam_score }}</span>/100</td>
                                <td><span class="badge {{ $enquiry->spam_status }}">{{ ucfirst($enquiry->spam_status) }}</span></td>
                                <td><span class="badge {{ $enquiry->review_status }}">{{ ucfirst($enquiry->review_status) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="muted">No enquiries match these filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($enquiries->hasPages())
                <nav class="pagination"><span>@if ($enquiries->previousPageUrl())<a href="{{ $enquiries->previousPageUrl() }}">← Previous</a>@endif</span><span>Page {{ $enquiries->currentPage() }} of {{ $enquiries->lastPage() }}</span><span>@if ($enquiries->nextPageUrl())<a href="{{ $enquiries->nextPageUrl() }}">Next →</a>@endif</span></nav>
            @endif
        </section>
    </main>
@endsection

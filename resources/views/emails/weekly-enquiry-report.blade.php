<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><title>Open Hands weekly enquiry report</title></head>
<body style="margin:0;background:#f4f1ea;color:#202426;font-family:Arial,sans-serif;">
    <div style="max-width:720px;margin:0 auto;padding:32px 20px;">
        <div style="padding:30px;background:#ffffff;border-top:5px solid #36bdd5;">
            <p style="margin:0 0 8px;color:#1289a0;font-size:12px;font-weight:bold;text-transform:uppercase;letter-spacing:.12em;">Open Hands website</p>
            <h1 style="margin:0 0 8px;font-size:28px;">Weekly enquiry report</h1>
            <p style="margin:0 0 25px;color:#647276;">{{ $summary['from']->format('j M Y') }} – {{ $summary['to']->format('j M Y') }}</p>

            <table role="presentation" style="width:100%;border-collapse:collapse;font-size:14px;">
                @foreach (['total' => 'Total received', 'delivered' => 'Delivered', 'suspicious' => 'Suspicious', 'quarantined' => 'Quarantined', 'blocked' => 'Blocked', 'confirmed_genuine' => 'Reviewed as genuine', 'confirmed_spam' => 'Reviewed as spam', 'awaiting_review' => 'Awaiting review'] as $key => $label)
                    <tr><td style="padding:8px 0;border-bottom:1px solid #e5e9e7;color:#647276;">{{ $label }}</td><td style="padding:8px 0;border-bottom:1px solid #e5e9e7;text-align:right;font-weight:bold;">{{ $summary[$key] }}</td></tr>
                @endforeach
            </table>

            @if ($reviewQueue->isNotEmpty())
                <h2 style="margin:28px 0 10px;font-size:18px;">Needs review</h2>
                @foreach ($reviewQueue as $enquiry)
                    <div style="padding:14px 0;border-bottom:1px solid #e5e9e7;">
                        <strong>{{ $enquiry->name }}</strong> · {{ $enquiry->spam_score }}/100 · {{ ucfirst($enquiry->spam_status) }}<br>
                        <span style="color:#647276;">{{ \Illuminate\Support\Str::limit($enquiry->message, 150) }}</span><br>
                        <a href="{{ route('admin.enquiries.show', $enquiry) }}">Review this enquiry</a>
                    </div>
                @endforeach
            @else
                <p style="margin:26px 0 0;color:#257a58;font-weight:bold;">No suspicious enquiries are waiting for review.</p>
            @endif

            <p style="margin:26px 0 0;"><a href="{{ route('admin.enquiries.index') }}" style="display:inline-block;padding:11px 16px;background:#1289a0;color:#fff;text-decoration:none;border-radius:6px;font-weight:bold;">Open enquiry dashboard</a></p>
        </div>
    </div>
</body>
</html>

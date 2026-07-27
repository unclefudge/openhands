<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>New Open Hands website enquiry</title>
</head>
<body style="margin:0;background:#f4f1ea;color:#202426;font-family:Arial,sans-serif;">
    <div style="max-width:680px;margin:0 auto;padding:32px 20px;">
        <div style="padding:30px;background:#ffffff;border-top:5px solid #36bdd5;">
            <p style="margin:0 0 8px;color:#1289a0;font-size:12px;font-weight:bold;text-transform:uppercase;letter-spacing:.12em;">
                Open Hands website
            </p>
            <h1 style="margin:0 0 26px;font-size:28px;">New project enquiry</h1>

            <table role="presentation" style="width:100%;border-collapse:collapse;font-size:14px;">
                <tr>
                    <td style="width:155px;padding:8px 0;color:#647276;vertical-align:top;">Name</td>
                    <td style="padding:8px 0;">{{ $enquiry['name'] }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#647276;vertical-align:top;">Email</td>
                    <td style="padding:8px 0;"><a href="mailto:{{ $enquiry['email'] }}">{{ $enquiry['email'] }}</a></td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#647276;vertical-align:top;">Organisation</td>
                    <td style="padding:8px 0;">{{ $enquiry['organisation'] ?: 'Not provided' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#647276;vertical-align:top;">Phone</td>
                    <td style="padding:8px 0;">{{ $enquiry['phone'] ?: 'Not provided' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#647276;vertical-align:top;">Looking for</td>
                    <td style="padding:8px 0;">{{ $enquiry['service'] }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#647276;vertical-align:top;">Timeframe</td>
                    <td style="padding:8px 0;">{{ $enquiry['timeframe'] ?: 'Not provided' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#647276;vertical-align:top;">Referral</td>
                    <td style="padding:8px 0;">{{ $enquiry['referral'] ?: 'Not provided' }}</td>
                </tr>
            </table>

            <h2 style="margin:28px 0 10px;font-size:17px;">Problem or idea</h2>
            <div style="padding:18px;background:#f4f1ea;line-height:1.65;white-space:pre-wrap;">{{ $enquiry['message'] }}</div>
        </div>
    </div>
</body>
</html>

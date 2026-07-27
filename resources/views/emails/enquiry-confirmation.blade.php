<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Your Open Hands enquiry has been received</title>
</head>
<body style="margin:0;background:#f4f1ea;color:#202426;font-family:Arial,sans-serif;">
    <div style="max-width:680px;margin:0 auto;padding:32px 20px;">
        <div style="padding:30px;background:#ffffff;border-top:5px solid #36bdd5;">
            <p style="margin:0 0 8px;color:#1289a0;font-size:12px;font-weight:bold;text-transform:uppercase;letter-spacing:.12em;">
                Open Hands
            </p>

            <h1 style="margin:0 0 24px;font-size:28px;line-height:1.2;">
                Thanks for getting in touch.
            </h1>

            <p style="margin:0 0 18px;font-size:16px;line-height:1.7;">
                Hi {{ $enquiry['name'] }},
            </p>

            <p style="margin:0 0 18px;font-size:16px;line-height:1.7;">
                Your enquiry has come through successfully. I’ll read through
                the details and respond personally as soon as I can.
            </p>

            <div style="margin:26px 0;padding:18px;background:#f4f1ea;border-left:3px solid #36bdd5;">
                <p style="margin:0 0 6px;color:#647276;font-size:12px;font-weight:bold;text-transform:uppercase;letter-spacing:.08em;">
                    Your enquiry
                </p>
                <p style="margin:0;font-size:16px;line-height:1.6;">
                    {{ $enquiry['service'] }}
                </p>
            </div>

            <p style="margin:26px 0 0;font-size:16px;line-height:1.7;">
                Regards,<br>
                <strong>Fudge Jordan</strong>
            </p>
        </div>
    </div>
</body>
</html>

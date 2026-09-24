<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>@yield('title', 'Enquiry review') · Open Hands</title>
    <style>
        :root { color-scheme: light; --ink:#202426; --muted:#667478; --paper:#f4f1ea; --white:#fff; --line:#dce2df; --brand:#1289a0; --brand2:#36bdd5; --green:#257a58; --amber:#a86400; --red:#a53b37; }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--paper); color:var(--ink); font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif; }
        a { color:var(--brand); }
        button,input,select { font:inherit; }
        .topbar { background:var(--white); border-top:5px solid var(--brand2); border-bottom:1px solid var(--line); }
        .topbar-inner,.page { width:min(1180px,calc(100% - 32px)); margin:0 auto; }
        .topbar-inner { min-height:72px; display:flex; align-items:center; justify-content:space-between; gap:20px; }
        .brand { color:var(--ink); font-weight:800; text-decoration:none; letter-spacing:.02em; }
        .brand span { display:block; color:var(--brand); font-size:11px; letter-spacing:.14em; text-transform:uppercase; }
        .page { padding:34px 0 60px; }
        .page-head { display:flex; justify-content:space-between; gap:20px; align-items:flex-start; margin-bottom:24px; }
        h1 { margin:0 0 6px; font-size:clamp(28px,4vw,42px); line-height:1.08; }
        h2 { margin:0 0 16px; font-size:20px; }
        p { line-height:1.6; }
        .muted { color:var(--muted); }
        .panel { background:var(--white); border:1px solid var(--line); border-radius:12px; padding:22px; box-shadow:0 8px 28px rgba(31,43,45,.05); }
        .flash { margin-bottom:20px; padding:13px 16px; border-radius:8px; background:#e9f7f1; border-left:4px solid var(--green); }
        .flash.error { background:#fff0ef; border-left-color:var(--red); }
        .stats { display:grid; grid-template-columns:repeat(6,minmax(0,1fr)); gap:12px; margin-bottom:20px; }
        .stat { background:var(--white); border:1px solid var(--line); border-radius:10px; padding:16px; }
        .stat strong { display:block; font-size:26px; }
        .stat span { color:var(--muted); font-size:12px; }
        .filters { display:grid; grid-template-columns:minmax(220px,1fr) 190px 190px auto; gap:10px; align-items:end; margin-bottom:20px; }
        label { display:block; font-size:13px; font-weight:700; }
        input,select { width:100%; margin-top:6px; padding:10px 12px; border:1px solid #bdc9c6; border-radius:7px; background:#fff; color:var(--ink); }
        .button { display:inline-flex; align-items:center; justify-content:center; min-height:40px; padding:9px 15px; border:0; border-radius:7px; background:var(--brand); color:#fff; text-decoration:none; cursor:pointer; font-weight:700; }
        .button.secondary { background:#e9efed; color:var(--ink); }
        .button.danger { background:var(--red); }
        .button.good { background:var(--green); }
        .button.small { min-height:34px; padding:6px 11px; font-size:13px; }
        .actions { display:flex; flex-wrap:wrap; gap:8px; }
        .table-wrap { overflow:auto; }
        table { width:100%; border-collapse:collapse; }
        th,td { padding:12px 10px; text-align:left; border-bottom:1px solid #e8ecea; vertical-align:top; }
        th { color:var(--muted); font-size:11px; letter-spacing:.07em; text-transform:uppercase; }
        td { font-size:14px; }
        tr:last-child td { border-bottom:0; }
        .badge { display:inline-block; padding:4px 8px; border-radius:999px; background:#edf1ef; font-size:12px; font-weight:800; white-space:nowrap; }
        .badge.delivered,.badge.genuine { background:#e3f4ec; color:var(--green); }
        .badge.suspicious { background:#fff2ce; color:var(--amber); }
        .badge.quarantined { background:#ffe5c3; color:#925000; }
        .badge.blocked,.badge.spam { background:#ffe2df; color:var(--red); }
        .score { font-size:18px; font-weight:850; }
        .message-preview { max-width:430px; color:#49565a; }
        .message { padding:18px; border-radius:8px; background:var(--paper); white-space:pre-wrap; line-height:1.65; }
        .detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
        .meta { display:grid; grid-template-columns:150px 1fr; gap:9px 14px; margin:0; }
        .meta dt { color:var(--muted); }
        .meta dd { margin:0; overflow-wrap:anywhere; }
        .reason-points { font-weight:850; text-align:right; white-space:nowrap; }
        .reason-points.negative { color:var(--green); }
        .pagination { display:flex; justify-content:space-between; align-items:center; margin-top:18px; color:var(--muted); }
        .logout { background:none; border:0; color:var(--brand); cursor:pointer; font-weight:700; }
        .login-shell { min-height:100vh; display:grid; place-items:center; padding:20px; }
        .login-card { width:min(440px,100%); }
        .login-card .button { width:100%; margin-top:10px; }
        @media (max-width:900px) { .stats { grid-template-columns:repeat(3,1fr); } .filters { grid-template-columns:1fr 1fr; } .detail-grid { grid-template-columns:1fr; } }
        @media (max-width:620px) { .stats { grid-template-columns:repeat(2,1fr); } .filters { grid-template-columns:1fr; } .page-head { display:block; } .meta { grid-template-columns:1fr; gap:3px; } .meta dd { margin-bottom:10px; } }
    </style>
</head>
<body>
    @auth
        <header class="topbar">
            <div class="topbar-inner">
                <a class="brand" href="{{ route('admin.enquiries.index') }}"><span>Open Hands</span>Enquiry review</a>
                <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="logout" type="submit">Log out</button></form>
            </div>
        </header>
    @endauth

    @yield('body')
</body>
</html>

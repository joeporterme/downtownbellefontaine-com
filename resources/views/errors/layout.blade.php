<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') &middot; Downtown Bellefontaine</title>
    <meta name="robots" content="noindex">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root { --teal:#01757f; --teal-d:#014b52; --accent:#f3773d; --accent-d:#e0632b; }
        *{box-sizing:border-box;margin:0;padding:0}
        html,body{height:100%}
        body{
            font-family:'Montserrat',system-ui,-apple-system,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;
            color:#fff;min-height:100%;position:relative;overflow:hidden;line-height:1.6;
            display:flex;align-items:center;justify-content:center;padding:48px 20px;
            -webkit-font-smoothing:antialiased;
        }
        .bg{position:absolute;inset:0;z-index:0}
        .bg img{width:100%;height:100%;object-fit:cover;transform:scale(1.05)}
        .bg::after{
            content:"";position:absolute;inset:0;
            background:linear-gradient(to bottom, rgba(1,117,127,.70), rgba(1,75,82,.82) 46%, rgba(0,46,52,.95));
        }
        .content{position:relative;z-index:1;text-align:center;max-width:660px;width:100%}
        .logo{height:42px;width:auto;margin:0 auto 44px;display:block;filter:drop-shadow(0 6px 16px rgba(0,0,0,.35))}
        .eyebrow{
            display:inline-block;font-weight:700;font-size:13px;letter-spacing:.28em;text-transform:uppercase;
            color:#ffd9c2;margin-bottom:6px;
        }
        .code{
            font-weight:900;font-size:clamp(96px,23vw,196px);line-height:.86;letter-spacing:-.04em;color:#fff;
            text-shadow:0 24px 60px rgba(0,0,0,.4);
        }
        h1{font-size:clamp(23px,3.6vw,34px);font-weight:800;letter-spacing:-.01em;margin:18px 0 14px}
        p.lead{color:rgba(255,255,255,.85);font-size:18px;max-width:46ch;margin:0 auto 34px}
        .actions{display:flex;flex-wrap:wrap;gap:12px;justify-content:center;margin-bottom:30px}
        .btn{
            display:inline-flex;align-items:center;gap:8px;font-weight:700;font-size:15px;text-decoration:none;
            padding:14px 28px;border-radius:999px;transition:transform .15s ease,background .15s ease;
        }
        .btn-primary{background:var(--accent);color:#fff;box-shadow:0 14px 30px -10px rgba(243,119,61,.65)}
        .btn-primary:hover{transform:translateY(-2px);background:var(--accent-d)}
        .btn-ghost{background:rgba(255,255,255,.08);color:#fff;border:2px solid rgba(255,255,255,.35)}
        .btn-ghost:hover{background:rgba(255,255,255,.16);transform:translateY(-2px)}
        .links{
            display:flex;flex-wrap:wrap;gap:8px 22px;justify-content:center;
            border-top:1px solid rgba(255,255,255,.18);padding-top:22px;margin:0 auto;max-width:520px;
        }
        .links a{
            color:rgba(255,255,255,.85);text-decoration:none;font-weight:600;font-size:14px;
            border-bottom:2px solid transparent;padding-bottom:2px;transition:.15s ease;
        }
        .links a:hover{color:#fff;border-color:var(--accent)}
        .footnote{margin-top:26px;font-size:12.5px;color:rgba(255,255,255,.6)}
        @media (max-width:480px){
            .logo{margin-bottom:32px}
            p.lead{font-size:16px}
        }
    </style>
</head>
<body>
    <div class="bg"><img src="/images/home/welcome-courthouse.jpg" alt="" aria-hidden="true"></div>
    <main class="content">
        <img class="logo" src="/images/logo-white.svg" alt="Downtown Bellefontaine">
        <span class="eyebrow">@yield('eyebrow', 'Downtown Bellefontaine')</span>
        <div class="code">@yield('code')</div>
        <h1>@yield('heading')</h1>
        <p class="lead">@yield('message')</p>
        <div class="actions">
            <a class="btn btn-primary" href="/">Back to Home</a>
            <a class="btn btn-ghost" href="/businesses">Browse Businesses</a>
        </div>
        <nav class="links">
            <a href="/events">Events</a>
            <a href="/map">Map</a>
            <a href="/plan-a-visit">Plan a Visit</a>
            <a href="/contact">Contact</a>
        </nav>
        <p class="footnote">Downtown Bellefontaine, Ohio &middot; The heart of Logan County</p>
    </main>
</body>
</html>

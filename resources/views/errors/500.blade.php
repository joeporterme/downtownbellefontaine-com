<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Something Went Wrong &middot; Downtown Bellefontaine</title>
    <meta name="robots" content="noindex">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root{
            --teal:#01757f; --teal-d:#015c66; --teal-dd:#01434c;
            --maroon:#88292f; --accent:#f3773d;
            --bg:#fdfbf6; --bg2:#f4efe4;
            --ink:#16282b; --muted:#5b6a6d;
            --card:#ffffff; --border:#ece4d4;
            --shadow:0 30px 60px -25px rgba(1,67,76,.45);
        }
        @media (prefers-color-scheme: dark){
            :root{
                --bg:#07181a; --bg2:#0a1f22;
                --ink:#eef5f5; --muted:#9bb1b3;
                --card:#0e262a; --border:#16383d;
                --shadow:0 30px 60px -25px rgba(0,0,0,.7);
            }
        }
        *{box-sizing:border-box;margin:0;padding:0}
        html,body{height:100%}
        body{
            font-family:'Montserrat',system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;
            color:var(--ink);
            background:radial-gradient(1200px 600px at 50% -10%, var(--bg2), var(--bg));
            display:flex;align-items:center;justify-content:center;
            padding:32px 20px;line-height:1.6;-webkit-font-smoothing:antialiased;
        }
        .card{
            position:relative;width:100%;max-width:600px;background:var(--card);
            border:1px solid var(--border);border-radius:28px;
            padding:48px 40px 40px;text-align:center;box-shadow:var(--shadow);overflow:hidden;
        }
        .card::before{
            content:"";position:absolute;inset:0 0 auto 0;height:6px;
            background:linear-gradient(90deg,var(--teal),var(--maroon));
        }
        .watermark{position:absolute;right:-40px;bottom:-40px;width:220px;opacity:.06;pointer-events:none;user-select:none}
        .logo{height:40px;width:auto;margin:0 auto 28px;display:block}
        .pineapple{height:96px;width:auto;margin:0 auto 18px;display:block;filter:drop-shadow(0 12px 18px rgba(1,67,76,.25))}
        .code{font-weight:900;font-size:14px;letter-spacing:.22em;text-transform:uppercase;color:var(--teal);display:inline-block;margin-bottom:10px}
        @media (prefers-color-scheme: dark){.code{color:#3aa7b0}}
        h1{font-size:30px;font-weight:800;letter-spacing:-.01em;margin-bottom:12px}
        p.lead{color:var(--muted);font-size:17px;max-width:42ch;margin:0 auto 28px}
        .actions{display:flex;flex-wrap:wrap;gap:12px;justify-content:center;margin-bottom:26px}
        .btn{display:inline-flex;align-items:center;gap:8px;font-weight:700;font-size:15px;text-decoration:none;padding:13px 26px;border-radius:999px;transition:transform .15s ease,box-shadow .15s ease}
        .btn-primary{background:var(--teal);color:#fff;box-shadow:0 10px 22px -8px rgba(1,117,127,.7)}
        .btn-primary:hover{transform:translateY(-2px);background:var(--teal-d)}
        .links{display:flex;flex-wrap:wrap;gap:8px 22px;justify-content:center;border-top:1px solid var(--border);padding-top:22px;margin-top:4px}
        .links a{color:var(--ink);text-decoration:none;font-weight:600;font-size:14px;opacity:.85;border-bottom:2px solid transparent;padding-bottom:2px;transition:.15s ease}
        .links a:hover{opacity:1;color:var(--teal);border-color:var(--accent)}
        @media (prefers-color-scheme: dark){.links a:hover{color:#3aa7b0}}
        .footnote{margin-top:24px;font-size:12.5px;color:var(--muted);opacity:.8}
        @media (max-width:480px){.card{padding:38px 24px 32px;border-radius:22px}h1{font-size:25px}p.lead{font-size:16px}}
    </style>
</head>
<body>
    <main class="card">
        <img class="watermark" src="/images/home/pineapple.svg" alt="" aria-hidden="true">
        <img class="logo" src="/images/logo.svg" alt="Downtown Bellefontaine">
        <img class="pineapple" src="/images/home/pineapple.svg" alt="">
        <span class="code">Error 500</span>
        <h1>Something went wrong on our end.</h1>
        <p class="lead">That&rsquo;s our fault, not yours. Our team has been notified and we&rsquo;re already on it. Please try again in a few minutes &mdash; thanks for your patience.</p>
        <div class="actions">
            <a class="btn btn-primary" href="/">Return home</a>
        </div>
        <nav class="links">
            <a href="/businesses">Businesses</a>
            <a href="/events">Events</a>
            <a href="/map">Map</a>
            <a href="/plan-a-visit">Plan a Visit</a>
            <a href="/contact">Contact</a>
        </nav>
        <p class="footnote">Downtown Bellefontaine, Ohio &middot; The heart of Logan County</p>
    </main>
</body>
</html>

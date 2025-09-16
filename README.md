<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>E-learning Platform — Colorful README</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="" crossorigin="anonymous" />
  <style>
    :root{
      --bg:#0f1724;
      --card:#0b1220;
      --glass: rgba(255,255,255,0.04);
      --accentA: linear-gradient(135deg,#7c3aed,#06b6d4);
      --accentB: linear-gradient(135deg,#f59e0b,#ef4444);
      --muted: #94a3b8;
      --glass-2: rgba(255,255,255,0.03);
      color-scheme: dark;
    }
    *{box-sizing:border-box}
    body{
      margin:0;
      font-family:Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
      background: radial-gradient(1200px 600px at 10% 10%, rgba(124,58,237,0.12), transparent),
                  radial-gradient(900px 500px at 90% 90%, rgba(6,182,212,0.08), transparent),
                  var(--bg);
      color:#e6eef8;
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
      padding:48px 32px;
    }
    .container{max-width:1100px;margin:0 auto}

    header{display:flex;gap:20px;align-items:center}
    .logo{
      width:96px;height:96px;border-radius:18px;display:flex;align-items:center;justify-content:center;
      background:conic-gradient(from 180deg at 50% 50%, #7c3aed, #06b6d4, #f59e0b);
      box-shadow:0 8px 30px rgba(7,12,20,0.6);
      transform:translateZ(0);
      font-weight:800;font-size:28px;color:white
    }
    h1{margin:0;font-size:28px;line-height:1.05}
    p.lead{margin:6px 0 0;color:var(--muted)}

    .badges{display:flex;gap:10px;margin:20px 0 28px;flex-wrap:wrap}
    .card{background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
          border:1px solid rgba(255,255,255,0.03);
          padding:18px;border-radius:14px;box-shadow: 0 6px 22px rgba(2,6,23,0.6)}

    .hero{display:grid;grid-template-columns:1fr 360px;gap:22px;align-items:start}
    .panel{padding:18px;border-radius:12px}

    /* features grid */
    .features{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
    .feature{display:flex;gap:12px;align-items:flex-start;padding:12px;border-radius:10px;background:var(--glass)}
    .feature .icon{width:44px;height:44;border-radius:10px;background:linear-gradient(135deg,#111827,rgba(255,255,255,0.02));display:grid;place-items:center;font-size:20px}

    table.tech{width:100%;border-collapse:collapse;margin-top:12px}
    table.tech td, table.tech th{padding:10px;border-bottom:1px dashed rgba(255,255,255,0.03);text-align:left}
    table.tech th{color:var(--muted);font-weight:600}

    pre.code{background:linear-gradient(90deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));padding:12px;border-radius:10px;overflow:auto;font-family:ui-monospace, SFMono-Regular, Menlo, Monaco, 'Roboto Mono', monospace}

    .install{display:flex;flex-direction:column;gap:8px}
    .install .step{display:flex;gap:12px;align-items:flex-start}
    .kbd{background:var(--glass-2);padding:6px 10px;border-radius:8px;font-weight:600;color:var(--muted);font-size:13px}

    .screenshot{height:160px;border-radius:10px;background:linear-gradient(135deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));display:grid;place-items:center;color:var(--muted);border:1px dashed rgba(255,255,255,0.03)}

    footer{margin-top:28px;color:var(--muted);font-size:13px;display:flex;justify-content:space-between;align-items:center}

    /* responsive */
    @media (max-width:900px){
      .hero{grid-template-columns:1fr}
      .badges{justify-content:flex-start}
    }

    /* tiny interactive dots */
    .ribbon{display:inline-grid;padding:6px 12px;border-radius:999px;background:linear-gradient(90deg,#7c3aed,#06b6d4);font-weight:700;color:#051025}

  </style>
</head>
<body>
  <div class="container">
    <header>
      <div class="logo">E‑L</div>
      <div>
        <h1>E‑Learning Platform <span style="font-weight:600;color:var(--muted);font-size:14px">— PHP + MySQL + ES6</span></h1>
        <p class="lead">A modern full-stack web app for secure & interactive communication between doctors and students.</p>
      </div>
    </header>

    <div class="badges">
      <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
      <img src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
      <img src="https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JS" />
      <img src="https://img.shields.io/badge/Animation-GSAP-88CE02?style=for-the-badge&logo=greensock&logoColor=white" alt="GSAP" />
    </div>

    <main class="hero">
      <section class="card panel">
        <h3 style="margin:0 0 8px;">🚀 Features Overview</h3>
        <div class="features">
          <div class="feature"><div class="icon">🔐</div><div><strong>Secure Authentication</strong><div class="muted" style="color:var(--muted);font-size:13px">PHP + MySQL with hashed passwords & sessions</div></div></div>
          <div class="feature"><div class="icon">🎨</div><div><strong>Modern Gradient UI</strong><div class="muted" style="color:var(--muted);font-size:13px">HTML5, CSS3 & GSAP-powered animations</div></div></div>
          <div class="feature"><div class="icon">📝</div><div><strong>Rich-Text Editing</strong><div class="muted" style="color:var(--muted);font-size:13px">TinyMCE integration for lectures & notes</div></div></div>
          <div class="feature"><div class="icon">📑</div><div><strong>Seamless PDF Viewing</strong><div class="muted" style="color:var(--muted);font-size:13px">PDF.js embedded viewer</div></div></div>
          <div class="feature"><div class="icon">🎥</div><div><strong>Video Integration</strong><div class="muted" style="color:var(--muted);font-size:13px">YouTube Iframe API for lectures</div></div></div>
          <div class="feature"><div class="icon">🎙️</div><div><strong>Lecture Recording</strong><div class="muted" style="color:var(--muted);font-size:13px">MediaDevices API — record & store</div></div></div>
          <div class="feature"><div class="icon">⚡</div><div><strong>Real-Time Communication</strong><div class="muted" style="color:var(--muted);font-size:13px">AJAX + Axios for chat & updates</div></div></div>
          <div class="feature"><div class="icon">🚄</div><div><strong>Optimized Performance</strong><div class="muted" style="color:var(--muted);font-size:13px">Async rendering & animation batching</div></div></div>
        </div>

        <h3 style="margin-top:18px">🛠️ Tech Stack</h3>
        <table class="tech">
          <tr><th>Frontend</th><td>HTML5 · CSS3 · JavaScript (ES6+) · GSAP · TinyMCE · PDF.js · YouTube Iframe API · MediaDevices API</td></tr>
          <tr><th>Backend</th><td>PHP (XAMPP) · MySQL (Relational DB)</td></tr>
          <tr><th>Communication</th><td>AJAX · Axios</td></tr>
        </table>

        <h3 style="margin-top:18px">⚙️ Installation & Setup (Local - XAMPP)</h3>
        <div class="install">
          <div class="step"><div class="kbd">1</div><div><strong>Clone the repo</strong><div style="color:var(--muted);font-size:13px;margin-top:6px"><code>git clone https://github.com/programmer-2003-Egypt/e-learning-project</code></div></div></div>
          <div class="step"><div class="kbd">2</div><div><strong>Move files to XAMPP</strong><div style="color:var(--muted);font-size:13px;margin-top:6px">Place project folder inside <code>htdocs/</code></div></div></div>
          <div class="step"><div class="kbd">3</div><div><strong>Start XAMPP</strong><div style="color:var(--muted);font-size:13px;margin-top:6px">Enable <code>Apache</code> and <code>MySQL</code> services</div></div></div>
          <div class="step"><div class="kbd">4</div><div><strong>Import DB</strong><div style="color:var(--muted);font-size:13px;margin-top:6px">Use phpMyAdmin to import <code>database.sql</code></div></div></div>
          <div class="step"><div class="kbd">5</div><div><strong>Open project</strong><div style="color:var(--muted);font-size:13px;margin-top:6px">Visit <code>http://localhost/e-learning-project</code></div></div></div>
        </div>

      </section>

      <aside class="card panel">
        <div style="display:flex;gap:12px;align-items:center;justify-content:space-between">
          <div>
            <div class="ribbon">Open Source</div>
            <div style="margin-top:10px;font-weight:700;font-size:16px">Project Status</div>
            <div style="color:var(--muted);margin-top:6px">Alpha · actively developed</div>
          </div>
          <div style="text-align:right;color:var(--muted);font-size:12px">Last updated: <strong>Sep 16, 2025</strong></div>
        </div>

        <hr style="border:none;border-top:1px dashed rgba(255,255,255,0.03);margin:12px 0">

        <div style="margin-top:8px">
          <div style="font-weight:700;margin-bottom:8px">Quick Commands</div>
          <pre class="code"># start xampp
# place files -> htdocs/
# import database.sql via phpmyadmin
</pre>
        </div>

        <div style="margin-top:12px">
          <div style="font-weight:700;margin-bottom:8px">Screenshots</div>
          <div class="screenshot">Screenshot placeholder</div>
        </div>

        <div style="margin-top:12px">
          <div style="font-weight:700;margin-bottom:8px">Roadmap</div>
          <ul style="margin:0;padding-left:18px;color:var(--muted)">
            <li>✅ Authentication & roles</li>
            <li>✅ Rich editor & PDF viewer</li>
            <li>🔄 Real-time chat (improvements)</li>
            <li>🔄 Video call integration (experimental)</li>
          </ul>
        </div>

      </aside>
    </main>

    <footer>
      <div>Made with <span style="color:#ff6b6b">❤</span> · <strong>programmer-2003-Egypt</strong></div>
      <div style="color:var(--muted)">License: MIT</div>
    </footer>
  </div>

  <!-- GSAP CDN for subtle animations -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script>
    // tiny entrance animation using GSAP (no external data)
    try{
      gsap.from('.logo',{duration:0.9, y:-12, opacity:0, ease:'power3.out'});
      gsap.from('h1',{duration:0.9, x:-18, opacity:0, delay:0.05, ease:'power3.out'});
      gsap.from('.card',{duration:0.85, y:8, opacity:0, stagger:0.06, delay:0.12});
    }catch(e){/* silent fallback */}
  </script>
</body>
</html>

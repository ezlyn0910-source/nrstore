{{-- resources/views/aboutus/index.blade.php --}}
@extends('layouts.app')

@section('styles')
<style>
  :root{
    --primary-dark:#1a2412;
    --primary-green:#2d4a35;
    --accent-green:#2f6032;
    --rare-green:#357a38;
    --light-green:#4caf50;

    --bg:#f8f9fa;
    --card:#ffffff;
    --text:#1a2412;
    --muted:#6b7c72;
    --border:#e9ecef;

    --shadow:0 10px 34px rgba(0,0,0,.10);
    --shadow-soft:0 6px 20px rgba(0,0,0,.08);

    --radius-xl:22px;
    --radius-lg:16px;
    --radius-md:12px;

    --container:1200px;
  }

  .about-page{ background:var(--bg); color:var(--text); }
  .about-page *{ box-sizing:border-box; }
  .about-page a{ color:inherit; text-decoration:none; }
  .about-page img{ max-width:100%; display:block; }

  .about-page .container{
    width:100%;
    max-width:var(--container);
    margin:0 auto;
    padding:0 16px;
  }

  .section{ padding:70px 0; }
  .section-title{ display:flex; flex-direction:column; gap:10px; margin-bottom:26px; }
  .section-title h2{ margin:0; font-size:clamp(22px,3vw,34px); letter-spacing:-.6px; }
  .section-title p{ margin:0; color:var(--muted); max-width:860px; line-height:1.7; }
  .underline{
    width:86px; height:4px; border-radius:99px;
    background:linear-gradient(90deg,var(--rare-green),var(--light-green));
  }

  .btn{
    display:inline-flex; align-items:center; justify-content:center;
    gap:10px; padding:12px 18px; border-radius:999px;
    border:1px solid rgba(255,255,255,.22);
    background:rgba(255,255,255,.12);
    color:#fff; font-weight:800; letter-spacing:.2px;
    transition:transform .15s ease, background .15s ease, border-color .15s ease;
    backdrop-filter:blur(10px);
    cursor:pointer;
  }
  .btn:hover{ transform:translateY(-1px); background:rgba(255,255,255,.18); border-color:rgba(255,255,255,.30); }
  .btn.btn-solid{
    background:linear-gradient(135deg,var(--rare-green),var(--light-green));
    border-color:transparent;
    box-shadow:0 10px 26px rgba(53,122,56,.25);
  }

  .chip{
    display:inline-flex; align-items:center; gap:8px;
    padding:7px 12px; border-radius:999px;
    background:rgba(45,74,53,.35);
    border:1px solid rgba(255,255,255,.12);
    color:#fff; font-weight:800; font-size:12px;
    backdrop-filter: blur(10px);
  }

  .hero{
    position:relative;
    overflow:hidden;
    min-height: calc(100vh - var(--site-header-h, 0px)) !important;
    padding: 0;
    display: flex;
    align-items: center;
    padding: 28px 0 !important;
    background:
      radial-gradient(900px 420px at 15% 20%, rgba(76,175,80,.20), transparent 55%),
      radial-gradient(900px 420px at 85% 10%, rgba(45,74,53,.22), transparent 55%),
      linear-gradient(180deg, rgba(26,36,18,.85), rgba(26,36,18,.88));
    color:#fff;
  }

  .hero-title{
    display:grid;
    margin:12px 0 10px;
    line-height:1.05;
    letter-spacing:-1px;
    font-size:clamp(34px, 4.5vw, 54px);
  }

  .hero-title__ghost,
  .hero-title__type{
    grid-area:1 / 1;
  }

  .hero-title__ghost{
    visibility:hidden;
    pointer-events:none;
  }

  .hero-title__type{
    white-space:normal;
  }
  #typeText::after{
    content: "|";
    margin-left: 4px;
    opacity: 1;
    animation: blink 1s infinite;
  }
  @keyframes blink{
    0%,50%{ opacity:1; }
    51%,100%{ opacity:0; }
  }
  #typeText.done::after{
    display:none;
  }

  .hero h1,
  .hero-title {
    text-shadow:
      0 2px 6px rgba(0, 0, 0, 0.35),
      0 6px 18px rgba(0, 0, 0, 0.25);
  }

  .hero::before{
    content:"";
    position:absolute; inset:0;
    background-image:url("/images/about_hero_bg.png");
    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;
    opacity:.60;
    filter:none;
    transform:scale(1);
  }
  .hero::after{
    content:"";
    position:absolute; 
    inset:-2px;
    background:linear-gradient(
      180deg,
      rgba(26,36,18,.50),
      rgba(26,36,18,.80)
    );
    pointer-events:none;
  }

  .hero .container{
    width: 100%;
  }

  .hero-inner{
    position:relative;
    z-index:2;
    display:grid;
    grid-template-columns:1.15fr .85fr;
    gap:26px;
    margin: 0;
    padding: 0;
    align-items:stretch;
  }

  .hero h1{
    margin:12px 0 10px;
    font-size:clamp(34px,4.5vw,54px);
    line-height:1.05;
    letter-spacing:-1px;
  }
  .hero p{
    text-align: justify;
    text-justify: inter-word;
    margin:0;
    color:rgba(255,255,255,.86);
    line-height:1.75;
    font-size:18px;
    max-width:720px;
    text-shadow: 0 1px 4px rgba(0, 0, 0, 0.5);
  }
  .hero-actions{ display:flex; flex-wrap:wrap; gap:12px; margin-top:18px; }

  .hero-card{
    height: 100%;
    display: flex;
    flex-direction: column;
    border-radius:var(--radius-xl);
    background:rgba(255,255,255,.08);
    border:1px solid rgba(255,255,255,.16);
    box-shadow:0 16px 40px rgba(0,0,0,.18);
    padding:18px;
    backdrop-filter:blur(12px);
    animation: kpiFloat 5.5s ease-in-out infinite;
  }
  @keyframes kpiFloat{
    0%,100%{ transform: translateY(0); }
    50%{ transform: translateY(-6px); }
  }
  .hero-card h3{ margin:0 0 10px; font-size:16px; letter-spacing:-.2px; }
  .hero-kpis{
    flex: 1;
    display:grid;
    grid-template-columns:repeat(2, minmax(0,1fr));
    gap:14px;
    margin-top:10px;
    align-content: stretch;
  }
  .kpi{
    display:flex;
    align-items:flex-start;
    gap:12px;
    height:100%;
    padding:16px;
    border-radius:16px;
    background:rgba(255,255,255,.08);
    border:1px solid rgba(255,255,255,.14);
    transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
  }
  .kpi-ic{
    width:42px;
    height:42px;
    border-radius:12px;
    flex: 0 0 auto;

    background: rgba(255,255,255,.10);
    border: 1px solid rgba(255,255,255,.16);
    backdrop-filter: blur(10px);
    box-shadow: 0 12px 26px rgba(0,0,0,.18);

    display:grid;
    place-items:center;
    overflow:hidden;
  }
  .kpi-ic img{
    width:24px;
    height:24px;
    object-fit:contain;
    opacity:.95;
    transform: translateZ(0);
    animation: icPulse 2.8s ease-in-out infinite;
  }
  @keyframes icPulse{
    0%,100%{ transform: scale(1); opacity:.92; }
    50%{ transform: scale(1.06); opacity:1; }
  }
  .kpi-body{ min-width:0; }
  .kpi .num{ font-size:22px; font-weight:900; letter-spacing:-.4px; }
  .kpi .lbl{ font-size:12px; color:rgba(255,255,255,.78); margin-top:4px; line-height:1.4; }

  .kpi:hover{
    transform: translateY(-3px);
    background: rgba(255,255,255,.10);
    box-shadow: 0 18px 42px rgba(0,0,0,.22);
  }
  .kpi:hover .kpi-ic{
    transform: scale(1.04);
  }

  .section-dark{
    position: relative;
    overflow: hidden;
    color: #fff;
    min-height: calc(100vh - var(--site-header-h, 0px));
    display: flex;
    align-items: center;
    padding: 0;
    background:
      radial-gradient(900px 420px at 15% 20%, rgba(76,175,80,.20), transparent 55%),
      radial-gradient(900px 420px at 85% 10%, rgba(45,74,53,.22), transparent 55%),
      linear-gradient(180deg, rgba(26,36,18,.85), rgba(26,36,18,.92));
  }

  .section-dark::before{
    content:"";
    position:absolute;
    inset:0;
    background-image:url("/images/about_hero_bg.png");
    background-size:cover;
    background-position:center;
    opacity:.18;
    filter:blur(1px);
    transform:scale(1.03);
    z-index:0;
  }

  .section-dark > .container{
    width: 100%;
    padding: 90px 16px;
    position: relative;
    z-index: 1;
  }

  .section-dark .section-title p,
  .section-dark .card p,
  .section-dark p{
    color: rgba(255,255,255,.85);
  }

  .section-dark .t-item{
    background: rgba(255,255,255,.92);
    color: var(--primary-dark);
  }

  .section-dark .t-title{
    color: var(--primary-dark);
  }

  .section-dark .t-desc{
    color: #4a5a50;
  }

  .section-dark .t-year{
    color: var(--accent-green);
  }

  .section-dark .card{
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.14);
    backdrop-filter: blur(10px);
  }
  .section-dark .card,
  .section-dark .card h3{
    color: #fff;
  }

  .section-dark .card p{
    color: rgba(255,255,255,.85);
  }

  #journey .section-title{
    margin: 0 0 18px;
  }

  #journey.section-dark{
    position: relative;
    overflow: hidden;
    color: #fff;
    min-height: calc(100vh - var(--site-header-h, 0px));
    display: flex;
    align-items: center;
    padding: 0;
    background: linear-gradient(
      180deg,
      rgba(26,36,18,.88),
      rgba(26,36,18,.92)
    );
  }

  #journey.section-dark::before{
    content: "";
    position: absolute;
    inset: 0;
    background-image: url("/images/about_journey_bg.png");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    filter: none;
    opacity: .35;
    z-index: 0;
  }

  #journey.section-dark > .container{
    width: 100%;
    position: relative;
    z-index: 1;
    padding: 40px 22px;
  }

  .grid-3{ display:grid; grid-template-columns:repeat(3, minmax(0,1fr)); gap:16px; }
  .grid-2{ display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:16px; }
  .card{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:var(--radius-xl);
    box-shadow:var(--shadow-soft);
    padding:18px;
    transition:transform .18s ease, box-shadow .18s ease;
  }
  .card:hover{ transform:translateY(-2px); box-shadow:0 16px 42px rgba(0,0,0,.10); }
  .card h3{ margin:0 0 8px; font-size:18px; letter-spacing:-.35px; }
  .card p{ margin:0; color:var(--muted); line-height:1.75; font-size:14.5px; }

  .icon{
    width:44px; height:44px; border-radius:14px;
    background:rgba(45,74,53,.08);
    border:1px solid rgba(45,74,53,.14);
    display:grid; place-items:center;
    color:var(--primary-green);
    margin-bottom:10px;
    font-weight:900;
    user-select:none;
  }

  .timeline{
    padding-left:18px;
    border-left:2px dashed rgba(45,74,53,.22);
    display:flex; flex-direction:column; gap:14px;
  }
  .t-item{
    position:relative;
    padding:14px 14px 14px 16px;
    border-radius:18px;
    border:1px solid var(--border);
    background:#fff;
    box-shadow:var(--shadow-soft);
    transition:transform .18s ease, box-shadow .18s ease;
  }
  .t-item:hover{ transform:translateY(-2px); box-shadow:0 16px 42px rgba(0,0,0,.10); }
  .t-item::before{
    content:"";
    position:absolute;
    left:-11px; top:18px;
    width:18px; height:18px;
    border-radius:99px;
    background:linear-gradient(135deg,var(--rare-green),var(--light-green));
    box-shadow:0 10px 20px rgba(53,122,56,.25);
    border:3px solid #fff;
  }
  .t-year{ font-weight:900; color:var(--primary-green); font-size:13px; }
  .t-title{ margin:4px 0 6px; font-weight:900; letter-spacing:-.4px; }
  .t-desc{ margin:0; color:var(--muted); line-height:1.7; font-size:14.5px; }

  .tabs{
    display:flex; gap:10px; flex-wrap:wrap;
    padding:10px; border-radius:999px;
    border:1px solid var(--border);
    background:#fff;
    box-shadow:var(--shadow-soft);
    width:fit-content;
  }
  .tab{
    border:1px solid transparent;
    background:transparent;
    padding:10px 12px;
    border-radius:999px;
    font-weight:900;
    color:var(--muted);
    cursor:pointer;
    transition:background .15s ease, color .15s ease, border-color .15s ease, transform .15s ease;
    font-size:13px;
    user-select:none;
  }
  .tab:hover{ transform:translateY(-1px); }
  .tab.active{ background:rgba(45,74,53,.08); border-color:rgba(45,74,53,.16); color:var(--primary-green); }
  .tab-panel{ margin-top:14px; display:none; }
  .tab-panel.active{ display:block; }

  .faq{ display:flex; flex-direction:column; gap:12px; }
  .faq-item{
    background:#fff;
    border:1px solid var(--border);
    border-radius:var(--radius-xl);
    box-shadow:var(--shadow-soft);
    overflow:hidden;
  }
  .faq-q{
    display:flex; align-items:center; justify-content:space-between;
    gap:12px;
    width:100%;
    padding:16px 16px;
    background:transparent;
    border:0;
    cursor:pointer;
    font-weight:900;
    color:var(--primary-dark);
    letter-spacing:-.3px;
    text-align:left;
  }
  .faq-q span{ font-size:15px; }
  .faq-a{
    padding:0 16px 16px;
    color:var(--muted);
    line-height:1.75;
    font-size:14.5px;
    display:none;
  }
  .faq-item.open .faq-a{ display:block; }
  .chev{
    width:32px; height:32px; border-radius:999px;
    display:grid; place-items:center;
    border:1px solid rgba(45,74,53,.14);
    background:rgba(45,74,53,.06);
    color:var(--primary-green);
    flex:0 0 auto;
    transition:transform .2s ease;
    user-select:none;
  }
  .faq-item.open .chev{ transform:rotate(180deg); }

  .cta{
    background:
      radial-gradient(900px 420px at 15% 30%, rgba(76,175,80,.16), transparent 55%),
      linear-gradient(135deg, rgba(45,74,53,.96), rgba(26,36,18,.96));
    color:#fff;
    border-radius:var(--radius-xl);
    padding:22px;
    border:1px solid rgba(255,255,255,.12);
    box-shadow:0 18px 46px rgba(0,0,0,.18);
    display:flex; align-items:center; justify-content:space-between; gap:16px;
    position:relative;
    overflow:hidden;
  }
  .cta h3{ margin:0 0 6px; font-size:20px; letter-spacing:-.5px; }
  .cta p{ margin:0; color:rgba(255,255,255,.86); line-height:1.7; font-size:14.5px; max-width:700px; }
  .cta-actions{ display:flex; gap:10px; flex-wrap:wrap; }

  .reveal{ opacity:0; transform:translate3d(0,18px,0); will-change:opacity,transform; }
  .reveal.in{
    opacity:1; transform:translate3d(0,0,0);
    transition:opacity .75s cubic-bezier(.2,.8,.2,1),
               transform .75s cubic-bezier(.2,.8,.2,1);
    transition-delay:var(--d,0ms);
  }
  .reveal--from-left{ transform:translate3d(-32px,0,0); }
  .reveal--from-right{ transform:translate3d(32px,0,0); }
  .reveal--from-bottom{ transform:translate3d(0,28px,0); }
  .reveal--pop{ transform:translate3d(0,22px,0) scale(.98); }

  @media (max-width:980px){
    .hero-inner{ align-items: start; }
    .hero-card{ height: auto; animation: none; }
    .grid-3{ grid-template-columns:1fr; }
    .grid-2{ grid-template-columns:1fr; }
    .cta{ flex-direction:column; align-items:flex-start; }

    .hero{
      min-height: auto !important;
      padding: 64px 0 44px !important;
      align-items: flex-start;
    }
  }
  @media (max-width:520px){
    .hero{
      min-height: calc(100vh - 120px);
      padding: 24px 0;
      align-items: flex-start;
    }
    .hero-inner{ align-items: start; }
  }
  @media (prefers-reduced-motion: reduce){
    .reveal, .reveal.in{ transition:none !important; transform:none !important; opacity:1 !important; }
    html{ scroll-behavior:auto !important; }
  }
</style>
@endsection

@section('content')
<div class="about-page">

  <!-- HERO -->
  <header class="hero">
    <div class="container">
      <div class="hero-inner">

        <!-- HERO TEXT -->
        <div class="reveal reveal--from-left">

          <h1 class="hero-title">
            <span class="hero-title__ghost" aria-hidden="true">
              Secure. Reliable. Built for long-term growth.
            </span>
            <span id="typeText" class="hero-title__type"></span>
          </h1>

          <p>
            NR Intellitech Sdn Bhd is a Malaysia-based technology company established in 2006, with a strong foundation in secure IT operations and long-term technology management. 
            We work closely with organizations to protect sensitive information, manage IT assets responsibly, and implement technology solutions that align with real-world operational needs. 
            Our expertise spans secure data destruction and IT asset disposition (ITAD), cybersecurity services, compliance-focused reporting, and scalable software and hardware solutions. 
            By combining practical execution with a security-first mindset, we help businesses reduce risk, maintain compliance, and modernize their systems in a way that is reliable, 
            transparent, and sustainable over the long term.
          </p>

          <div class="hero-actions">
            <a class="btn btn-solid" href="#services">Explore Services <span aria-hidden="true">→</span></a>
            <a class="btn" href="#contact">Get In Touch <span aria-hidden="true">✦</span></a>
          </div>

        </div>

        <!-- HERO KPI CARD -->
        <aside class="hero-card reveal reveal--from-right" id="heroKpiCard" aria-label="Company quick facts">
          <h3>At a Glance</h3>

          <div class="hero-kpis">
            <div class="kpi">
              <div class="kpi-ic">
                <img src="{{ asset('images/icons/shield.png') }}" alt="Experience">
              </div>
              <div class="kpi-body">
                <div class="num"><span class="count" data-to="19">0</span></div>
                <div class="lbl">Years of experience</div>
              </div>
            </div>

            <div class="kpi">
              <div class="kpi-ic">
                <img src="{{ asset('images/icons/globe.png') }}" alt="Countries">
              </div>
              <div class="kpi-body">
                <div class="num"><span class="count" data-to="10" data-suffix="+">0</span></div>
                <div class="lbl">Countries reached</div>
              </div>
            </div>

            <div class="kpi">
              <div class="kpi-ic">
                <img src="{{ asset('images/icons/briefcase.png') }}" alt="Projects">
              </div>
              <div class="kpi-body">
                <div class="num"><span class="count" data-to="150" data-suffix="+">0</span></div>
                <div class="lbl">Projects delivered</div>
              </div>
            </div>

            <div class="kpi">
              <div class="kpi-ic">
                <img src="{{ asset('images/icons/handshake.png') }}" alt="Client-first">
              </div>
              <div class="kpi-body">
                <div class="num"><span class="count" data-to="50" data-suffix="%">0</span></div>
                <div class="lbl">Client-first execution</div>
              </div>
            </div>
          </div>

        </aside>

      </div>
    </div>
  </header>

  <!-- OUR STORY -->
  <section id="story" class="section">
    <div class="container">
      <div class="section-title reveal reveal--from-bottom">
        <div class="underline"></div>
        <h2>Our Story</h2>
        <p>
          NR Intellitech has evolved across storage solutions, refurbishment, secure data services, and cybersecurity—
          guided by one principle: security must be practical, measurable, and trusted.
        </p>
      </div>

      <div class="grid-3">
        <div class="card reveal reveal--from-bottom">
          <div class="icon">①</div>
          <h3>Practical by design</h3>
          <p>Solutions aligned to real workflows—clear scope, usable deliverables, predictable outcomes.</p>
        </div>

        <div class="card reveal reveal--from-bottom">
          <div class="icon">②</div>
          <h3>Security-first</h3>
          <p>Protection embedded from planning to delivery—so risk stays controlled as you scale.</p>
        </div>

        <div class="card reveal reveal--from-bottom">
          <div class="icon">③</div>
          <h3>Built for growth</h3>
          <p>Scalable systems and responsible asset handling that support long-term expansion.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- JOURNEY -->
  <section id="journey" class="section section-dark">
    <div class="container">
      <div class="section-title reveal reveal--from-bottom">
        <div class="underline"></div>
        <h2>Our Journey</h2>
        <p>Key milestones that shaped our capabilities—from hardware roots to security-driven services.</p>
      </div>

      <div class="grid-2">
        <div class="timeline">
          <div class="t-item reveal reveal--from-left">
            <div class="t-year">2006</div>
            <div class="t-title">Company Founded</div>
            <p class="t-desc">Started in Kuala Lumpur with strong capabilities in storage solutions and distribution.</p>
          </div>

          <div class="t-item reveal reveal--from-left">
            <div class="t-year">2012</div>
            <div class="t-title">Refurbishment & SME Software</div>
            <p class="t-desc">Expanded into refurbishment across major brands and tailored software for SMEs.</p>
          </div>

          <div class="t-item reveal reveal--from-left">
            <div class="t-year">2018</div>
            <div class="t-title">Certified Data Erasure & Cloud</div>
            <p class="t-desc">Introduced certified data sanitization services and extended into cloud ecosystems.</p>
          </div>

          <div class="t-item reveal reveal--from-left">
            <div class="t-year">2024</div>
            <div class="t-title">Cybersecurity Focus</div>
            <p class="t-desc">Strengthened security testing, readiness, and compliance-aligned practices.</p>
          </div>
        </div>

        <div class="card reveal reveal--from-right">
          <h3 style="margin-top:0;">Mission & Vision</h3>
          <p style="margin:0 0 10px;"><b>Mission:</b> Deliver innovative and cost-effective IT solutions that empower organizations to grow securely.</p>
          <p style="margin:0 0 10px;"><b>Vision:</b> Be recognized as a trusted technology partner—driven by integrity, innovation, and customer-centric delivery.</p>

          <div class="grid-2" style="margin-top:14px;">
            <div class="card" style="box-shadow:none; padding:16px; border-radius:16px;">
              <h3 style="margin:0 0 6px; font-size:16px;">Core values</h3>
              <p>Integrity • Excellence • Innovation • Responsibility • Continuous learning</p>
            </div>
            <div class="card" style="box-shadow:none; padding:16px; border-radius:16px;">
              <h3 style="margin:0 0 6px; font-size:16px;">What clients get</h3>
              <p>Clear scope • Secure handling • Traceable reporting • Responsive support</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- SERVICES -->
  <section id="services" class="section">
    <div class="container">
      <div class="section-title reveal reveal--from-bottom">
        <div class="underline"></div>
        <h2>What We Do</h2>
        <p>Choose a category to preview our focus areas.</p>
      </div>

      <div class="tabs reveal reveal--pop" role="tablist" aria-label="Service categories">
        <button class="tab active" data-tab="tab-data" role="tab" aria-selected="true">Secure Data Destruction</button>
        <button class="tab" data-tab="tab-assets" role="tab" aria-selected="false">IT Assets Handling</button>
        <button class="tab" data-tab="tab-compliance" role="tab" aria-selected="false">Compliance & Reporting</button>
        <button class="tab" data-tab="tab-cyber" role="tab" aria-selected="false">Cybersecurity</button>
        <button class="tab" data-tab="tab-commerce" role="tab" aria-selected="false">E-Commerce & Hardware</button>
      </div>

      <div id="tab-data" class="tab-panel active" role="tabpanel">
        <div class="grid-3" style="margin-top:14px;">
          <div class="card reveal reveal--from-bottom"><div class="icon">🛡</div><h3>Certified sanitization</h3><p>Secure destruction with traceable handling and documentation aligned to recognized practices.</p></div>
          <div class="card reveal reveal--from-bottom"><div class="icon">🧾</div><h3>Proof & reporting</h3><p>Structured reporting per batch/device to support audits and internal governance.</p></div>
          <div class="card reveal reveal--from-bottom"><div class="icon">♻</div><h3>Responsible disposition</h3><p>Support for reuse and recycling goals where applicable—reducing e-waste while staying compliant.</p></div>
        </div>
      </div>

      <div id="tab-assets" class="tab-panel" role="tabpanel">
        <div class="grid-3" style="margin-top:14px;">
          <div class="card reveal reveal--from-bottom"><div class="icon">🚚</div><h3>Secure collection</h3><p>Coordinated pickup and secure logistics for data-bearing assets.</p></div>
          <div class="card reveal reveal--from-bottom"><div class="icon">🏷</div><h3>Inventory & tracking</h3><p>Asset identification, labeling, and structured tracking to reduce operational risk.</p></div>
          <div class="card reveal reveal--from-bottom"><div class="icon">🧰</div><h3>Evaluation & routing</h3><p>Assess and route assets for reuse, remarketing, or certified destruction.</p></div>
        </div>
      </div>

      <div id="tab-compliance" class="tab-panel" role="tabpanel">
        <div class="grid-3" style="margin-top:14px;">
          <div class="card reveal reveal--from-bottom"><div class="icon">📘</div><h3>Audit-ready docs</h3><p>Reporting structured to support reviews, governance, and customer assurance.</p></div>
          <div class="card reveal reveal--from-bottom"><div class="icon">✅</div><h3>Policy alignment</h3><p>Guidance aligned to widely used practices for information security and data handling.</p></div>
          <div class="card reveal reveal--from-bottom"><div class="icon">🔍</div><h3>Transparency</h3><p>Clear chain-of-custody and deliverables to improve accountability.</p></div>
        </div>
      </div>

      <div id="tab-cyber" class="tab-panel" role="tabpanel">
        <div class="grid-3" style="margin-top:14px;">
          <div class="card reveal reveal--from-bottom"><div class="icon">🧪</div><h3>Pen testing</h3><p>Assess web apps, APIs, networks, and cloud configurations to identify vulnerabilities.</p></div>
          <div class="card reveal reveal--from-bottom"><div class="icon">🧩</div><h3>App & API security</h3><p>Improvements for auth, access control, and data protection—built for real operations.</p></div>
          <div class="card reveal reveal--from-bottom"><div class="icon">🎯</div><h3>Readiness</h3><p>Support with best practices, hardening, and security awareness.</p></div>
        </div>
      </div>

      <div id="tab-commerce" class="tab-panel" role="tabpanel">
        <div class="grid-3" style="margin-top:14px;">
          <div class="card reveal reveal--from-bottom"><div class="icon">💻</div><h3>Hardware supply</h3><p>Laptops, desktops, storage, and accessories—sourced and supported with operational experience.</p></div>
          <div class="card reveal reveal--from-bottom"><div class="icon">🛒</div><h3>E-commerce ops</h3><p>Online channels that make purchasing and service access convenient for customers.</p></div>
          <div class="card reveal reveal--from-bottom"><div class="icon">⚙</div><h3>Custom solutions</h3><p>Tailor-made IT and software solutions based on business needs and constraints.</p></div>
        </div>
      </div>
    </div>
  </section>

  <!-- WHY US -->
  <section id="why" class="section section-dark">
    <div class="container">
      <div class="section-title reveal reveal--from-bottom">
        <div class="underline"></div>
        <h2>Why Choose Us</h2>
        <p>Security-minded delivery with clear outcomes, documentation, and accountability.</p>
      </div>

      <div class="grid-3">
        <div class="card reveal reveal--pop">
          <div class="icon">🔐</div>
          <h3>Secure by default</h3>
          <p>Security embedded in every engagement—without slowing your operations.</p>
        </div>

        <div class="card reveal reveal--pop">
          <div class="icon">📦</div>
          <h3>End-to-end</h3>
          <p>One partner from asset intake to final reporting—one accountable flow.</p>
        </div>

        <div class="card reveal reveal--pop">
          <div class="icon">🤝</div>
          <h3>Transparent</h3>
          <p>Clear scope, reporting, and communication—built for trust and long-term relationships.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section id="faq" class="section">
    <div class="container">
      <div class="section-title reveal reveal--from-bottom">
        <div class="underline"></div>
        <h2>Frequently Asked Questions</h2>
        <p>Quick answers for common questions before engaging our team.</p>
      </div>

      <div class="faq">
        <div class="faq-item reveal reveal--from-bottom">
          <button class="faq-q" type="button">
            <span>Do you provide documentation and proof for completed work?</span>
            <span class="chev" aria-hidden="true">⌄</span>
          </button>
          <div class="faq-a">
            Yes—depending on the service, we provide structured reports, chain-of-custody records, and completion documentation suitable for internal review and audits.
          </div>
        </div>

        <div class="faq-item reveal reveal--from-bottom">
          <button class="faq-q" type="button">
            <span>Can you handle cybersecurity and data destruction in one engagement?</span>
            <span class="chev" aria-hidden="true">⌄</span>
          </button>
          <div class="faq-a">
            Yes. We can scope a combined plan that covers assessment, controls, and secure end-of-life handling for data-bearing assets.
          </div>
        </div>

        <div class="faq-item reveal reveal--from-bottom">
          <button class="faq-q" type="button">
            <span>Where are you based?</span>
            <span class="chev" aria-hidden="true">⌄</span>
          </button>
          <div class="faq-a">
            We are headquartered in Kuala Lumpur. Coverage depends on scope and logistics, and we support organizations across Malaysia with structured scheduling and secure handling processes.
          </div>
        </div>

        <div class="faq-item reveal reveal--from-bottom">
          <button class="faq-q" type="button">
            <span>Do you build custom software and websites?</span>
            <span class="chev" aria-hidden="true">⌄</span>
          </button>
          <div class="faq-a">
            Yes—tailored software solutions, websites, and application security work, especially when projects benefit from security-first design and compliance-aware delivery.
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CONTACT -->
  <section id="contact" class="section" style="padding-top:0;">
    <div class="container">
      <div class="cta reveal reveal--from-bottom" role="region" aria-label="Contact call-to-action">
        <div>
          <h3>Let’s build a secure, smoother operation—together.</h3>
          <p>
            Share your needs (data destruction, IT asset handling, cybersecurity, or software).
            We’ll propose a clear plan with scope, timeline, and deliverables.
          </p>
          <p style="margin-top:10px; color:rgba(255,255,255,.78);">
            Office: Lot #5.34, 5th floor, Imbi Plaza, 28 Jalan Imbi, 55100 Kuala Lumpur • Mon–Fri 11:00 AM–6:00 PM
          </p>
        </div>
        <div class="cta-actions">
          <a class="btn btn-solid" href="https://wa.me/60123162006" target="_blank" rel="noopener">WhatsApp Us →</a>
          <a class="btn" href="mailto:nrintellitech@gmail.com">Email</a>
        </div>
      </div>
    </div>
  </section>

</div>
@endsection

@push('scripts')
<script>
(function(){
  const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function getHeaderOffset(){
    const top = document.querySelector('.header-top');
    const bottom = document.querySelector('.header-bottom');
    const h = (top?.offsetHeight || 0) + (bottom?.offsetHeight || 0);
    return Math.max(120, h + 20);
  }

  const tabs = Array.from(document.querySelectorAll('.tab'));
  const panels = Array.from(document.querySelectorAll('.tab-panel'));
  if (tabs.length && panels.length){
    tabs.forEach(btn => {
      btn.addEventListener('click', () => {
        tabs.forEach(t => { t.classList.remove('active'); t.setAttribute('aria-selected','false'); });
        panels.forEach(p => p.classList.remove('active'));

        btn.classList.add('active');
        btn.setAttribute('aria-selected','true');
        const target = btn.dataset.tab;
        const panel = target ? document.getElementById(target) : null;
        if(panel) panel.classList.add('active');
      });
    });
  }

  document.querySelectorAll('.faq-item').forEach(item => {
    const btn = item.querySelector('.faq-q');
    if(!btn) return;
    btn.addEventListener('click', () => {
      document.querySelectorAll('.faq-item').forEach(i => { if(i !== item) i.classList.remove('open'); });
      item.classList.toggle('open');
    });
  });

  const reveals = Array.from(document.querySelectorAll('.reveal'));
  if (!prefersReduced && reveals.length){
    reveals.forEach((el, idx) => {
      el.style.setProperty('--d', (idx % 10) * 70 + 'ms');
    });

    const ro = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if(!entry.isIntersecting) return;
        entry.target.classList.add('in');
        ro.unobserve(entry.target);
      });
    }, { threshold: 0.18, rootMargin: "0px 0px -10% 0px" });

    reveals.forEach(el => ro.observe(el));
  } else {
    reveals.forEach(el => el.classList.add('in'));
  }

  function animateCounts(){
    const els = Array.from(document.querySelectorAll('.count'));
    if (!els.length) return;

    els.forEach(el => {
      const to = parseInt(el.dataset.to || "0", 10);
      const suffix = el.dataset.suffix || "";
      el.textContent = "0" + suffix;
      el.dataset.done = "0";
    });

    const duration = 900;

    function run(){
      els.forEach(el => {
        if (el.dataset.done === "1") return;

        const to = parseInt(el.dataset.to || "0", 10);
        const suffix = el.dataset.suffix || "";
        const start = performance.now();
        const from = 0;

        el.dataset.done = "1";

        function tick(now){
          const t = Math.min(1, (now - start) / duration);
          const eased = 1 - Math.pow(1 - t, 3); // easeOutCubic
          const val = Math.round(from + (to - from) * eased);
          el.textContent = val.toLocaleString() + suffix;
          if(t < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);

        setTimeout(() => {
          el.textContent = to.toLocaleString() + suffix;
        }, duration + 120);
      });
    }

    const card = document.getElementById('heroKpiCard');
    if (!card || prefersReduced){
      els.forEach(el => {
        const to = parseInt(el.dataset.to || "0", 10);
        const suffix = el.dataset.suffix || "";
        el.textContent = to.toLocaleString() + suffix;
      });
      return;
    }

    const io = new IntersectionObserver((entries) => {
      const entry = entries[0];
      if(entry && entry.isIntersecting){
        run();
        io.disconnect();
      }
    }, { threshold: 0.35 });

    io.observe(card);

    requestAnimationFrame(() => {
      const rect = card.getBoundingClientRect();
      const inView = rect.top < window.innerHeight && rect.bottom > 0;
      if(inView) run();
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", animateCounts, { once:true });
  } else {
    animateCounts();
  }

  function setHeaderHeightVar(){
    const top = document.querySelector('.header-top');
    const bottom = document.querySelector('.header-bottom');
    const h = (top?.offsetHeight || 0) + (bottom?.offsetHeight || 0);
    document.documentElement.style.setProperty('--site-header-h', h + 'px');
  }

  window.addEventListener('load', setHeaderHeightVar);
  window.addEventListener('resize', setHeaderHeightVar);
  setHeaderHeightVar();

  const text = "Secure. Reliable. Built for long-term growth.";
  const el = document.getElementById("typeText");
  if(!el) return;

  const typeSpeed = 45;
  const holdAfterType = 2000;

  function typeOnce(){
    el.textContent = "";
    let i = 0;

    function type(){
      if(i < text.length){
        el.textContent += text.charAt(i);
        i++;
        setTimeout(type, typeSpeed);
      } else {
        setTimeout(typeOnce, holdAfterType);
      }
    }

    type();
  }

  if(document.readyState === "loading"){
    document.addEventListener("DOMContentLoaded", typeOnce, { once:true });
  } else {
    typeOnce();
  }

})();
</script>
@endpush

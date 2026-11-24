<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400;1,500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<title>The Family Gets Hit Too</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

:root {
  --gold:#C9A24A;
  --gold2:#A8823A;
  --gold-light:#E8D5A3;
  --ink:#1F2023;
  --ink-70:rgba(31,32,35,0.70);
  --ink-45:rgba(31,32,35,0.45);
  --cream:#F7F2EA;
  --serif:'Cormorant Garamond',Georgia,serif;
  --sans:'DM Sans',system-ui,sans-serif;
  --ease:0.48s cubic-bezier(0.25,0.46,0.45,0.94);
}

body{font-family:var(--sans);background:#EDEAE3;-webkit-font-smoothing:antialiased;}

.section {
  position:relative;
  overflow:hidden;
  background:linear-gradient(135deg,#EBEBEB 0%,#F0EAE0 45%,#E5DAC9 100%);
}

.bg-grid{position:absolute;inset:0;pointer-events:none;
  background-image:linear-gradient(rgba(31,32,35,.022) 1px,transparent 1px),
    linear-gradient(90deg,rgba(31,32,35,.022) 1px,transparent 1px);
  background-size:72px 72px;}
.bg-glow-l{position:absolute;top:-80px;left:-80px;width:500px;height:500px;
  background:radial-gradient(circle,rgba(201,162,74,.13) 0%,transparent 65%);pointer-events:none;}
.bg-glow-r{position:absolute;bottom:-60px;right:-60px;width:560px;height:560px;
  background:radial-gradient(circle,rgba(31,32,35,.07) 0%,transparent 65%);pointer-events:none;}

.split{
  position:relative;z-index:10;
  display:grid;
  grid-template-columns:1fr 1fr;
  min-height:640px;
}

.panel-image{
  position:relative;
  overflow:hidden;
}
.panel-image img{
  width:100%;height:100%;
  object-fit:cover;
  display:block;
  transition:transform 0.9s cubic-bezier(0.25,0.46,0.45,0.94);
}
.panel-image:hover img{transform:scale(1.04);}

/* Gold gradient fade on right edge — blends image into content panel */
.panel-image::after{
  content:'';
  position:absolute;inset:0;
  background:linear-gradient(90deg,
    transparent 0%,
    transparent 55%,
    rgba(235,228,215,0.55) 78%,
    rgba(235,228,215,0.92) 93%,
    #EBE4D7 100%);
  pointer-events:none;
}

/* Vertical gold rule between panels */
.panel-image::before{
  content:'';
  position:absolute;top:10%;bottom:10%;right:0;
  width:1px;
  background:linear-gradient(180deg,transparent,rgba(201,162,74,.45),transparent);
  z-index:2;
}

.img-caption{
  position:absolute;bottom:32px;left:32px;z-index:3;
  display:flex;align-items:center;gap:10px;
}
.img-caption-dot{
  width:8px;height:8px;border-radius:50%;
  background:var(--gold);
  box-shadow:0 0 0 3px rgba(201,162,74,.25);
  flex-shrink:0;
}
.img-caption-text{
  font-family:var(--sans);
  font-size:11px;font-weight:500;
  letter-spacing:0.18em;text-transform:uppercase;
  color:rgba(255,255,255,0.82);
  text-shadow:0 1px 8px rgba(0,0,0,.45);
}

.panel-content{
  position:relative;
  padding:80px 72px 80px 64px;
  display:flex;flex-direction:column;justify-content:center;
  background:linear-gradient(135deg,#EBEBEB 0%,#EDE7DC 100%);
}

.deco-numeral{
  position:absolute;top:-20px;right:20px;
  font-family:var(--serif);
  font-size:260px;font-weight:300;line-height:1;
  color:rgba(201,162,74,.07);
  pointer-events:none;user-select:none;
  letter-spacing:-0.06em;
}

.eyebrow{
  display:inline-flex;align-items:center;gap:12px;
  font-family:var(--sans);
  font-size:10px;font-weight:500;
  letter-spacing:0.28em;text-transform:uppercase;
  color:var(--gold2);
  margin-bottom:22px;
}
.eyebrow::before{
  content:'';display:block;width:28px;height:1px;
  background:linear-gradient(90deg,transparent,var(--gold));
}

.section-title{
  font-family:var(--serif);
  font-size:clamp(36px,3.8vw,54px);
  font-weight:400;line-height:1.1;
  letter-spacing:-0.01em;
  color:var(--ink);
  margin-bottom:10px;
}
.section-title em{
  font-style:italic;color:var(--gold2);
}

.title-rule{
  width:48px;height:2px;
  background:linear-gradient(90deg,var(--gold),transparent);
  margin:22px 0 30px;
  border-radius:1px;
}

.lead-text{
  font-family:var(--sans);
  font-size:15.5px;font-weight:300;
  line-height:1.75;
  color:var(--ink-70);
  max-width:420px;
  margin-bottom:44px;
}

.impact-list{
  display:flex;flex-direction:column;gap:0;
  margin-bottom:52px;
}

.impact-item{
  display:grid;
  grid-template-columns:48px 1fr;
  gap:0 20px;
  align-items:stretch;
  padding:20px 0;
  border-bottom:1px solid rgba(201,162,74,.14);
  cursor:default;
  transition:var(--ease);
}
.impact-item:first-child{
  border-top:1px solid rgba(201,162,74,.14);
}
.impact-item:hover{
  padding-left:6px;
}

.impact-num{
  font-family:var(--serif);
  font-size:13px;font-weight:500;
  color:var(--gold);
  letter-spacing:0.05em;
  padding-top:2px;
  opacity:0.70;
}

.impact-title{
  font-family:var(--serif);
  font-size:19px;font-weight:500;
  color:var(--ink);
  margin-bottom:4px;
  line-height:1.2;
  transition:color var(--ease);
}
.impact-item:hover .impact-title{color:var(--gold2);}
.impact-desc{
  font-family:var(--sans);
  font-size:13px;font-weight:300;
  color:var(--ink-45);
  line-height:1.65;
}

.cta-row{
  display:flex;align-items:center;gap:20px;
}
.cta-btn{
  display:inline-flex;align-items:center;gap:10px;
  font-family:var(--sans);
  font-size:12px;font-weight:500;
  letter-spacing:0.14em;text-transform:uppercase;
  color:var(--ink);
  text-decoration:none;
  padding:14px 28px;
  border:1px solid rgba(201,162,74,.55);
  background:rgba(255,255,255,.55);
  backdrop-filter:blur(8px);
  border-radius:2px;
  transition:var(--ease);
  cursor:pointer;
}
.cta-btn:hover{
  background:rgba(201,162,74,.12);
  border-color:var(--gold);
  color:var(--gold2);
}
.cta-arrow{
  width:18px;height:1px;
  background:currentColor;
  position:relative;
  transition:width var(--ease);
}
.cta-arrow::after{
  content:'';position:absolute;right:-1px;top:-3px;
  width:6px;height:6px;
  border-top:1px solid currentColor;
  border-right:1px solid currentColor;
  transform:rotate(45deg);
}
.cta-btn:hover .cta-arrow{width:26px;}

.cta-secondary{
  font-family:var(--sans);
  font-size:12px;font-weight:400;
  letter-spacing:0.06em;
  color:var(--ink-45);
  text-decoration:none;
  border-bottom:1px solid rgba(201,162,74,.30);
  padding-bottom:2px;
  transition:color var(--ease),border-color var(--ease);
  cursor:pointer;
}
.cta-secondary:hover{color:var(--gold2);border-color:var(--gold);}

@keyframes fadeUp{
  from{opacity:0;transform:translateY(24px)}
  to{opacity:1;transform:translateY(0)}
}
.anim{opacity:0;animation:fadeUp 0.72s cubic-bezier(0.25,0.46,0.45,0.94) forwards;}
.d1{animation-delay:.05s}.d2{animation-delay:.18s}.d3{animation-delay:.28s}
.d4{animation-delay:.38s}.d5{animation-delay:.48s}.d6{animation-delay:.58s}
.d7{animation-delay:.68s}.d8{animation-delay:.78s}

@media(max-width:900px){
  .split{grid-template-columns:1fr;min-height:auto;}
  .panel-image{height:420px;}
  .panel-image::after{
    background:linear-gradient(180deg,transparent 0%,transparent 55%,
      rgba(235,228,215,.65) 80%,rgba(235,228,215,.95) 95%,#EBE4D7 100%);
  }
  .panel-image::before{display:none;}
  .panel-content{padding:56px 36px;}
  .deco-numeral{font-size:160px;}
}
@media(max-width:520px){
  .panel-content{padding:44px 24px;}
  .cta-row{flex-direction:column;align-items:flex-start;}
}
</style>
</head>
<body>

<section class="section" id="family">
  <div class="bg-grid"></div>
  <div class="bg-glow-l"></div>
  <div class="bg-glow-r"></div>

  <div class="split">

    <!-- IMAGE PANEL -->
    <div class="panel-image anim d1">
      <img src="images/supp.jpg" alt="Family navigating crisis together">
      <div class="img-caption">
        <div class="img-caption-dot"></div>
        <span class="img-caption-text">Family Impact</span>
      </div>
    </div>

    <!-- CONTENT PANEL -->
    <div class="panel-content">
      <div class="deco-numeral">II</div>

      <p class="eyebrow anim d2">The Ripple Effect</p>

      <h2 class="section-title anim d3">
        The Family<br><em>Gets Hit Too</em>
      </h2>

      <div class="title-rule anim d4"></div>

      <p class="lead-text anim d5">
        When one person faces a legal crisis, the entire household absorbs the impact. Stress radiates outward — through relationships, routines, and finances.
      </p>

      <div class="impact-list">

        <div class="impact-item anim d5">
          <span class="impact-num">01</span>
          <div class="impact-right">
            <p class="impact-title">Shock & Disrupted Rest</p>
            <p class="impact-desc">Anxiety, sleep disruption, and emotional shock that ripple through every member of the household.</p>
          </div>
        </div>

        <div class="impact-item anim d6">
          <span class="impact-num">02</span>
          <div class="impact-right">
            <p class="impact-title">Role & Responsibility Shifts</p>
            <p class="impact-desc">Sudden redistribution of financial duties, parenting, and household management without preparation.</p>
          </div>
        </div>

        <div class="impact-item anim d7">
          <span class="impact-num">03</span>
          <div class="impact-right">
            <p class="impact-title">Trauma-Like Stress Responses</p>
            <p class="impact-desc">Loved ones often experience secondary trauma — fear, grief, and helplessness that requires its own support.</p>
          </div>
        </div>

      </div>

      <div class="cta-row anim d8">
        <a class="cta-btn">
          Family Support Services
          <span class="cta-arrow"></span>
        </a>
        <a class="cta-secondary">Learn how we help</a>
      </div>

    </div>
  </div>
</section>

</body>
</html>
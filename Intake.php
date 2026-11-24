<?php

$appointmentId = $_GET["appointmentId"] ?? '';

function loadEnvFile() {
    $envPath = __DIR__ . '/.env';

    if (!file_exists($envPath)) {
        $envPath = __DIR__ . '/../.env';
    }

    if (!file_exists($envPath)) {
        throw new Exception('.env file not found.');
    }

    $envLines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($envLines as $line) {
        $line = trim($line);

        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }

        [$key, $value] = array_pad(explode('=', $line, 2), 2, null);

        if ($key && $value !== null) {
            $_ENV[trim($key)] = trim($value);
        }
    }
}

function createPDOConnection() {
    loadEnvFile();

    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $db   = $_ENV['DB_NAME'] ?? '';
    $user = $_ENV['DB_USERNAME'] ?? '';
    $pass = $_ENV['DB_PASSWORD'] ?? '';

    if (!$db || !$user) {
        throw new Exception('Database credentials are missing.');
    }

    $dsn = "mysql:host={$host};dbname={$db};charset=utf8mb4";

    return new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}

$showForm = true;
$alreadySubmitted = false;
$invalidLink = false;
$intakeRecord = null;

try {
    if (empty($appointmentId)) {
        $showForm = false;
        $invalidLink = true;
    } else {
        $pdo = createPDOConnection();

        $stmt = $pdo->prepare("
            SELECT 
                id_intake,
                appointment_id,
                intake
            FROM intake
            WHERE appointment_id = :appointment_id
            LIMIT 1
        ");

        $stmt->execute([
            ':appointment_id' => $appointmentId
        ]);

        $intakeRecord = $stmt->fetch();

        if (!$intakeRecord) {
            $showForm = false;
            $invalidLink = true;
        } elseif ((int) $intakeRecord['intake'] === 1) {
            $showForm = false;
            $alreadySubmitted = true;
        }
    }
} catch (Exception $e) {
    $showForm = false;
    $invalidLink = true;

    error_log('Intake form check failed: ' . $e->getMessage());
}

?>
<!DOCTYPE html>

<html lang="en">

<head>

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Thank you for booking a discovery call with The PHELONPRENEUR Network. We'll be in touch within one business day." />
  <title>Thank You - The PHELONPRENEUR Network</title>

  <!-- Preconnect for Performance -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" type="image/x-icon" href="images/favicon.ico" />

  <!-- Tailwind CDN -->
  <script src="js/tailwind-cdn.js"></script>
  <script src="js/tailwind-config.js"></script>
<!-- Add before </head> -->
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>

<style>
  .survey-card {
    background: rgba(255,255,255,.72);
    border: 1px solid rgba(201,162,74,.22);
    border-radius: 22px;
    box-shadow: 0 18px 50px rgba(0,0,0,.08);
    backdrop-filter: blur(10px);
  }

  .survey-step { display: none; }
  .survey-step.active { display: block; }

  .survey-input {
    width: 100%;
    border: 1px solid rgba(201,162,74,.28);
    border-radius: 12px;
    padding: 13px 14px;
    background: rgba(255,255,255,.85);
    outline: none;
  }

  .survey-input:focus {
    border-color: #c9a24a;
    box-shadow: 0 0 0 3px rgba(201,162,74,.15);
  }

  .survey-label {
    display: block;
    font-weight: 700;
    color: #1f1a14;
    margin-bottom: 7px;
    font-size: .92rem;
  }

  .choice-box {
    display: flex;
    gap: 10px;
    align-items: flex-start;
    padding: 12px 14px;
    border: 1px solid rgba(201,162,74,.22);
    border-radius: 12px;
    background: rgba(255,255,255,.55);
    cursor: pointer;
  }

  .progress-wrap {
    height: 8px;
    background: rgba(201,162,74,.18);
    border-radius: 999px;
    overflow: hidden;
  }

  .progress-bar {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg,#c9a24a,#e6c98b);
    transition: width .3s ease;
  }

  .survey-btn {
    border-radius: 999px;
    padding: 13px 24px;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .survey-btn-primary {
    background: linear-gradient(135deg,#c9a24a,#e6c98b);
    color: #fff;
  }

  .survey-btn-secondary {
    border: 1px solid rgba(201,162,74,.35);
    color: #1f1a14;
    background: rgba(255,255,255,.65);
  }
</style>

</head>

<body class="font-inter text-ink">

<?php include 'nav.php'; ?>
<main>
  <?php if ($alreadySubmitted): ?>

<section class="section-pad">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
    <div class="card-exec p-8 sm:p-10 md:p-12">

      <div style="width:88px;height:88px;border-radius:50%;background:linear-gradient(135deg,#c9a24a,#e6c98b);display:flex;align-items:center;justify-content:center;margin:0 auto 1.75rem;box-shadow:0 12px 40px rgba(201,162,74,.35);">
        <svg viewBox="0 0 24 24" style="width:42px;height:42px;stroke:#fff;stroke-width:2.5;fill:none;stroke-linecap:round;stroke-linejoin:round;">
          <polyline points="4,12 9,17 20,6"></polyline>
        </svg>
      </div>

      <p class="h-eyebrow mb-3 active" style="color:#c9a24a;letter-spacing:.22em;text-transform:uppercase;font-weight:700;">
        Intake Already Submitted
      </p>

      <h1 class="h-title text-3xl sm:text-4xl mb-4">
        Your Intake Form Has Already Been Completed.
      </h1>

      <p class="text-muted-ink leading-relaxed max-w-xl mx-auto mb-6">
        We already received your intake form for this appointment/service request.
        Retaking or resubmitting this form is not allowed so we can keep your case review accurate and avoid duplicate records.
      </p>

      <p class="text-muted-ink leading-relaxed max-w-xl mx-auto mb-8">
        Our team will review your submitted information and follow up if anything else is needed.
      </p>

      <a href="index" class="btn btn-primary">
        <span>Back To Home</span>
      </a>

    </div>
  </div>
</section>

<?php elseif ($invalidLink): ?>

<section class="section-pad">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
    <div class="card-exec p-8 sm:p-10 md:p-12">

      <p class="h-eyebrow mb-3 active" style="color:#c9a24a;letter-spacing:.22em;text-transform:uppercase;font-weight:700;">
        Invalid Intake Link
      </p>

      <h1 class="h-title text-3xl sm:text-4xl mb-4">
        We Could Not Verify This Intake Form Link.
      </h1>

      <p class="text-muted-ink leading-relaxed max-w-xl mx-auto mb-8">
        This link is missing or does not match an active appointment/service request.
        Please use the intake form link sent to your email.
      </p>

      <a href="index" class="btn btn-primary">
        <span>Back To Home</span>
      </a>

    </div>
  </div>
</section>

<?php else: ?>
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --gold:#c9a24a;--gold-light:#e6c98b;--gold-dim:rgba(201,162,74,.18);--gold-border:rgba(201,162,74,.28);
  --ink:#1a1410;--ink-mid:#3d3220;--ink-muted:#6b5c40;--ink-soft:#9c8a6e;
  --cream:#fdf8f0;--cream2:#f7f0e3;--surface:rgba(255,255,255,.72);
  --card-bg:rgba(255,255,255,.82);--dot-color:rgba(201,162,74,.12);
}

.intake-wrap{max-width:900px;margin:0 auto;padding:2rem 1rem 4rem}

.intake-eyebrow{display:flex;align-items:center;gap:10px;margin-bottom:1.5rem;justify-content:center}
.intake-eyebrow-line{height:1px;width:40px;background:var(--gold)}
.intake-eyebrow-text{font-size:.7rem;font-weight:800;letter-spacing:.2em;color:var(--gold);text-transform:uppercase}
.intake-hero-title{font-family:'Playfair Display',serif;font-size:clamp(1.8rem,4vw,3rem);font-weight:700;text-align:center;color:var(--ink);line-height:1.2;margin-bottom:.75rem}
.intake-hero-title span{color:var(--gold)}
.intake-hero-sub{text-align:center;color:var(--ink-muted);font-size:.875rem;max-width:560px;margin:0 auto 2.5rem;line-height:1.7;font-weight:500}

.step-rail{display:flex;align-items:flex-start;gap:0;margin-bottom:2.5rem;position:relative;overflow-x:auto;padding-bottom:4px}
.step-rail-item{display:flex;flex-direction:column;align-items:center;flex:1;min-width:56px;position:relative;cursor:default}
.step-rail-item::before{content:'';position:absolute;top:16px;left:50%;right:-50%;height:2px;background:var(--gold-dim);z-index:0}
.step-rail-item:last-child::before{display:none}
.step-dot{width:32px;height:32px;border-radius:50%;border:2px solid var(--gold-border);background:var(--cream2);display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:800;color:var(--ink-soft);z-index:1;position:relative;transition:all .3s ease}
.step-dot.active{background:var(--gold);border-color:var(--gold);color:#fff;box-shadow:0 0 0 4px rgba(201,162,74,.2)}
.step-dot.done{background:var(--ink);border-color:var(--ink);color:#fff}
.step-dot.done::after{content:'✓';font-size:.65rem}
.step-dot.done span{display:none}
.step-label{font-size:.55rem;font-weight:700;letter-spacing:.04em;color:var(--ink-soft);margin-top:6px;text-align:center;text-transform:uppercase;max-width:60px;line-height:1.3}
.step-label.active{color:var(--gold)}
.step-label.done{color:var(--ink-mid)}

.form-card{background:var(--card-bg);border:1px solid var(--gold-border);border-radius:24px;box-shadow:0 24px 64px rgba(0,0,0,.08),0 1px 0 rgba(255,255,255,.9) inset;backdrop-filter:blur(12px);overflow:hidden}
.form-card-header{background:linear-gradient(135deg,var(--ink) 0%,var(--ink-mid) 100%);padding:1.75rem 2rem;position:relative;overflow:hidden}
.form-card-header::before{content:'';position:absolute;inset:0;background-image:radial-gradient(circle,var(--dot-color) 1px,transparent 1px);background-size:18px 18px;opacity:.6}
.form-card-header::after{content:'';position:absolute;top:-40px;right:-40px;width:180px;height:180px;border-radius:50%;background:rgba(201,162,74,.07)}
.form-card-header-inner{position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap}
.step-title-wrap h2{font-family:'Playfair Display',serif;font-size:1.4rem;color:#fff;font-weight:700;margin-bottom:2px}
.step-title-wrap p{font-size:.75rem;color:rgba(255,255,255,.55);font-weight:500}
.step-counter-badge{background:rgba(201,162,74,.18);border:1px solid rgba(201,162,74,.35);border-radius:999px;padding:.35rem .9rem;display:flex;align-items:center;gap:.5rem;white-space:nowrap}
.step-counter-badge .num{font-size:1.1rem;font-weight:900;color:var(--gold)}
.step-counter-badge .of{font-size:.7rem;color:rgba(255,255,255,.45);font-weight:600}

.prog-bar-wrap{height:4px;background:rgba(201,162,74,.15)}
.prog-bar-fill{height:4px;background:linear-gradient(90deg,var(--gold),var(--gold-light));transition:width .4s cubic-bezier(.4,0,.2,1)}

.form-body{padding:2rem}

.field-group{margin-bottom:1.5rem}
.field-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem}
.field-grid-3{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1rem}
.field-span-2{grid-column:1/-1}

.f-label{display:block;font-size:.7rem;font-weight:800;letter-spacing:.08em;color:var(--ink-mid);text-transform:uppercase;margin-bottom:.45rem}
.f-required::after{content:' *';color:var(--gold)}

.f-input,.f-select,.f-textarea{width:100%;border:1.5px solid rgba(201,162,74,.22);border-radius:10px;padding:.75rem 1rem;background:rgba(255,255,255,.85);font-family:'Montserrat',sans-serif;font-size:.875rem;color:var(--ink);font-weight:500;outline:none;transition:border .2s,box-shadow .2s;appearance:none}
.f-input:focus,.f-select:focus,.f-textarea:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(201,162,74,.12)}
.f-textarea{resize:vertical;min-height:100px;line-height:1.6}
.f-select{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M2 4l4 4 4-4' stroke='%23c9a24a' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 14px center;padding-right:2.5rem}

.rating-group{display:flex;gap:6px;flex-wrap:wrap}
.rating-btn{width:40px;height:40px;border-radius:50%;border:1.5px solid var(--gold-border);background:transparent;font-size:.8rem;font-weight:800;color:var(--ink-soft);cursor:pointer;transition:all .18s;display:flex;align-items:center;justify-content:center}
.rating-btn:hover{border-color:var(--gold);color:var(--gold)}
.rating-btn.sel{background:var(--gold);border-color:var(--gold);color:#fff}

.choices-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:.6rem}
.choice-item{display:flex;align-items:flex-start;gap:.65rem;padding:.75rem 1rem;border:1.5px solid rgba(201,162,74,.2);border-radius:12px;background:rgba(255,255,255,.55);cursor:pointer;transition:border .18s,background .18s;font-size:.8rem;font-weight:600;color:var(--ink-mid);line-height:1.4}
.choice-item:hover{border-color:var(--gold);background:rgba(201,162,74,.06)}
.choice-item input{position:absolute;opacity:0;pointer-events:none}
.choice-item.checked{border-color:var(--gold);background:rgba(201,162,74,.1);color:var(--ink)}
.choice-dot{width:16px;height:16px;border:2px solid var(--gold-border);border-radius:4px;flex-shrink:0;margin-top:1px;display:flex;align-items:center;justify-content:center;transition:all .18s}
.choice-item.checked .choice-dot{background:var(--gold);border-color:var(--gold)}
.choice-item.checked .choice-dot::after{content:'';display:block;width:6px;height:4px;border-left:2px solid #fff;border-bottom:2px solid #fff;transform:rotate(-45deg) translateY(-1px)}

.consent-list{display:flex;flex-direction:column;gap:.6rem}
.consent-item{display:flex;align-items:flex-start;gap:.75rem;padding:.9rem 1.1rem;border:1.5px solid rgba(201,162,74,.18);border-radius:12px;background:rgba(255,255,255,.5);cursor:pointer;transition:border .18s,background .18s;font-size:.8rem;font-weight:500;color:var(--ink-mid);line-height:1.55}
.consent-item:hover{border-color:var(--gold);background:rgba(201,162,74,.04)}
.consent-item input{position:absolute;opacity:0;pointer-events:none}
.consent-item.checked{border-color:var(--gold);background:rgba(201,162,74,.07);color:var(--ink)}
.consent-check-box{width:20px;height:20px;border:2px solid var(--gold-border);border-radius:6px;flex-shrink:0;margin-top:1px;display:flex;align-items:center;justify-content:center;transition:all .18s}
.consent-item.checked .consent-check-box{background:var(--gold);border-color:var(--gold)}
.consent-item.checked .consent-check-box::after{content:'';display:block;width:8px;height:5px;border-left:2px solid #fff;border-bottom:2px solid #fff;transform:rotate(-45deg) translateY(-1px)}

.section-divider{display:flex;align-items:center;gap:12px;margin:1.5rem 0}
.section-divider-line{flex:1;height:1px;background:var(--gold-dim)}
.section-divider-label{font-size:.65rem;font-weight:800;letter-spacing:.12em;color:var(--gold);text-transform:uppercase}

.notice-box{background:rgba(201,162,74,.08);border:1px solid rgba(201,162,74,.25);border-radius:12px;padding:1rem 1.2rem;font-size:.8rem;color:var(--ink-muted);line-height:1.65;font-weight:500}
.notice-box strong{color:var(--ink-mid)}

.cond-panel{background:rgba(255,255,255,.65);border:1px solid rgba(201,162,74,.2);border-radius:14px;padding:1.5rem;margin-bottom:1rem}
.cond-panel-title{font-family:'Playfair Display',serif;font-size:1rem;color:var(--ink);margin-bottom:1rem;padding-bottom:.6rem;border-bottom:1px solid var(--gold-dim)}

.disclaimer-box{background:rgba(255,255,255,.6);border:1px solid rgba(201,162,74,.2);border-radius:14px;padding:1.25rem;max-height:200px;overflow-y:auto;font-size:.775rem;color:var(--ink-muted);line-height:1.8;margin-bottom:1.5rem;font-weight:500}
.disclaimer-box::-webkit-scrollbar{width:4px}
.disclaimer-box::-webkit-scrollbar-track{background:transparent}
.disclaimer-box::-webkit-scrollbar-thumb{background:var(--gold-border);border-radius:99px}

.form-nav{display:flex;justify-content:space-between;align-items:center;gap:1rem;margin-top:2rem;padding-top:1.5rem;border-top:1px solid var(--gold-dim)}
.btn{border-radius:999px;padding:.75rem 2rem;font-family:'Montserrat',sans-serif;font-size:.8rem;font-weight:800;letter-spacing:.06em;cursor:pointer;transition:all .18s;text-transform:uppercase;border:none}
.btn-primary{background:linear-gradient(135deg,var(--ink) 0%,var(--ink-mid) 100%);color:#fff;position:relative;overflow:hidden}
.btn-primary::after{content:'';position:absolute;inset:0;background:linear-gradient(135deg,var(--gold) 0%,var(--gold-light) 100%);opacity:0;transition:opacity .25s}
.btn-primary:hover::after{opacity:1}
.btn-primary span{position:relative;z-index:1}
.btn-ghost{background:transparent;border:1.5px solid var(--gold-border);color:var(--ink-mid)}
.btn-ghost:hover{border-color:var(--gold);color:var(--gold)}
.btn-submit{background:linear-gradient(135deg,var(--gold) 0%,var(--gold-light) 100%);color:var(--ink);padding:.75rem 2.5rem}
.btn-submit:hover{filter:brightness(1.06)}
.btn.hidden{display:none!important}

.survey-step{display:none;animation:stepIn .3s ease both}
.survey-step.active{display:block}
@keyframes stepIn{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}

.toast{position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(20px);background:var(--ink);color:#fff;padding:.65rem 1.4rem;border-radius:999px;font-size:.78rem;font-weight:700;letter-spacing:.04em;opacity:0;transition:all .3s;z-index:99;pointer-events:none;border:1px solid rgba(201,162,74,.3)}
.toast.show{opacity:1;transform:translateX(-50%) translateY(0)}

.rating-card{background:rgba(255,255,255,.55);border:1.5px solid rgba(201,162,74,.18);border-radius:14px;padding:1.1rem 1.25rem}
.rating-card-label{font-size:.7rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:var(--ink-mid);margin-bottom:.75rem}

.upload-area{border:2px dashed rgba(201,162,74,.3);border-radius:14px;padding:2rem;text-align:center;background:rgba(255,255,255,.4);cursor:pointer;transition:border .2s,background .2s;display:block}
.upload-area:hover{border-color:var(--gold);background:rgba(201,162,74,.05)}
.upload-icon{font-size:2rem;margin-bottom:.5rem;opacity:.5}
.upload-text{font-size:.8rem;color:var(--ink-muted);font-weight:600}
.upload-text strong{color:var(--gold)}
.upload-area input[type=file]{display:none}

/* SweetAlert2 review modal overrides */
.swal-phelon-review{border-radius:20px!important;border:1px solid rgba(201,162,74,.3)!important;font-family:'Montserrat',sans-serif!important}
.swal-phelon-review .swal2-title{font-family:'Playfair Display',serif!important;padding-bottom:.5rem!important;border-bottom:1px solid rgba(201,162,74,.2)!important}
.swal-phelon-review .swal2-html-container{margin:1rem 0 0!important;padding:0!important}
.swal-phelon-review .swal2-actions{padding-top:1rem!important;border-top:1px solid rgba(201,162,74,.15)!important;margin-top:.5rem!important;gap:.75rem!important}
.swal-btn-confirm{font-family:'Montserrat',sans-serif!important;font-weight:800!important;font-size:.78rem!important;letter-spacing:.06em!important;border-radius:999px!important;padding:.7rem 1.6rem!important}
.swal-btn-cancel{font-family:'Montserrat',sans-serif!important;font-weight:700!important;font-size:.78rem!important;letter-spacing:.04em!important;border-radius:999px!important;padding:.7rem 1.4rem!important}

@media(max-width:600px){
  .form-body{padding:1.25rem}
  .form-card-header{padding:1.25rem}
  .field-grid,.field-grid-3,.choices-grid{grid-template-columns:1fr}
  .step-label{display:none}
  .intake-wrap{padding:1rem .75rem 3rem}
}
</style>

  <section class='section-pad'>
    <div class='intake-wrap'>

      <div class='intake-eyebrow'>
        <div class='intake-eyebrow-line'></div>
        <span class='intake-eyebrow-text'>Readiness Consultation</span>
        <div class='intake-eyebrow-line'></div>
      </div>
      <h1 class='intake-hero-title'>Readiness Consultation<br><span>Intake Form</span></h1>
      <p class='intake-hero-sub'>Please complete this form before your consultation. Do not include confidential legal strategy, privileged attorney-client communication, protected health information, or sensitive case details.</p>

      <!-- Step Rail -->
      <div class='step-rail' id='stepRail'></div>

      <div class='form-card'>
        <div class='form-card-header'>
          <div class='form-card-header-inner'>
            <div class='step-title-wrap'>
              <h2 id='stepHeadTitle'>Contact Information</h2>
              <p id='stepHeadSub'>Step 1 of 11 — Required fields marked with ◆</p>
            </div>
            <div class='step-counter-badge'>
              <span class='num' id='stepNumBig'>01</span>
              <span class='of'>/ 11</span>
            </div>
          </div>
        </div>
        <div class='prog-bar-wrap'>
          <div class='prog-bar-fill' id='progFill' style='width:9%'></div>
        </div>

        <div class='form-body'>
          <form id='readinessForm' autocomplete='off' novalidate>
            <input type="hidden" name="appointmentId" value="<?php echo htmlspecialchars($appointmentId, ENT_QUOTES, 'UTF-8'); ?>">

            <!-- STEP 1 -->
            <div class='survey-step active' data-step='1'>
              <div class='field-grid'>
                <div class='field-group'>
                  <label class='f-label f-required'>First Name</label>
                  <input class='f-input' name='first_name' autocomplete='given-name' required placeholder='First name'>
                </div>
                <div class='field-group'>
                  <label class='f-label f-required'>Last Name</label>
                  <input class='f-input' name='last_name' autocomplete='family-name' required placeholder='Last name'>
                </div>
                <div class='field-group'>
                  <label class='f-label f-required'>Email Address</label>
                  <input class='f-input' type='email' name='email' autocomplete='email' required placeholder='you@email.com'>
                </div>
                <div class='field-group'>
                  <label class='f-label f-required'>Phone Number</label>
                  <input class='f-input' name='phone' autocomplete='tel' required placeholder='(555) 000-0000'>
                </div>
                <div class='field-group'>
                  <label class='f-label f-required'>Best Phone for Consultation</label>
                  <input class='f-input' name='best_phone' required placeholder='(555) 000-0000'>
                </div>
                <div class='field-group'>
                  <label class='f-label f-required'>State</label>
                  <select class='f-select' name='state' required>
                    <option value=''>Select state</option>
                    <option>Alabama</option><option>Alaska</option><option>Arizona</option><option>Arkansas</option><option>California</option><option>Colorado</option><option>Connecticut</option><option>Delaware</option><option>Florida</option><option>Georgia</option><option>Hawaii</option><option>Idaho</option><option>Illinois</option><option>Indiana</option><option>Iowa</option><option>Kansas</option><option>Kentucky</option><option>Louisiana</option><option>Maine</option><option>Maryland</option><option>Massachusetts</option><option>Michigan</option><option>Minnesota</option><option>Mississippi</option><option>Missouri</option><option>Montana</option><option>Nebraska</option><option>Nevada</option><option>New Hampshire</option><option>New Jersey</option><option>New Mexico</option><option>New York</option><option>North Carolina</option><option>North Dakota</option><option>Ohio</option><option>Oklahoma</option><option>Oregon</option><option>Pennsylvania</option><option>Rhode Island</option><option>South Carolina</option><option>South Dakota</option><option>Tennessee</option><option>Texas</option><option>Utah</option><option>Vermont</option><option>Virginia</option><option>Washington</option><option>West Virginia</option><option>Wisconsin</option><option>Wyoming</option><option>Washington D.C.</option>
                  </select>
                </div>
                <div class='field-group'>
                  <label class='f-label f-required'>Time Zone</label>
                  <select class='f-select' name='timezone' required>
                    <option value=''>Select timezone</option>
                    <option>Eastern (ET)</option><option>Central (CT)</option><option>Mountain (MT)</option><option>Pacific (PT)</option><option>Alaska (AKT)</option><option>Hawaii (HT)</option>
                  </select>
                </div>
                <div class='field-group'>
                  <label class='f-label'>Preferred Name</label>
                  <input class='f-input' name='preferred_name' placeholder='Goes by...'>
                </div>
                <div class='field-group'>
                  <label class='f-label'>City</label>
                  <input class='f-input' name='city' autocomplete='address-level2' placeholder='City'>
                </div>
                <div class='field-group'>
                  <label class='f-label'>Alternate Phone</label>
                  <input class='f-input' name='alternate_phone' placeholder='(555) 000-0000'>
                </div>
                <div class='field-group'>
                  <label class='f-label'>Best Time to Reach You</label>
                  <select class='f-select' name='best_time'>
                    <option value=''>Select</option>
                    <option>Morning (8am – 12pm)</option><option>Afternoon (12pm – 5pm)</option><option>Evening (5pm – 8pm)</option>
                  </select>
                </div>
              </div>
              <div class='field-group' style='margin-top:.5rem'>
                <label class='f-label f-required'>Can we text you reminders and follow-up messages?</label>
                <div style='display:flex;gap:.75rem;flex-wrap:wrap'>
                  <label class='choice-item' style='flex:1;min-width:120px'><input type='radio' name='text_consent' value='Yes' required><span class='choice-dot'></span>Yes</label>
                  <label class='choice-item' style='flex:1;min-width:120px'><input type='radio' name='text_consent' value='No'><span class='choice-dot'></span>No</label>
                </div>
              </div>
            </div>

            <!-- STEP 2 -->
            <div class='survey-step' data-step='2'>
              <div class='field-group'>
                <label class='f-label f-required'>Who is this consultation for?</label>
                <select class='f-select' name='consultation_for' required>
                  <option value=''>Select one</option>
                  <option>Myself</option><option>Spouse / partner</option><option>Parent</option><option>Adult child</option><option>Minor child</option><option>Sibling</option><option>Family member</option><option>Business partner</option><option>Client / referral</option><option>Other</option>
                </select>
              </div>
              <div class='field-group'>
                <label class='f-label f-required'>Are you the person directly impacted?</label>
                <select class='f-select' name='directly_impacted' required>
                  <option value=''>Select one</option>
                  <option>Yes</option><option>No</option><option>I am supporting someone else</option><option>Shared situation</option><option>Not sure</option>
                </select>
              </div>
              <div class='field-group'>
                <label class='f-label f-required'>Are you the main decision-maker for paid support if recommended?</label>
                <select class='f-select' name='decision_maker' required>
                  <option value=''>Select one</option>
                  <option>Yes</option><option>No</option><option>Shared decision</option><option>Not sure</option>
                </select>
              </div>
              <div class='field-group'>
                <label class='f-label'>If someone else needs to be involved, who?</label>
                <input class='f-input' name='other_decision_person' placeholder='Name and relationship'>
              </div>
            </div>

            <!-- STEP 3 -->
            <div class='survey-step' data-step='3'>
              <div class='field-group'>
                <label class='f-label f-required'>Which stage best describes the current situation?</label>
                <select class='f-select' name='current_stage' required>
                  <option value=''>Select one</option>
                  <option>Not sure yet</option><option>Under investigation</option><option>Charged / case pending</option><option>Pretrial</option><option>Preparing for sentencing</option><option>Sentenced</option><option>Preparing to report</option><option>Currently incarcerated</option><option>Preparing for halfway house / RRC</option><option>Preparing for home confinement</option><option>Recently released / reentry</option><option>Post-release rebuild</option><option>Business / reputation concern</option><option>Family member seeking support</option><option>Other</option>
                </select>
              </div>
              <div class='field-group'>
                <label class='f-label f-required'>How urgent does this feel right now?</label>
                <div class='choices-grid'>
                  <label class='choice-item'><input type='radio' name='urgency' value='Immediate: 0–7 days' required><span class='choice-dot'></span>Immediate: 0–7 days</label>
                  <label class='choice-item'><input type='radio' name='urgency' value='Soon: 1–2 weeks'><span class='choice-dot'></span>Soon: 1–2 weeks</label>
                  <label class='choice-item'><input type='radio' name='urgency' value='This month'><span class='choice-dot'></span>This month</label>
                  <label class='choice-item'><input type='radio' name='urgency' value='30–60 days'><span class='choice-dot'></span>30–60 days</label>
                  <label class='choice-item'><input type='radio' name='urgency' value='Long-term planning'><span class='choice-dot'></span>Long-term planning</label>
                  <label class='choice-item'><input type='radio' name='urgency' value='Not sure'><span class='choice-dot'></span>Not sure</label>
                </div>
              </div>
            </div>

            <!-- STEP 4 -->
            <div class='survey-step' data-step='4'>
              <div class='field-group'>
                <label class='f-label f-required'>Is there an important upcoming date or deadline?</label>
                <select class='f-select' name='has_important_date' id='hasImportantDate' required>
                  <option value=''>Select one</option>
                  <option>Yes</option><option>No</option><option>Not sure</option>
                </select>
              </div>
              <div id='importantDateFields' style='display:none'>
                <div class='section-divider'><div class='section-divider-line'></div><span class='section-divider-label'>Date Details</span><div class='section-divider-line'></div></div>
                <div class='field-grid'>
                  <div class='field-group'>
                    <label class='f-label f-required'>What is the date?</label>
                    <input type='date' class='f-input conditional-required' name='important_date'>
                  </div>
                  <div class='field-group'>
                    <label class='f-label f-required'>Type of date or deadline</label>
                    <select class='f-select conditional-required' name='deadline_type'>
                      <option value=''>Select one</option>
                      <option>Court date</option><option>Sentencing date</option><option>Report date</option><option>Release date</option><option>Halfway house / RRC date</option><option>Home confinement date</option><option>Probation / supervision appointment</option><option>Attorney meeting</option><option>Business deadline</option><option>Other</option>
                    </select>
                  </div>
                </div>
                <div class='field-group'>
                  <label class='f-label'>What do you need to be ready for before this date?</label>
                  <textarea class='f-textarea' name='date_readiness_need' placeholder='Describe what preparation is needed...'></textarea>
                </div>
              </div>
            </div>

            <!-- STEP 5 -->
            <div class='survey-step' data-step='5'>
              <div class='field-group'>
                <label class='f-label f-required' style='margin-bottom:.75rem'>Main areas of concern <span style='font-weight:500;text-transform:none;letter-spacing:0;font-size:.75rem;color:var(--ink-soft)'>(select all that apply)</span></label>
                <div class='choices-grid' id='concernOptions'>
                  <label class='choice-item'><input type='checkbox' name='concerns' value='Family stability'><span class='choice-dot'></span>Family stability</label>
                  <label class='choice-item'><input type='checkbox' name='concerns' value='Children / household impact'><span class='choice-dot'></span>Children / household impact</label>
                  <label class='choice-item'><input type='checkbox' name='concerns' value='Spouse or partner support'><span class='choice-dot'></span>Spouse or partner support</label>
                  <label class='choice-item'><input type='checkbox' name='concerns' value='Bills / household responsibilities'><span class='choice-dot'></span>Bills / household responsibilities</label>
                  <label class='choice-item'><input type='checkbox' name='concerns' value='Financial organization'><span class='choice-dot'></span>Financial organization</label>
                  <label class='choice-item'><input type='checkbox' name='concerns' value='Credit concerns'><span class='choice-dot'></span>Credit concerns</label>
                  <label class='choice-item'><input type='checkbox' name='concerns' value='Business continuity'><span class='choice-dot'></span>Business continuity</label>
                  <label class='choice-item'><input type='checkbox' name='concerns' value='Employment concerns'><span class='choice-dot'></span>Employment concerns</label>
                  <label class='choice-item'><input type='checkbox' name='concerns' value='Reputation / online presence'><span class='choice-dot'></span>Reputation / online presence</label>
                  <label class='choice-item'><input type='checkbox' name='concerns' value='Prison readiness'><span class='choice-dot'></span>Prison readiness</label>
                  <label class='choice-item'><input type='checkbox' name='concerns' value='Productive-time planning'><span class='choice-dot'></span>Productive-time planning</label>
                  <label class='choice-item'><input type='checkbox' name='concerns' value='Release preparation'><span class='choice-dot'></span>Release preparation</label>
                  <label class='choice-item'><input type='checkbox' name='concerns' value='Halfway house / RRC readiness'><span class='choice-dot'></span>Halfway house / RRC readiness</label>
                  <label class='choice-item'><input type='checkbox' name='concerns' value='Home confinement preparation'><span class='choice-dot'></span>Home confinement preparation</label>
                  <label class='choice-item'><input type='checkbox' name='concerns' value='Reentry planning'><span class='choice-dot'></span>Reentry planning</label>
                  <label class='choice-item'><input type='checkbox' name='concerns' value='Not sure where to start'><span class='choice-dot'></span>Not sure where to start</label>
                </div>
              </div>
              <div class='field-group'>
                <label class='f-label f-required'>What is the biggest pressure point right now?</label>
                <textarea class='f-textarea' name='biggest_pressure' required placeholder='Describe your most pressing concern...'></textarea>
              </div>
              <div class='field-group'>
                <label class='f-label f-required'>What would make this consultation helpful for you?</label>
                <textarea class='f-textarea' name='helpful_consultation' required placeholder='What outcome are you hoping for?'></textarea>
              </div>
            </div>

            <!-- STEP 6 -->
            <div class='survey-step' data-step='6'>
              <div class='field-group'>
                <label class='f-label f-required'>Do you currently have an attorney?</label>
                <select class='f-select' name='has_attorney' required>
                  <option value=''>Select one</option>
                  <option>Yes</option><option>No</option><option>Searching for one</option><option>Not applicable</option><option>Prefer not to say</option>
                </select>
              </div>
              <div class='section-divider'><div class='section-divider-line'></div><span class='section-divider-label'>Attorney Details (if applicable)</span><div class='section-divider-line'></div></div>
              <div class='field-grid'>
                <div class='field-group'><label class='f-label'>Attorney Name</label><input class='f-input' name='attorney_name' placeholder='Full name'></div>
                <div class='field-group'><label class='f-label'>Attorney Firm</label><input class='f-input' name='attorney_firm' placeholder='Firm name'></div>
                <div class='field-group'><label class='f-label'>Attorney Phone</label><input class='f-input' name='attorney_phone' placeholder='(555) 000-0000'></div>
                <div class='field-group'><label class='f-label'>Attorney Email</label><input class='f-input' type='email' name='attorney_email' placeholder='attorney@firm.com'></div>
              </div>
              <div class='field-group' style='margin-top:.5rem'>
                <label class='f-label'>Avoid contacting professionals without written permission?</label>
                <select class='f-select' name='avoid_professional_contact'>
                  <option value=''>Select one</option>
                  <option>Yes</option><option>No</option><option>Not applicable</option>
                </select>
              </div>
            </div>

            <!-- STEP 7 CONDITIONAL -->
            <div class='survey-step' data-step='7'>
              <div id='conditionalSections'></div>
            </div>

            <!-- STEP 8 -->
            <div class='survey-step' data-step='8'>
              <div class='field-group'>
                <label class='f-label f-required' style='margin-bottom:.75rem'>What do you most want to leave this consultation with?</label>
                <div class='choices-grid'>
                  <label class='choice-item'><input type='checkbox' name='consultation_goals' value='Clear next steps'><span class='choice-dot'></span>Clear next steps</label>
                  <label class='choice-item'><input type='checkbox' name='consultation_goals' value='Family stability direction'><span class='choice-dot'></span>Family stability direction</label>
                  <label class='choice-item'><input type='checkbox' name='consultation_goals' value='Financial organization direction'><span class='choice-dot'></span>Financial organization direction</label>
                  <label class='choice-item'><input type='checkbox' name='consultation_goals' value='Credit readiness direction'><span class='choice-dot'></span>Credit readiness direction</label>
                  <label class='choice-item'><input type='checkbox' name='consultation_goals' value='Business continuity direction'><span class='choice-dot'></span>Business continuity direction</label>
                  <label class='choice-item'><input type='checkbox' name='consultation_goals' value='Reputation strategy direction'><span class='choice-dot'></span>Reputation strategy direction</label>
                  <label class='choice-item'><input type='checkbox' name='consultation_goals' value='Prison readiness direction'><span class='choice-dot'></span>Prison readiness direction</label>
                  <label class='choice-item'><input type='checkbox' name='consultation_goals' value='Productive-time direction'><span class='choice-dot'></span>Productive-time direction</label>
                  <label class='choice-item'><input type='checkbox' name='consultation_goals' value='Release / reentry direction'><span class='choice-dot'></span>Release / reentry direction</label>
                  <label class='choice-item'><input type='checkbox' name='consultation_goals' value='Package recommendation'><span class='choice-dot'></span>Package recommendation</label>
                  <label class='choice-item'><input type='checkbox' name='consultation_goals' value='Not sure'><span class='choice-dot'></span>Not sure</label>
                </div>
              </div>
              <div class='field-group'>
                <label class='f-label f-required'>What would make this consultation successful for you?</label>
                <textarea class='f-textarea' name='successful_consultation' required placeholder='Describe your ideal outcome...'></textarea>
              </div>
            </div>

            <!-- STEP 9 -->
            <div class='survey-step' data-step='9'>
              <p style='font-size:.8rem;color:var(--ink-soft);margin-bottom:1.5rem;font-weight:600'>Rate your current preparedness in each area — 1 = Not prepared, 5 = Very prepared</p>
              <div id='ratingGrid' class='field-grid'>
                <div class='rating-card'><div class='rating-card-label'>Family stability</div><div class='rating-group' data-name='score_family'></div><input type='hidden' name='score_family' class='rating-val' required></div>
                <div class='rating-card'><div class='rating-card-label'>Financial organization</div><div class='rating-group' data-name='score_financial'></div><input type='hidden' name='score_financial' class='rating-val' required></div>
                <div class='rating-card'><div class='rating-card-label'>Business / employment continuity</div><div class='rating-group' data-name='score_business'></div><input type='hidden' name='score_business' class='rating-val' required></div>
                <div class='rating-card'><div class='rating-card-label'>Reputation readiness</div><div class='rating-group' data-name='score_reputation'></div><input type='hidden' name='score_reputation' class='rating-val' required></div>
                <div class='rating-card'><div class='rating-card-label'>Prison / reporting readiness</div><div class='rating-group' data-name='score_prison'></div><input type='hidden' name='score_prison' class='rating-val' required></div>
                <div class='rating-card'><div class='rating-card-label'>Release / reentry readiness</div><div class='rating-group' data-name='score_reentry'></div><input type='hidden' name='score_reentry' class='rating-val' required></div>
                <div class='rating-card' style='grid-column:1/-1'><div class='rating-card-label'>Overall readiness</div><div class='rating-group' data-name='score_overall'></div><input type='hidden' name='score_overall' class='rating-val' required></div>
              </div>
            </div>

            <!-- STEP 10 -->
            <div class='survey-step' data-step='10'>
              <div class='notice-box' style='margin-bottom:1.5rem'>
                <strong>Important:</strong> Please do not upload confidential legal strategy, privileged attorney-client communications, protected health information, or sensitive case materials unless directed by your licensed attorney.
              </div>
              <label class='upload-area' id='uploadArea'>
                <div class='upload-icon'>📎</div>
                <div class='upload-text'><strong>Click to upload</strong> or drag and drop</div>
                <div class='upload-text' style='font-size:.72rem;margin-top:.25rem;font-weight:500'>Accepted: PDF, DOC, DOCX, JPG, PNG</div>
                <input type='file' name='uploads' multiple accept='.pdf,.doc,.docx,.jpg,.jpeg,.png'>
              </label>
              <div id='fileList' style='margin-top:1rem'></div>
            </div>

            <!-- STEP 11 -->
            <div class='survey-step' data-step='11'>
              <div class='disclaimer-box'>
                The PHELONPRENEUR Network provides confidential, non-legal readiness and life-stability support only. We do not provide legal advice, legal representation, financial advice, tax advice, therapy, medical advice, sentence calculation, sentence reduction assistance, release-date guarantees, program eligibility guarantees, credit repair guarantees, reputation removal guarantees, business outcome guarantees, employment guarantees, housing guarantees, or legal outcome promises.<br><br>
                Information submitted through this form is used only to understand readiness needs and prepare for the Readiness Consultation. This form and consultation are not substitutes for legal, financial, tax, medical, clinical, or supervision-related advice.<br><br>
                Submitting this form, paying for a consultation, or attending a consultation does not create an attorney-client relationship, legal advisory relationship, financial advisory relationship, therapist-client relationship, medical provider relationship, or any professional relationship requiring licensed advice.
              </div>
              <div class='consent-list'>
                <label class='consent-item'><input type='checkbox' name='consent_1' required><span class='consent-check-box'></span>I understand that The PHELONPRENEUR Network provides non-legal readiness and life-stability support only.</label>
                <label class='consent-item'><input type='checkbox' name='consent_2' required><span class='consent-check-box'></span>I understand this consultation does not replace an attorney or any licensed professional.</label>
                <label class='consent-item'><input type='checkbox' name='consent_3' required><span class='consent-check-box'></span>I understand that no legal, financial, credit, reputation, employment, housing, program, release, or reentry outcome is guaranteed.</label>
                <label class='consent-item'><input type='checkbox' name='consent_4' required><span class='consent-check-box'></span>I understand that submitting this form and attending this consultation does not create an attorney-client relationship.</label>
                <label class='consent-item'><input type='checkbox' name='consent_5' required><span class='consent-check-box'></span>I agree not to submit privileged legal strategy, confidential attorney-client communication, protected health information, or sensitive case details.</label>
                <label class='consent-item'><input type='checkbox' name='consent_6' required><span class='consent-check-box'></span>I consent to being contacted by phone, email, or text regarding my consultation and related services.</label>
                <label class='consent-item'><input type='checkbox' name='consent_7' required><span class='consent-check-box'></span>I understand that the consultation fee is paid before booking and is subject to the cancellation / reschedule policy.</label>
              </div>
            </div>

            <!-- Nav -->
            <div class='form-nav'>
              <button type='button' id='prevBtn' class='btn btn-ghost hidden'>← Back</button>
              <button type='button' id='nextBtn' class='btn btn-primary'><span>Next Step →</span></button>
              <button type='submit' id='submitBtn' class='btn btn-submit hidden'>Submit Intake Form ◆</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </section>

  <div class='toast' id='toast'></div>

  <script>
  const STEP_TITLES = [
    ['Contact Information','Personal and contact details'],
    ['Consultation Purpose','Who this is for and your role'],
    ['Current Situation','Stage and urgency'],
    ['Important Dates','Upcoming deadlines or dates'],
    ['Areas of Concern','What matters most right now'],
    ['Attorney & Professional Support','Legal representation details'],
    ['Impact Details','Situation-specific follow-up'],
    ['Consultation Goals','What you want to leave with'],
    ['Readiness Self-Assessment','Rate your current preparedness'],
    ['Optional Uploads','Supporting documents'],
    ['Disclaimer & Consent','Review and agree to terms']
  ];
  const RAIL_LABELS = ['Contact','Purpose','Situation','Dates','Concerns','Attorney','Impact','Goals','Scores','Uploads','Consent'];
  const conditionalMap = [
    {title:'Family Stability',triggers:['Family stability','Children / household impact','Spouse or partner support'],field:'family_affected',question:'Is your family currently affected by this situation?'},
    {title:'Household & Financial Impact',triggers:['Bills / household responsibilities','Financial organization'],field:'financial_affected',question:'Are bills, income, or household responsibilities currently affected?'},
    {title:'Credit Impact',triggers:['Credit concerns'],field:'credit_affected',question:'Are there credit concerns connected to this situation?'},
    {title:'Business & Employment Impact',triggers:['Business continuity','Employment concerns'],field:'business_affected',question:'Is a business, job, professional role, or employment situation affected?'},
    {title:'Reputation & Digital Impact',triggers:['Reputation / online presence'],field:'reputation_affected',question:'Are there reputation, online presence, or public image concerns?'},
    {title:'Prison Readiness & Reporting',triggers:['Prison readiness'],field:'prison_affected',question:'Is preparation for reporting, incarceration, or time away from home a concern?'},
    {title:'Productive-Time Planning',triggers:['Productive-time planning'],field:'productive_time_affected',question:'Is the person interested in using time productively?'},
    {title:'Release & Reentry Planning',triggers:['Release preparation','Halfway house / RRC readiness','Home confinement preparation','Reentry planning'],field:'reentry_affected',question:'Is release, halfway house, home confinement, or reentry preparation needed?'}
  ];

  const form = document.getElementById('readinessForm');
  const steps = [...document.querySelectorAll('.survey-step')];
  const nextBtn = document.getElementById('nextBtn');
  const prevBtn = document.getElementById('prevBtn');
  const submitBtn = document.getElementById('submitBtn');
  const toast = document.getElementById('toast');

  let current = 0;

  const rail = document.getElementById('stepRail');
  RAIL_LABELS.forEach((label,i) => {
    const item = document.createElement('div');
    item.className = 'step-rail-item';
    item.innerHTML = `<div class="step-dot" id="rdot${i}"><span>${i+1}</span></div><div class="step-label" id="rlabel${i}">${label}</div>`;
    rail.appendChild(item);
  });

  document.querySelectorAll('.rating-group').forEach(group => {
    const name = group.dataset.name;
    for(let v=1;v<=5;v++){
      const b = document.createElement('button');
      b.type='button'; b.className='rating-btn'; b.textContent=v;
      b.addEventListener('click',()=>{
        group.querySelectorAll('.rating-btn').forEach(x=>x.classList.remove('sel'));
        b.classList.add('sel');
        form.querySelector(`input[name="${name}"].rating-val`).value = v;
        
      });
      group.appendChild(b);
    }
  });

  document.querySelectorAll('.choice-item').forEach(item => {
        const inp = item.querySelector('input');
        if (!inp) return;

        inp.addEventListener('change', () => {

            if (inp.type === 'radio') {
                document
                    .querySelectorAll(`input[name="${inp.name}"]`)
                    .forEach(radio => {
                        radio.closest('.choice-item')?.classList.remove('checked');
                    });
            }

            item.classList.toggle('checked', inp.checked);
        });
    });

    document.querySelectorAll('.consent-item').forEach(item => {
        const inp = item.querySelector('input[type="checkbox"]');

        inp.addEventListener('change', () => {
            item.classList.toggle('checked', inp.checked);
            
        });
    });

  document.getElementById('hasImportantDate').addEventListener('change', function(){
    const show = this.value==='Yes';
    document.getElementById('importantDateFields').style.display = show ? 'block' : 'none';
    document.querySelectorAll('.conditional-required').forEach(f=>{
      if(show) f.setAttribute('required','required');
      else { f.removeAttribute('required'); f.value=''; }
    });
    
  });

  function buildConditional(){
    const selected = [...form.querySelectorAll('input[name="concerns"]:checked')].map(i=>i.value);
    const wrap = document.getElementById('conditionalSections');
    wrap.innerHTML='';
    const active = conditionalMap.filter(s=>s.triggers.some(t=>selected.includes(t)));
    if(!active.length){ wrap.innerHTML=`<div class="notice-box" style="text-align:center;padding:2rem">No additional detail sections based on your selected concerns.</div>`; return; }
    active.forEach(s=>{
      wrap.insertAdjacentHTML('beforeend',`
        <div class="cond-panel">
          <div class="cond-panel-title">${s.title}</div>
          <div class="field-group">
            <label class="f-label f-required">${s.question}</label>
            <select class="f-select" name="${s.field}" required>
              <option value="">Select one</option>
              <option>Yes</option><option>No</option><option>Somewhat</option><option>Maybe</option><option>Not sure</option>
            </select>
          </div>
          <div class="field-group">
            <label class="f-label">What is the biggest concern in this area?</label>
            <textarea class="f-textarea" name="${s.field}_details" placeholder="Describe..."></textarea>
          </div>
        </div>
      `);
    });
  }

  document.querySelector('input[name="uploads"]').addEventListener('change',function(){
    const fl=document.getElementById('fileList'); fl.innerHTML='';
    [...this.files].forEach(file=>{
      fl.insertAdjacentHTML('beforeend',`<div style="display:flex;align-items:center;gap:.5rem;padding:.5rem .75rem;background:rgba(201,162,74,.08);border-radius:8px;margin-bottom:.4rem;font-size:.78rem;font-weight:600;color:var(--ink-mid)">📄 ${file.name} <span style="color:var(--ink-soft);font-weight:400">(${(file.size/1024).toFixed(1)} KB)</span></div>`);
    });
  });

  function getFormData(){
    const data={};
    const fd=new FormData(form);
    for(const [k,v] of fd.entries()){
      if(k==='uploads') continue;
      if(data[k]){ if(!Array.isArray(data[k])) data[k]=[data[k]]; data[k].push(v); } else data[k]=v;
    }
    return data;
  }



  function showToast(msg){ toast.textContent=msg; toast.classList.add('show'); setTimeout(()=>toast.classList.remove('show'),3200); }

function validateStep() {

    const activeStep = steps[current];

    // Regular required fields
    const requiredFields = activeStep.querySelectorAll(
        'input[required]:not([type=checkbox]):not([type=radio]), select[required], textarea[required]'
    );

    for (const field of requiredFields) {
        if (!field.value.trim()) {
            showToast('Please complete all required fields.');
            field.focus();
            return false;
        }
    }

    // Required radio groups
    const radioGroups = [...new Set(
        [...activeStep.querySelectorAll('input[type="radio"][required]')]
        .map(r => r.name)
    )];

    for (const groupName of radioGroups) {
        if (!activeStep.querySelector(`input[name="${groupName}"]:checked`)) {
            showToast('Please make a selection before continuing.');
            return false;
        }
    }

    // Step 5 Concerns
    if (current === 4) {
        const selected = form.querySelectorAll(
            'input[name="concerns"]:checked'
        );

        if (!selected.length) {
            showToast('Please select at least one area of concern.');
            return false;
        }
    }

    // Step 8 Goals
    if (current === 7) {
        const selected = form.querySelectorAll(
            'input[name="consultation_goals"]:checked'
        );

        if (!selected.length) {
            showToast('Please select at least one consultation goal.');
            return false;
        }
    }

    // Step 9 Ratings
    if (current === 8) {
        const ratings = document.querySelectorAll('.rating-val');

        for (const rating of ratings) {
            if (!rating.value) {
                showToast('Please rate all areas.');
                return false;
            }
        }
    }

    // Step 11 Consent Checkboxes
    if (current === 10) {

        const consentBoxes = activeStep.querySelectorAll(
            'input[name^="consent_"]'
        );

        for (const box of consentBoxes) {
            if (!box.checked) {
                showToast('Please agree to all consent items.');
                return false;
            }
        }
    }

    return true;
}

  function updateUI(){
    const pct=Math.round(((current+1)/steps.length)*100);
    document.getElementById('progFill').style.width=pct+'%';
    const [title]=STEP_TITLES[current];
    document.getElementById('stepHeadTitle').textContent=title;
    document.getElementById('stepHeadSub').textContent=`Step ${current+1} of ${steps.length} — Required fields marked with ◆`;
    document.getElementById('stepNumBig').textContent=String(current+1).padStart(2,'0');
    prevBtn.classList.toggle('hidden',current===0);
    nextBtn.classList.toggle('hidden',current===steps.length-1);
    submitBtn.classList.toggle('hidden',current!==steps.length-1);
    for(let i=0;i<steps.length;i++){
      const dot=document.getElementById('rdot'+i);
      const lbl=document.getElementById('rlabel'+i);
      dot.className='step-dot'+(i<current?' done':i===current?' active':'');
      dot.innerHTML=i<current?'':`<span>${i+1}</span>`;
      lbl.className='step-label'+(i===current?' active':i<current?' done':'');
    }
  }

  function showStep(idx){
    steps.forEach(s=>s.classList.remove('active'));
    steps[idx].classList.add('active');
    updateUI();
    window.scrollTo({top:0,behavior:'smooth'});
  }

  nextBtn.addEventListener('click',()=>{ if(!validateStep()) return;  if(current===4) buildConditional(); current++; showStep(current); });
  prevBtn.addEventListener('click',()=>{  current--; showStep(current); });


  /* ── REVIEW MODAL ──────────────────────────────────────────────── */
  function buildReviewHTML(data){
    const sections = [
      {
        title: '01 — Contact Information',
        icon: '👤',
        fields: [
          ['first_name','First Name'],['last_name','Last Name'],
          ['email','Email'],['phone','Phone'],
          ['best_phone','Best Consultation Phone'],['state','State'],
          ['timezone','Time Zone'],['city','City'],
          ['preferred_name','Preferred Name'],['alternate_phone','Alternate Phone'],
          ['best_time','Best Time to Reach'],['text_consent','Text Consent']
        ]
      },
      {
        title: '02 — Consultation Purpose',
        icon: '🎯',
        fields: [
          ['consultation_for','Consultation For'],['directly_impacted','Directly Impacted'],
          ['decision_maker','Decision Maker'],['other_decision_person','Other Decision Person']
        ]
      },
      {
        title: '03 — Current Situation',
        icon: '📋',
        fields: [
          ['current_stage','Current Stage'],['urgency','Urgency Level']
        ]
      },
      {
        title: '04 — Important Dates',
        icon: '📅',
        fields: [
          ['has_important_date','Has Important Date'],['important_date','Date'],
          ['deadline_type','Deadline Type'],['date_readiness_need','Readiness Need']
        ]
      },
      {
        title: '05 — Areas of Concern',
        icon: '⚠️',
        fields: [
          ['concerns','Concerns Selected'],
          ['biggest_pressure','Biggest Pressure Point'],
          ['helpful_consultation','What Would Help']
        ]
      },
      {
        title: '06 — Attorney & Professional Support',
        icon: '⚖️',
        fields: [
          ['has_attorney','Has Attorney'],['attorney_name','Attorney Name'],
          ['attorney_firm','Firm'],['attorney_phone','Attorney Phone'],
          ['attorney_email','Attorney Email'],['avoid_professional_contact','Avoid Contact Without Permission']
        ]
      },
      {
        title: '07 — Impact Details',
        icon: '🔍',
        fields: [
          ['family_affected','Family Affected'],['family_affected_details','Family Details'],
          ['financial_affected','Financial Affected'],['financial_affected_details','Financial Details'],
          ['credit_affected','Credit Affected'],['credit_affected_details','Credit Details'],
          ['business_affected','Business Affected'],['business_affected_details','Business Details'],
          ['reputation_affected','Reputation Affected'],['reputation_affected_details','Reputation Details'],
          ['prison_affected','Prison / Reporting Affected'],['prison_affected_details','Prison Details'],
          ['productive_time_affected','Productive Time'],['productive_time_affected_details','Productive Time Details'],
          ['reentry_affected','Reentry Affected'],['reentry_affected_details','Reentry Details']
        ]
      },
      {
        title: '08 — Consultation Goals',
        icon: '🏆',
        fields: [
          ['consultation_goals','Goals Selected'],
          ['successful_consultation','Definition of Success']
        ]
      },
      {
        title: '09 — Readiness Scores',
        icon: '📊',
        fields: [
          ['score_family','Family Stability'],['score_financial','Financial Organization'],
          ['score_business','Business / Employment'],['score_reputation','Reputation Readiness'],
          ['score_prison','Prison / Reporting'],['score_reentry','Release / Reentry'],
          ['score_overall','Overall Readiness']
        ]
      }
    ];

    const s = (color, opacity=1) => `rgba(${color},${opacity})`;
    const gold = '201,162,74';
    const ink  = '26,20,16';

    let html = `
      <div style="font-family:'Montserrat',sans-serif;text-align:left;">
        <p style="font-size:.75rem;color:#9c8a6e;margin-bottom:1rem;line-height:1.6;padding:.65rem .85rem;background:rgba(201,162,74,.08);border-radius:8px;border:1px solid rgba(201,162,74,.2);">
          Please review all entries carefully. Use <strong style="color:#3d3220">Go Back &amp; Edit</strong> to make changes, or <strong style="color:#3d3220">Confirm &amp; Submit</strong> to send your intake form.
        </p>
        <div style="max-height:420px;overflow-y:auto;padding-right:4px;">
    `;

    sections.forEach(sec => {
      const rows = sec.fields
        .map(([key, label]) => {
          const val = data[key];
          if (!val || val === '') return null;
          const display = Array.isArray(val) ? val.join('<br>• ') : val;
          const isArray = Array.isArray(val) && val.length > 1;
          return `
            <tr>
              <td style="padding:.5rem .6rem .5rem 0;vertical-align:top;font-size:.72rem;font-weight:700;color:#6b5c40;white-space:nowrap;width:42%;border-bottom:1px solid rgba(201,162,74,.1);">${label}</td>
              <td style="padding:.5rem 0 .5rem .4rem;vertical-align:top;font-size:.72rem;color:#1a1410;font-weight:500;border-bottom:1px solid rgba(201,162,74,.1);">${isArray ? '• ' + display : display}${key.startsWith('score_') ? ' / 5' : ''}</td>
            </tr>`;
        })
        .filter(Boolean);

      if (!rows.length) return;

      html += `
        <div style="margin-bottom:1rem;border:1px solid rgba(201,162,74,.2);border-radius:12px;overflow:hidden;">
          <div style="background:linear-gradient(135deg,#1a1410,#3d3220);padding:.6rem .9rem;display:flex;align-items:center;gap:.5rem;">
            <span style="font-size:.85rem;">${sec.icon}</span>
            <span style="font-size:.68rem;font-weight:800;letter-spacing:.08em;color:rgba(255,255,255,.85);text-transform:uppercase;">${sec.title}</span>
          </div>
          <div style="padding:.25rem .75rem;">
            <table style="width:100%;border-collapse:collapse;">${rows.join('')}</table>
          </div>
        </div>`;
    });

    html += `
        </div>
      </div>`;

    return html;
  }

  /* ── SUBMIT FLOW ───────────────────────────────────────────────── */
  form.addEventListener('submit', async e => {
    e.preventDefault();
    if (!validateStep()) return;

    const data = getFormData();

    /* Show sectioned review modal */
    const result = await Swal.fire({
      title: '<span style="font-family:\'Playfair Display\',serif;font-size:1.3rem;color:#1a1410">Review Your Submission</span>',
      html: buildReviewHTML(data),
      width: 680,
      padding: '1.5rem',
      showCancelButton: true,
      confirmButtonText: 'Confirm &amp; Submit ◆',
      cancelButtonText: '← Go Back &amp; Edit',
      confirmButtonColor: '#c9a24a',
      cancelButtonColor: '#3d3220',
      reverseButtons: true,
      customClass: {
        popup: 'swal-phelon-review',
        confirmButton: 'swal-btn-confirm',
        cancelButton: 'swal-btn-cancel'
      }
    });

    if (!result.isConfirmed) return;

    /* Show loading state */
    Swal.fire({
      title: 'Submitting…',
      html: '<p style="font-family:Montserrat,sans-serif;font-size:.85rem;color:#6b5c40">Sending your intake form securely. Please wait.</p>',
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => Swal.showLoading()
    });

    try {
      const formData = new FormData();

      formData.append('payload', JSON.stringify(data));

      const uploadInput = form.querySelector('input[name="uploads"]');

      if (uploadInput && uploadInput.files.length > 0) {
        [...uploadInput.files].forEach(file => {
          formData.append('uploads[]', file);
        });
      }

      const response = await fetch('api/readiness-intake.php', {
        method: 'POST',
        body: formData
      });

      const json = await response.json();

      if (!response.ok || !json.success) {
        throw new Error(json.message || `Server error: ${response.status}`);
      }


      Swal.fire({
        icon: 'success',
        title: '<span style="font-family:\'Playfair Display\',serif;font-size:1.3rem;color:#1a1410">Form Submitted</span>',
        html: `
          <div style="font-family:Montserrat,sans-serif;font-size:.85rem;color:#6b5c40;line-height:1.7;">
            <p>Your readiness consultation intake form has been received.</p>
            <p style="margin-top:.75rem;padding:.65rem .85rem;background:rgba(201,162,74,.08);border-radius:8px;border:1px solid rgba(201,162,74,.2);">
              We will be in touch within <strong style="color:#3d3220">one business day</strong> to confirm your consultation details.
            </p>
          </div>`,
        confirmButtonText: 'Done',
        confirmButtonColor: '#c9a24a',
        allowOutsideClick: false
      }).then(() => {
        window.location.reload();
      });

    } catch (err) {
      Swal.fire({
        icon: 'error',
        title: '<span style="font-family:\'Playfair Display\',serif;font-size:1.2rem;color:#1a1410">Submission Failed</span>',
        html: `
          <div style="font-family:Montserrat,sans-serif;font-size:.84rem;color:#6b5c40;line-height:1.7;">
            <p>Something went wrong while sending your form.</p>
            <p style="margin-top:.6rem;color:#9c3a1d;font-weight:600;font-size:.78rem;">${err.message}</p>
            <p style="margin-top:.6rem;">Your answers have been saved locally. Please try again or contact us directly.</p>
          </div>`,
        confirmButtonText: 'Try Again',
        confirmButtonColor: '#c9a24a'
      });
    }
  });


  showStep(0);
  </script>
  <?php endif; ?>
</main>

<?php include 'footer.php'; ?>

<script src="js/main.js"></script>

</body>

</html>
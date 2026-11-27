<?php
/**
 * Intake Print — TCPDF version
 * Phelonpreneur Network
 *
 * Requires: composer require tecnickcom/tcpdf
 */

require_once __DIR__ . '/vendor/autoload.php';

// ─── DB helpers ───────────────────────────────────────────────────────────────

function loadEnvFile() {
    $envPath = __DIR__ . '/.env';
    if (!file_exists($envPath)) throw new Exception('.env file not found.');
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        [$k, $v] = array_pad(explode('=', $line, 2), 2, null);
        if ($k && $v !== null) $_ENV[trim($k)] = trim($v);
    }
}

function createPDOConnection(): PDO {
    loadEnvFile();
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $db   = $_ENV['DB_NAME'] ?? '';
    $user = $_ENV['DB_USERNAME'] ?? '';
    $pass = $_ENV['DB_PASSWORD'] ?? '';
    if (!$db || !$user) throw new Exception('Database credentials are missing.');
    return new PDO("mysql:host={$host};dbname={$db};charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}

// ─── Fetch submission ─────────────────────────────────────────────────────────

$appointmentId = trim($_GET['appointmentId'] ?? '');
$submission    = null;
$payload       = [];
$fetchError    = null;

try {
    if (empty($appointmentId)) throw new Exception('Missing appointment ID.');
    $pdo  = createPDOConnection();
    $stmt = $pdo->prepare("
        SELECT * FROM readiness_intake_submissions
        WHERE JSON_UNQUOTE(JSON_EXTRACT(raw_payload, '$.appointmentId')) = :aid
        ORDER BY id DESC LIMIT 1
    ");
    $stmt->execute([':aid' => $appointmentId]);
    $submission = $stmt->fetch();
    if (!$submission) throw new Exception('No intake submission found for this appointment ID.');
    $payload = json_decode($submission['raw_payload'] ?? '{}', true);
    if (!is_array($payload)) $payload = [];
} catch (Exception $e) {
    $fetchError = $e->getMessage();
}

// ─── Value helpers ────────────────────────────────────────────────────────────

function val(array $payload, string $key): string {
    $v = $payload[$key] ?? '';
    if (is_array($v)) $v = implode(', ', array_filter($v));
    return trim((string) $v);
}

function esc(string $v): string {
    return htmlspecialchars($v, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function onePageText(string $value, int $limit = 95): string {
    $value = trim(preg_replace('/\s+/', ' ', $value));

    if (mb_strlen($value) <= $limit) {
        return $value;
    }

    return mb_substr($value, 0, $limit - 3) . '...';
}

// ─── Colours ──────────────────────────────────────────────────────────────────

define('C_DARK',   [14,  12,  8]);    // #0e0c08
define('C_GOLD',   [201, 162, 74]);   // #c9a24a
define('C_CREAM',  [240, 230, 204]);  // #f0e6cc
define('C_WHITE',  [255, 255, 255]);
define('C_OFFWH',  [255, 250, 240]);  // #fffaf0
define('C_RULE',   [232, 220, 180]);  // subtle gold rule
define('C_LBLTXT', [130, 110,  80]);  // muted label colour
define('C_BODY',   [26,  20,  16]);   // near-black body text
define('C_SECHD',  [40,  32,  20]);   // section header bg

// ─── TCPDF subclass — custom header/footer ────────────────────────────────────

class IntakePDF extends TCPDF {

    public string $apptId  = '';
    public string $subId   = '';
    public string $subDate = '';

    public function Header(): void {
        // Dark bar
        $this->SetFillColor(...C_DARK);
        $this->Rect(0, 0, $this->getPageWidth(), 22, 'F');

        // Gold accent line
        $this->SetFillColor(...C_GOLD);
        $this->Rect(0, 22, $this->getPageWidth(), 0.6, 'F');

        // Logo — fits within the 22mm dark bar, left-aligned, vertically centered
        $logoPath = __DIR__ . '/images/logo.png';
        if (file_exists($logoPath)) {
            $src = imagecreatefrompng($logoPath);
            $w   = imagesx($src);
            $h   = imagesy($src);

            $canvas = imagecreatetruecolor($w, $h);
            $bg     = imagecolorallocate($canvas, 14, 12, 8); // C_DARK
            imagefill($canvas, 0, 0, $bg);
            imagecopy($canvas, $src, 0, 0, 0, 0, $w, $h);

            $tmpLogo = sys_get_temp_dir() . '/intake_logo_flat_' . getmypid() . '.png';
            imagepng($canvas, $tmpLogo);
            imagedestroy($src);
            imagedestroy($canvas);

            // Logo height = 18mm (stays inside 22mm bar), Y=2 centers it vertically
            $logoH = 18;
            $this->Image($tmpLogo, 3, 2, 0, $logoH, 'PNG');
            @unlink($tmpLogo);
        }

        // Measure actual logo width so text starts after it
        // At 18mm tall with roughly square-ish logo, estimate ~22mm wide — use fixed offset
        $textStartX = 28; // mm from left — adjust up/down if logo is wider/narrower

        $pageW      = $this->getPageWidth();
        $metaW      = 65; // width reserved for right-side meta block
        $titleW     = $pageW - $textStartX - $metaW;

        // Title (center of remaining space)
        $this->SetTextColor(...C_GOLD);
        $this->SetFont('helvetica', 'B', 7);
        $this->SetXY($textStartX, 5);
        $this->Cell($titleW, 5, 'READINESS INTAKE — SUBMITTED DETAILS', 0, 0, 'C');

        $this->SetTextColor(...C_CREAM);
        $this->SetFont('helvetica', 'B', 11);
        $this->SetXY($textStartX, 11);
        $this->Cell($titleW, 6, 'Client Intake Summary', 0, 0, 'C');

        // Meta (right)
        $this->SetFont('helvetica', '', 6.5);
        $this->SetTextColor(...C_GOLD);
        $x = $pageW - $metaW;
        $this->SetXY($x, 4);
        $this->Cell($metaW - 2, 4, 'APPT: ' . esc($this->apptId), 0, 2, 'R');
        $this->SetXY($x, $this->GetY());
        $this->Cell($metaW - 2, 3.5, 'SUB: ' . esc($this->subId), 0, 2, 'R');
        $this->SetXY($x, $this->GetY());
        $this->Cell($metaW - 2, 3.5, esc($this->subDate), 0, 0, 'R');
    }

    public function Footer(): void {
        $this->SetY(-10);
        $this->SetFillColor(...C_OFFWH);
        $this->Rect(0, $this->getPageHeight() - 10, $this->getPageWidth(), 10, 'F');
        $this->SetFillColor(...C_RULE);
        $this->Rect(0, $this->getPageHeight() - 10, $this->getPageWidth(), 0.4, 'F');

        $this->SetFont('helvetica', '', 6.5);
        $this->SetTextColor(...C_LBLTXT);
        $this->Cell(0, 8,
            'The Phelonpreneur Network — Readiness support only. No legal advice provided.  |  Printed: ' . date('M j, Y g:i A'),
            0, 0, 'C'
        );
    }
}

// ─── Error page ───────────────────────────────────────────────────────────────

if ($fetchError) {
    $pdf = new IntakePDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
    $pdf->SetCreator('Phelonpreneur Network');
    $pdf->SetTitle('Intake Error');
    $pdf->SetMargins(14, 30, 14);
    $pdf->SetHeaderMargin(0);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(false, 0);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->SetTextColor(...C_BODY);
    $pdf->Cell(0, 10, 'Intake Not Found', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 6, esc($fetchError), 0, 'C');
    $pdf->Output('intake-error.pdf', 'I');
    exit;
}

// ─── Build PDF ────────────────────────────────────────────────────────────────

$pdf = new IntakePDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
$pdf->SetCreator('Phelonpreneur Network');
$pdf->SetAuthor('Phelonpreneur Network');
$pdf->SetTitle('Client Intake Summary — ' . $appointmentId);
$pdf->SetSubject('Readiness Intake');
$pdf->SetMargins(7, 25, 7);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(false, 0);
$pdf->apptId  = $appointmentId;
$pdf->subId   = (string)($submission['id'] ?? '');
$pdf->subDate = (string)($submission['created_at'] ?? '');
$pdf->AddPage();

// ─── Layout constants ─────────────────────────────────────────────────────────

$pageW   = $pdf->getPageWidth();
$margins = 14;
$usableW = $pageW - $margins;
$colW    = $usableW / 3; // three columns to fit one page

$lblH    = 2.8;
$valH    = 3.4;
$cellPad = 1.6;
$secHdrH = 4.4;

// ─── Drawing helpers ──────────────────────────────────────────────────────────

/**
 * Draw a section header bar then render fields in a two-column grid.
 * $fields: [ 'Label' => 'payload_key', ... ]
 * $fullWidth: keys that span both columns (pass as array of keys, default [])
 */
function drawSection(
    IntakePDF $pdf,
    string    $title,
    array     $fields,
    array     $payload,
    float     $colW,
    float     $secHdrH,
    float     $lblH,
    float     $valH,
    float     $cellPad,
    array     $fullWidthKeys = []
): void {

    // Collect non-empty fields only
    $rows = [];
    foreach ($fields as $label => $key) {
        $v = val($payload, $key);

        if ($v !== '') {
            $isFull = in_array($key, $fullWidthKeys, true);
            $v = onePageText($v, $isFull ? 150 : 80);

            $rows[] = [$label, $v, $isFull];
        }
    }
    if (empty($rows)) return;

    $x0 = $pdf->GetX();

    // ── Section header ────────────────────────────────────────────────────────
    $pdf->SetFillColor(...C_SECHD);
    $pdf->SetTextColor(...C_GOLD);
    $pdf->SetFont('helvetica', 'B', 7);
    $pdf->SetX($x0);
    $pdf->Cell($colW * 3, $secHdrH, strtoupper($title), 0, 1, 'P', true, '', 0, false, 'M', 'M');
    // Shift text left padding
    $pdf->SetX($x0);

    // ── Rows ──────────────────────────────────────────────────────────────────
    $col = 0; // 0 = left, 1 = right
    $leftX  = $x0;
    $midX   = $x0 + $colW;
    $rightX = $x0 + ($colW * 2);

    foreach ($rows as [$label, $value, $full]) {

        if ($full) {
            // If we're mid-row, flush the right column as blank then move down
            if ($col === 1) {
                // blank right cell already drawn by left cell below — just reset
                $col = 0;
            }
            $cellW = $colW * 2;
            $xPos  = $leftX;
        } else {
            $cellW = $colW;
            $xPos = $col === 0 ? $leftX : ($col === 1 ? $midX : $rightX);
        }

        // Background alternate
        $bg = ($col === 0 || $full) ? C_WHITE : C_OFFWH;
        $pdf->SetFillColor(...$bg);

        // Label
        $pdf->SetFont('helvetica', 'B', 6.5);
        $pdf->SetTextColor(...C_LBLTXT);
        $pdf->SetX($xPos + $cellPad);
        $pdf->Cell($cellW - $cellPad * 2, $lblH, strtoupper($label), 0, 0, 'P', false);

        // Value (may wrap — use MultiCell for long text)
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(...C_BODY);

        // Measure value height
        $lines    = $pdf->getNumLines($value, $cellW - $cellPad * 2, false, true, '', false);
        $thisValH = max($valH, $lines * 4.2);
        $cellTH   = $lblH + $thisValH;

        // Draw background rect
        $startY = $pdf->GetY();
        $pdf->SetFillColor(...$bg);
        $pdf->Rect($xPos, $startY, $cellW, $cellTH, 'F');

        // Re-draw label (rect overwrites it)
        $pdf->SetFont('helvetica', 'B', 6.5);
        $pdf->SetTextColor(...C_LBLTXT);
        $pdf->SetXY($xPos + $cellPad, $startY);
        $pdf->Cell($cellW - $cellPad * 2, $lblH, strtoupper($label), 0, 2, 'P');

        // Value
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(...C_BODY);
        $pdf->SetX($xPos + $cellPad);
        $pdf->MultiCell($cellW - $cellPad * 2, 4.2, $value, 0, 'P', false, 0);

        // Bottom rule
        $pdf->SetDrawColor(...C_RULE);
        $pdf->SetLineWidth(0.2);
        $bottomY = $startY + $cellTH;
        $pdf->Line($xPos, $bottomY, $xPos + $cellW, $bottomY);

        // Vertical divider between columns (not for full-width)
        if (!$full) {
            $pdf->Line($rightX, $startY, $rightX, $bottomY);
        }

        // Advance position
        if ($full) {
            $pdf->SetXY($leftX, $bottomY);
            $col = 0;
        } elseif ($col === 0) {
            $pdf->SetXY($midX, $startY);
            $col = 1;
        } elseif ($col === 1) {
            $pdf->SetXY($rightX, $startY);
            $col = 2;
        } else {
            $pdf->SetXY($leftX, $bottomY);
            $col = 0;
        }
    }

    // If last row left the right column empty, move down cleanly
    if ($col !== 0) {
        $pdf->SetXY($leftX, $pdf->GetY() + $valH + $lblH);
    }

    // Section bottom margin
    $pdf->Ln(3);
}

// ─── Score chips row ──────────────────────────────────────────────────────────

function drawScores(IntakePDF $pdf, array $payload, float $usableW): void {
    $scoreFields = [
        'Family'     => 'score_family',
        'Financial'  => 'score_financial',
        'Business'   => 'score_business',
        'Reputation' => 'score_reputation',
        'Prison'     => 'score_prison',
        'Reentry'    => 'score_reentry',
        'Overall'    => 'score_overall',
    ];

    $scores = [];
    foreach ($scoreFields as $lbl => $key) {
        $v = val($payload, $key);
        if ($v !== '') $scores[] = [$lbl, $v];
    }
    if (empty($scores)) return;

    // Section header
    $x0 = $pdf->GetX();
    $pdf->SetFillColor(...C_SECHD);
    $pdf->SetTextColor(...C_GOLD);
    $pdf->SetFont('helvetica', 'B', 7);
    $pdf->Cell($usableW, 5.5, 'READINESS SCORES', 0, 1, 'P', true);

    $chipW   = $usableW / count($scores);
    $chipH   = 12;
    $startY  = $pdf->GetY();

    foreach ($scores as $i => [$lbl, $score]) {
        $xPos = $x0 + $i * $chipW;

        // Chip background
        $pdf->SetFillColor(...C_OFFWH);
        $pdf->Rect($xPos, $startY, $chipW, $chipH, 'F');

        // Label
        $pdf->SetFont('helvetica', 'B', 6);
        $pdf->SetTextColor(...C_LBLTXT);
        $pdf->SetXY($xPos, $startY + 1.5);
        $pdf->Cell($chipW, 4, strtoupper($lbl), 0, 0, 'C');

        // Score value
        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->SetTextColor(...C_GOLD);
        $pdf->SetXY($xPos, $startY + 5.5);
        $pdf->Cell($chipW, 6, $score, 0, 0, 'C');

        // Divider between chips
        if ($i < count($scores) - 1) {
            $pdf->SetDrawColor(...C_RULE);
            $pdf->SetLineWidth(0.2);
            $pdf->Line($xPos + $chipW, $startY, $xPos + $chipW, $startY + $chipH);
        }
    }

    // Bottom rule
    $pdf->SetDrawColor(...C_RULE);
    $pdf->Line($x0, $startY + $chipH, $x0 + $usableW, $startY + $chipH);
    $pdf->SetXY($x0, $startY + $chipH);
    $pdf->Ln(3);
}

// ─── Render sections ──────────────────────────────────────────────────────────

$x0 = $pdf->GetX();

// Reference bar (inline, compact)
$pdf->SetFillColor(...C_OFFWH);
$pdf->SetDrawColor(...C_RULE);
$pdf->SetLineWidth(0.2);
$pdf->SetFont('helvetica', '', 7);
$pdf->SetTextColor(...C_LBLTXT);
$metaH = 6;
$pdf->SetFillColor(...C_OFFWH);
$pdf->Rect($x0, $pdf->GetY(), $usableW, $metaH, 'F');
$pdf->SetXY($x0 + 2, $pdf->GetY() + 1);

$refParts = array_filter([
    'Submission ID: ' . ($submission['id'] ?? ''),
    'Appointment ID: ' . $appointmentId,
    'Submitted: ' . ($submission['created_at'] ?? ''),
]);
$pdf->Cell($usableW - 4, 4, implode('   |   ', $refParts), 0, 1, 'P');
$pdf->Ln(2);

// ── Contact
drawSection($pdf, 'Contact Information', [
    'First Name'      => 'first_name',
    'Last Name'       => 'last_name',
    'Email'           => 'email',
    'Phone'           => 'phone',
    'Best Phone'      => 'best_phone',
    'Alternate Phone' => 'alternate_phone',
    'Preferred Name'  => 'preferred_name',
    'State'           => 'state',
    'City'            => 'city',
    'Timezone'        => 'timezone',
    'Best Time'       => 'best_time',
    'Text Consent'    => 'text_consent',
], $payload, $colW, $secHdrH, $lblH, $valH, $cellPad);

// ── Consultation Purpose
drawSection($pdf, 'Consultation Purpose', [
    'Consultation For'      => 'consultation_for',
    'Directly Impacted'     => 'directly_impacted',
    'Decision Maker'        => 'decision_maker',
    'Other Decision Person' => 'other_decision_person',
], $payload, $colW, $secHdrH, $lblH, $valH, $cellPad);

// ── Situation & Urgency
drawSection($pdf, 'Current Situation & Urgency', [
    'Current Stage' => 'current_stage',
    'Urgency'       => 'urgency',
], $payload, $colW, $secHdrH, $lblH, $valH, $cellPad);

// ── Important Dates
drawSection($pdf, 'Important Dates', [
    'Has Important Date'  => 'has_important_date',
    'Important Date'      => 'important_date',
    'Deadline Type'       => 'deadline_type',
    'Date Readiness Need' => 'date_readiness_need',
], $payload, $colW, $secHdrH, $lblH, $valH, $cellPad);

// ── Areas of Concern
drawSection($pdf, 'Areas of Concern', [
    'Concerns'             => 'concerns',
    'Biggest Pressure'     => 'biggest_pressure',
    'Helpful Consultation' => 'helpful_consultation',
], $payload, $colW, $secHdrH, $lblH, $valH, $cellPad, ['concerns', 'biggest_pressure', 'helpful_consultation']);

// ── Attorney
drawSection($pdf, 'Attorney & Professional Support', [
    'Has Attorney'               => 'has_attorney',
    'Attorney Name'              => 'attorney_name',
    'Attorney Firm'              => 'attorney_firm',
    'Attorney Phone'             => 'attorney_phone',
    'Attorney Email'             => 'attorney_email',
    'Avoid Professional Contact' => 'avoid_professional_contact',
], $payload, $colW, $secHdrH, $lblH, $valH, $cellPad);

// ── Impact Details
drawSection($pdf, 'Impact Details', [
    'Family Affected'             => 'family_affected',
    'Family Details'              => 'family_affected_details',
    'Financial Affected'          => 'financial_affected',
    'Financial Details'           => 'financial_affected_details',
    'Credit Affected'             => 'credit_affected',
    'Credit Details'              => 'credit_affected_details',
    'Business Affected'           => 'business_affected',
    'Business Details'            => 'business_affected_details',
    'Reputation Affected'         => 'reputation_affected',
    'Reputation Details'          => 'reputation_affected_details',
    'Prison Affected'             => 'prison_affected',
    'Prison Details'              => 'prison_affected_details',
    'Productive Time Affected'    => 'productive_time_affected',
    'Productive Time Details'     => 'productive_time_affected_details',
    'Reentry Affected'            => 'reentry_affected',
    'Reentry Details'             => 'reentry_affected_details',
], $payload, $colW, $secHdrH, $lblH, $valH, $cellPad, [
    'family_affected_details','financial_affected_details','credit_affected_details',
    'business_affected_details','reputation_affected_details','prison_affected_details',
    'productive_time_affected_details','reentry_affected_details',
]);

// ── Goals
drawSection($pdf, 'Consultation Goals', [
    'Goals'                   => 'consultation_goals',
    'Successful Consultation' => 'successful_consultation',
], $payload, $colW, $secHdrH, $lblH, $valH, $cellPad, ['consultation_goals','successful_consultation']);

// ── Scores
drawScores($pdf, $payload, $usableW);

// ─── Output ───────────────────────────────────────────────────────────────────

$pdf->Output('intake-summary-' . preg_replace('/[^a-z0-9]/i', '-', $appointmentId) . '.pdf', 'I');
$ErrorActionPreference = 'Stop'
$projectPath = (Resolve-Path -LiteralPath (Join-Path $PSScriptRoot '..\..')).Path
$legacyPath = Join-Path $projectPath 'backend\legacy'
$portalPath = Join-Path $projectPath 'Login'
$portalTemp = Join-Path $projectPath 'phelon-portal-casing-temp'
$secretTarget = Join-Path $projectPath 'backend\.env'

function Assert-ProjectLocation([string]$candidate) {
    $absolute = [System.IO.Path]::GetFullPath($candidate)
    $prefix = $projectPath.TrimEnd('\') + '\'
    if (-not $absolute.StartsWith($prefix, [StringComparison]::OrdinalIgnoreCase)) {
        throw "A migration path is outside the project."
    }
    if (Test-Path -LiteralPath $absolute) {
        $entry = Get-Item -LiteralPath $absolute -Force
        if (($entry.Attributes -band [System.IO.FileAttributes]::ReparsePoint) -ne 0) {
            throw "Migration refuses to follow a symlink or junction."
        }
    }
}

if (-not (Test-Path -LiteralPath (Join-Path $projectPath 'backend\artisan')) -or
    -not (Test-Path -LiteralPath (Join-Path $projectPath 'frontend\src\main.jsx'))) {
    throw 'The replacement applications are not present.'
}
foreach ($path in @($legacyPath, $portalPath, $portalTemp, $secretTarget)) {
    Assert-ProjectLocation $path
}
if ((Test-Path -LiteralPath $portalTemp) -or (Test-Path -LiteralPath $secretTarget)) {
    throw 'A destination already exists. Inspect the workspace before continuing.'
}
New-Item -ItemType Directory -Path (Join-Path $legacyPath 'Login') -Force | Out-Null

$legacyEntries = @('api','app','components','css','database','docs','Documents','images','js','scripts','tests','uploads','vendor','webinar',
    'About.php','Book.php','composer.json','composer.lock','Contact.php','Cookie-Policy.php','create_database.php','create_table.php',
    'Disclaimer.php','env.php','footer.php','index.php','intake-print.php','intake-print.phpold','Intake.php','nav.php','plan.md',
    'Privacy-Policy.php','README.md','Service.php','Services.php','start-here.php','StartHere.php','Terms-of-Service.php','test_email.php',
    'Thanks.php','ThankYou.php','webinar_index.php')
foreach ($entry in $legacyEntries) {
    $source = Join-Path $projectPath $entry
    $destination = Join-Path $legacyPath $entry
    Assert-ProjectLocation $source
    Assert-ProjectLocation $destination
    if (Test-Path -LiteralPath $source) {
        if (Test-Path -LiteralPath $destination) { throw "Archive conflict: $entry" }
        Move-Item -LiteralPath $source -Destination $destination
    }
}
foreach ($entry in @('backend','frontend','index.php')) {
    $source = Join-Path $portalPath $entry
    $destination = Join-Path (Join-Path $legacyPath 'Login') $entry
    Assert-ProjectLocation $source
    Assert-ProjectLocation $destination
    if (Test-Path -LiteralPath $source) {
        if (Test-Path -LiteralPath $destination) { throw "Staff archive conflict: $entry" }
        Move-Item -LiteralPath $source -Destination $destination
    }
}
Copy-Item -LiteralPath (Join-Path $projectPath '.htaccess') -Destination (Join-Path $legacyPath 'root.htaccess.txt')
$oldEnv = Join-Path $projectPath '.env'
Assert-ProjectLocation $oldEnv
if (Test-Path -LiteralPath $oldEnv) {
    Move-Item -LiteralPath $oldEnv -Destination $secretTarget
}
Rename-Item -LiteralPath $portalPath -NewName (Split-Path -Leaf $portalTemp)
Rename-Item -LiteralPath $portalTemp -NewName 'login'

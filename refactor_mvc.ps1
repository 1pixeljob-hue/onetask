# Create directories
$dirs = @(
    "app/Controllers", "app/Models", "app/Views", "app/Core", "app/Helpers",
    "config", "routes", "storage/logs", "storage/cache", "docs/modules"
)
foreach ($d in $dirs) {
    if (!(Test-Path $d)) { New-Item -ItemType Directory -Path $d -Force | Out-Null }
}

# Rename assets to public_html
if (Test-Path "assets") {
    Rename-Item -Path "assets" -NewName "public_html"
}
if (!(Test-Path "public_html/js")) { New-Item -ItemType Directory -Path "public_html/js" -Force | Out-Null }
if (!(Test-Path "public_html/images")) { New-Item -ItemType Directory -Path "public_html/images" -Force | Out-Null }
if (!(Test-Path "public_html/uploads")) { New-Item -ItemType Directory -Path "public_html/uploads" -Force | Out-Null }

# Move HTML files to app/Views/ and rename to .php
$htmlFiles = Get-ChildItem -Path . -Filter "*.html"
foreach ($f in $htmlFiles) {
    $newName = $f.Name -replace '\.html$', '.php'
    Move-Item -Path $f.FullName -Destination "app/Views/$newName" -Force
}

# Update content in app/Views/*.php
$phpFiles = Get-ChildItem -Path "app/Views" -Filter "*.php"

$replacements = @{
    'href="assets/css/style.css"' = 'href="/css/style.css"';
    'href="index.html"' = 'href="/"';
    'href="hostings.html"' = 'href="/hostings"';
    'href="projects.html"' = 'href="/projects"';
    'href="reports.html"' = 'href="/reports"';
    'href="passwords.html"' = 'href="/passwords"';
    'href="codex.html"' = 'href="/codex"';
    'href="logs.html"' = 'href="/logs"';
    'href="settings.html"' = 'href="/settings"'
}

foreach ($f in $phpFiles) {
    $content = [System.IO.File]::ReadAllText($f.FullName, [System.Text.Encoding]::UTF8)
    foreach ($key in $replacements.Keys) {
        $content = $content.Replace($key, $replacements[$key])
    }
    [System.IO.File]::WriteAllText($f.FullName, $content, [System.Text.Encoding]::UTF8)
}

Write-Host "File structure and links updated successfully."

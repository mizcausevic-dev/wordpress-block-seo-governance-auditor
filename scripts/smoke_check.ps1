$ErrorActionPreference = "Stop"

$repoRoot = Split-Path -Parent $PSScriptRoot
$router = Join-Path $repoRoot "router.php"
$stdout = Join-Path $repoRoot "screenshots\app.stdout.log"
$stderr = Join-Path $repoRoot "screenshots\app.stderr.log"
$port = 5172
$process = $null

function Wait-ForUrl {
    param([string]$Url)
    for ($i = 0; $i -lt 40; $i++) {
        try {
            Invoke-WebRequest -Uri $Url -UseBasicParsing | Out-Null
            return
        } catch {
            Start-Sleep -Milliseconds 750
        }
    }

    throw "Timed out waiting for $Url"
}

try {
    $process = Start-Process -FilePath "php.exe" `
        -ArgumentList "-S", "127.0.0.1:$port", $router `
        -WorkingDirectory $repoRoot `
        -RedirectStandardOutput $stdout `
        -RedirectStandardError $stderr `
        -PassThru

    Wait-ForUrl "http://127.0.0.1:$port/"

    $routes = @(
        "/",
        "/block-audit",
        "/schema-opportunities",
        "/verification",
        "/docs",
        "/api/summary"
    )

    foreach ($route in $routes) {
        $response = Invoke-WebRequest -Uri "http://127.0.0.1:$port$route" -UseBasicParsing
        if ($response.StatusCode -ne 200) {
            throw "Smoke check failed for $route with $($response.StatusCode)"
        }
    }

    Write-Output "Smoke checks passed for WordPress Block SEO Governance Auditor routes."
} finally {
    if ($process -and -not $process.HasExited) {
        Stop-Process -Id $process.Id -Force
    }
}

$ErrorActionPreference = 'Stop'
Set-Location (Split-Path $PSScriptRoot -Parent)
function Run-Php([string[]]$Arguments) {
    & $script:php @Arguments
    if ($LASTEXITCODE -ne 0) { throw "PHP command failed. Check the error above and run START.cmd again." }
}
try {
    $roots = @($env:XAMPP_HOME, 'C:\php82', 'C:\xampp', 'C:\xamp', 'D:\xampp', 'D:\xamp')
    $php = $null
    foreach ($root in $roots) {
        if ($root -and (Test-Path "$root\php\php.exe")) { $php = "$root\php\php.exe"; break }
    }
    if (!$php) { $php = (Get-Command php.exe -ErrorAction SilentlyContinue).Source }
    if (!$php) { throw 'PHP missing. Set XAMPP_HOME to your XAMPP folder and reopen VS Code.' }
    $env:Path = "$(Split-Path $php);$env:Path"
    # Load optional XAMPP modules for this launcher without changing the system php.ini.
    $phpDir = Split-Path $php
    $modules = @(& $php -m)
    $extra = @()
    foreach ($module in @('zip','mbstring','pdo_mysql','openssl','fileinfo','curl')) {
        if (($modules -notcontains $module) -and (Test-Path "$phpDir\ext\php_$module.dll")) {
            $extra += "extension=`"$phpDir\ext\php_$module.dll`""
        }
    }
    $scanDir = Join-Path $PSScriptRoot 'php-conf'
    New-Item $scanDir -ItemType Directory -Force | Out-Null
    Set-Content (Join-Path $scanDir 'extensions.ini') -Value ($extra -join [Environment]::NewLine) -Encoding ASCII
    if ($env:PHP_INI_SCAN_DIR) { $env:PHP_INI_SCAN_DIR += ";$scanDir" }
    else { $env:PHP_INI_SCAN_DIR = $scanDir }
    Run-Php @('-r','exit(PHP_VERSION_ID >= 80200 && extension_loaded(''pdo_mysql'') ? 0 : 1);')
    if (!(Test-Path .env)) { Copy-Item .env.example .env }
    if (!(Test-Path 'setup\.installed') -or !(Test-Path 'vendor\autoload.php')) {
        $composer = Get-Command composer -ErrorAction SilentlyContinue
        if ($composer) {
            & $composer.Source install --no-dev --prefer-dist --no-interaction
            if ($LASTEXITCODE -ne 0) { throw 'Composer install failed. Check Internet and required PHP extensions in php.ini.' }
        } else {
            $phar = 'setup\composer.phar'
            if (!(Test-Path $phar)) {
                [Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
                $installer = 'setup\composer-setup.php'
                Invoke-WebRequest 'https://getcomposer.org/installer' -UseBasicParsing -OutFile $installer
                $expected = (Invoke-RestMethod 'https://composer.github.io/installer.sig').Trim()
                if ((Get-FileHash $installer -Algorithm SHA384).Hash -ne $expected) { throw 'Composer installer signature mismatch.' }
                Run-Php @($installer, '--install-dir=setup', '--filename=composer.phar')
                Remove-Item $installer
            }
            Run-Php @($phar,'install','--no-dev','--prefer-dist','--no-interaction')
        }
        New-Item 'setup\.installed' -ItemType File -Force | Out-Null
    }
    if (!(Select-String -Path .env -Pattern '^APP_KEY=.+$' -Quiet)) { Run-Php @('artisan','key:generate','--force') }
    Run-Php @('setup/database.php')
    Run-Php @('artisan','migrate','--force')
    $port = [Net.Sockets.TcpListener]::new([Net.IPAddress]::Loopback,8000)
    try { $port.Start() } catch { throw 'Port 8000 is busy. Close the older API server, then relaunch.' } finally { $port.Stop() }
    Write-Host 'Ready. Open http://127.0.0.1:8000/api/students or run the Postman collection.'
    Run-Php @('artisan','serve','--host=127.0.0.1','--port=8000','--tries=1')
} catch {
    Write-Host $_ -ForegroundColor Red
    Write-Host 'Use XAMPP PHP 8.2+, start MySQL, and check DB settings in .env.'
    exit 1
}

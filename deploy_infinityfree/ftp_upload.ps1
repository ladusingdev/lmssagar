# ============================================================
# FTP Upload Script untuk Deploy LMS ke InfinityFree
# ============================================================
# CARA PAKAI:
#   1. Buka PowerShell sebagai Administrator
#   2. Jalankan: .\ftp_upload.ps1
#   3. Masukkan FTP credentials saat diminta
#   4. Tunggu proses upload selesai (bisa 15-30 menit)
# ============================================================

$ErrorActionPreference = "Stop"

# --- Konfigurasi ---
$SourceDir = Join-Path $PSScriptRoot "htdocs"
$FtpHost   = Read-Host -Prompt "Masukkan FTP Host (contoh: ftpupload.infinityfree.com)"
$FtpUser   = Read-Host -Prompt "Masukkan FTP Username"
$FtpPass   = Read-Host -Prompt "Masukkan FTP Password" -AsSecureString
$FtpPassPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto(
    [Runtime.InteropServices.Marshal]::SecureStringToBSTR($FtpPass)
)

# --- Validasi ---
if (-not (Test-Path $SourceDir)) {
    Write-Host "[ERROR] Folder 'htdocs' tidak ditemukan di $PSScriptRoot" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "  FTP Upload ke InfinityFree" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "Host   : $FtpHost"
Write-Host "User   : $FtpUser"
Write-Host "Source : $SourceDir"
Write-Host ""

# --- Hitung total file ---
$allFiles = Get-ChildItem -Path $SourceDir -Recurse -File
$totalFiles = $allFiles.Count
$totalSizeMB = [math]::Round(($allFiles | Measure-Object -Property Length -Sum).Sum / 1MB, 2)
Write-Host "Total: $totalFiles file ($totalSizeMB MB)" -ForegroundColor Yellow
Write-Host ""

# --- Fungsi FTP Upload ---
function Upload-FtpFile {
    param(
        [string]$LocalPath,
        [string]$RemotePath
    )
    
    $uri = New-Object System.Uri("ftp://$FtpHost$RemotePath")
    $ftpRequest = [System.Net.FtpWebRequest]::Create($uri)
    $ftpRequest.Method = [System.Net.WebRequestMethods+Ftp]::UploadFile
    $ftpRequest.Credentials = New-Object System.Net.NetworkCredential($FtpUser, $FtpPassPlain)
    $ftpRequest.UseBinary = $true
    $ftpRequest.UsePassive = $true
    $ftpRequest.Timeout = 60000
    $ftpRequest.ReadWriteTimeout = 60000
    
    $fileContent = [System.IO.File]::ReadAllBytes($LocalPath)
    $ftpRequest.ContentLength = $fileContent.Length
    
    $requestStream = $ftpRequest.GetRequestStream()
    $requestStream.Write($fileContent, 0, $fileContent.Length)
    $requestStream.Close()
    $requestStream.Dispose()
    
    # Verify upload
    try {
        $checkRequest = [System.Net.FtpWebRequest]::Create($uri)
        $checkRequest.Method = [System.Net.WebRequestMethods+Ftp]::GetFileSize
        $checkRequest.Credentials = New-Object System.Net.NetworkCredential($FtpUser, $FtpPassPlain)
        $checkResponse = $checkRequest.GetResponse()
        $remoteSize = $checkResponse.ContentLength
        $checkResponse.Close()
        
        if ($remoteSize -ne $fileContent.Length) {
            throw "Size mismatch: local=$($fileContent.Length) remote=$remoteSize"
        }
    } catch {
        # Some servers don't support GetFileSize, skip verification
    }
}

function Create-FtpDirectory {
    param([string]$RemotePath)
    
    $uri = New-Object System.Uri("ftp://$FtpHost$RemotePath")
    try {
        $ftpRequest = [System.Net.FtpWebRequest]::Create($uri)
        $ftpRequest.Method = [System.Net.WebRequestMethods+Ftp]::MakeDirectory
        $ftpRequest.Credentials = New-Object System.Net.NetworkCredential($FtpUser, $FtpPassPlain)
        $ftpRequest.UsePassive = $true
        $ftpRequest.Timeout = 30000
        $response = $ftpRequest.GetResponse()
        $response.Close()
    } catch {
        # Directory might already exist, that's OK
    }
}

# --- Mulai Upload ---
$uploaded = 0
$failed = 0
$startTime = Get-Date
$createdDirs = @{}

Write-Host "Mulai upload..." -ForegroundColor Green
Write-Host ""

foreach ($file in $allFiles) {
    $relativePath = $file.FullName.Substring($SourceDir.Length).Replace('\', '/')
    $remotePath = $relativePath
    
    # Get parent directories
    $parentDir = Split-Path $relativePath -Parent
    if ($parentDir -and -not $createdDirs.ContainsKey($parentDir)) {
        $segments = $parentDir.Split('/')
        $currentPath = ""
        foreach ($segment in $segments) {
            if ($segment) {
                $currentPath = "$currentPath/$segment"
                if (-not $createdDirs.ContainsKey($currentPath)) {
                    Create-FtpDirectory -RemotePath $currentPath
                    $createdDirs[$currentPath] = $true
                }
            }
        }
    }
    
    $uploaded++
    $percent = [math]::Round(($uploaded / $totalFiles) * 100)
    $elapsed = (Get-Date) - $startTime
    $speed = if ($elapsed.TotalSeconds -gt 0) { [math]::Round($uploaded / $elapsed.TotalSeconds, 1) } else { 0 }
    
    Write-Host "`r[$uploaded/$totalFiles] $percent% - $speed file/s - $($file.Name)" -NoNewline
    
    try {
        Upload-FtpFile -LocalPath $file.FullName -RemotePath $remotePath
    } catch {
        $failed++
        Write-Host ""
        Write-Host "[FAIL] $relativePath : $($_.Exception.Message)" -ForegroundColor Red
    }
}

# --- Selesai ---
$elapsed = (Get-Date) - $startTime
Write-Host ""
Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "  Upload Selesai!" -ForegroundColor Green
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "Berhasil : $($uploaded - $failed) file"
Write-Host "Gagal    : $failed file"
Write-Host "Waktu    : $([math]::Round($elapsed.TotalMinutes, 1)) menit"
Write-Host ""

if ($failed -gt 0) {
    Write-Host "[WARNING] Ada $failed file yang gagal di-upload. Jalankan ulang script untuk retry." -ForegroundColor Yellow
}

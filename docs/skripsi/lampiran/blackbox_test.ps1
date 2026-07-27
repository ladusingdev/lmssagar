$base = "http://localhost/LMS9/public"
$results = @()

function Get-CsrfToken($html) {
    if ($html -match 'name="_token" value="([^"]+)"') { return $matches[1] }
    return $null
}

# TC-01: Akses halaman terproteksi tanpa login -> harus redirect ke login
$sessionGuest = $null
try {
    $r = Invoke-WebRequest -Uri "$base/admin/teachers" -UseBasicParsing -SessionVariable sessionGuest -MaximumRedirection 5 -TimeoutSec 15
    $status = $r.StatusCode
    $onLogin = $r.Content -match "Login - LMS"
    $results += [PSCustomObject]@{ID="TC-01";Desc="Akses /admin/teachers tanpa login";Expected="Redirect ke halaman login";Actual="Status $status, halaman login tampil: $onLogin";Pass=$onLogin}
} catch { $results += [PSCustomObject]@{ID="TC-01";Desc="Akses /admin/teachers tanpa login";Expected="Redirect ke halaman login";Actual="ERROR: $($_.Exception.Message)";Pass=$false} }

# Fungsi login helper
function Try-Login($email, $password, $label) {
    $sess = $null
    $loginPage = Invoke-WebRequest -Uri "$base/login" -UseBasicParsing -SessionVariable sess -TimeoutSec 15
    $token = Get-CsrfToken $loginPage.Content
    $body = @{ _token = $token; email = $email; password = $password }
    $resp = Invoke-WebRequest -Uri "$base/login" -Method POST -Body $body -WebSession $sess -UseBasicParsing -MaximumRedirection 0 -ErrorAction SilentlyContinue -TimeoutSec 15
    return @{ Response = $resp; Session = $sess }
}

# TC-02..04: Login sukses per role
$roles = @(
    @{Email="admin@smkn9garut.sch.id"; Label="Admin"; ExpectPath="/admin"},
    @{Email="guru1@smkn9garut.sch.id"; Label="Guru"; ExpectPath="/guru"},
    @{Email="siswa1@smkn9garut.sch.id"; Label="Siswa"; ExpectPath="/siswa"}
)
$idx = 2
$adminSession = $null
foreach ($r in $roles) {
    $res = Try-Login $r.Email "password" $r.Label
    $status = $res.Response.StatusCode
    $location = $res.Response.Headers.Location
    # Login redirects to /dashboard first; follow it to get the role-specific final redirect
    $final = Invoke-WebRequest -Uri $location -WebSession $res.Session -UseBasicParsing -MaximumRedirection 0 -ErrorAction SilentlyContinue -TimeoutSec 15
    $finalLocation = $final.Headers.Location
    $pass = ($finalLocation -match $r.ExpectPath)
    $results += [PSCustomObject]@{ID="TC-0$idx";Desc="Login sukses sebagai $($r.Label) ($($r.Email))";Expected="Redirect akhir ke dashboard mengandung '$($r.ExpectPath)'";Actual="Login->$location ; Dashboard->$finalLocation";Pass=$pass}
    if ($r.Label -eq "Admin") { $adminSession = $res.Session }
    $idx++
}

# TC-05: Login gagal (password salah)
$loginPage = Invoke-WebRequest -Uri "$base/login" -UseBasicParsing -SessionVariable failSess -TimeoutSec 15
$token = Get-CsrfToken $loginPage.Content
$body = @{ _token = $token; email = "admin@smkn9garut.sch.id"; password = "password-salah" }
$resp = Invoke-WebRequest -Uri "$base/login" -Method POST -Body $body -WebSession $failSess -UseBasicParsing -MaximumRedirection 0 -ErrorAction SilentlyContinue -TimeoutSec 15
$status = $resp.StatusCode
$location = $resp.Headers.Location
$pass = ($status -eq 302) -and ($location -notmatch "/admin|/guru|/siswa")
$results += [PSCustomObject]@{ID="TC-05";Desc="Login dengan password salah";Expected="Gagal login, redirect kembali ke /login dengan pesan error";Actual="Status $status, Location: $location";Pass=$pass}

# TC-06: Verifikasi perbaikan bug hapus guru yang masih punya mata pelajaran (guru id 3)
if ($adminSession) {
    $teacherPage = Invoke-WebRequest -Uri "$base/admin/teachers" -WebSession $adminSession -UseBasicParsing -TimeoutSec 15
    $token2 = Get-CsrfToken $teacherPage.Content
    $body2 = @{ _token = $token2; _method = "DELETE" }
    $resp2 = Invoke-WebRequest -Uri "$base/admin/teachers/3" -Method POST -Body $body2 -WebSession $adminSession -UseBasicParsing -MaximumRedirection 0 -ErrorAction SilentlyContinue -TimeoutSec 15
    $status2 = $resp2.StatusCode
    $pass2 = ($status2 -eq 302)
    $results += [PSCustomObject]@{ID="TC-06";Desc="Hapus guru (id=3) yang masih mengampu mata pelajaran";Expected="Ditolak dengan pesan error (bukan HTTP 500)";Actual="Status $status2 (302=redirect aman, bukan crash)";Pass=$pass2}

    # follow redirect to capture flash message
    $follow = Invoke-WebRequest -Uri "$base/admin/teachers" -WebSession $adminSession -UseBasicParsing -TimeoutSec 15
    $hasErrorMsg = $follow.Content -match "tidak dapat dihapus karena masih mengampu mata pelajaran"
    $results += [PSCustomObject]@{ID="TC-06b";Desc="Pesan flash error tampil setelah percobaan hapus guru id=3";Expected="Pesan 'Guru tidak dapat dihapus...' tampil di halaman";Actual="Pesan ditemukan: $hasErrorMsg";Pass=$hasErrorMsg}
} else {
    $results += [PSCustomObject]@{ID="TC-06";Desc="Hapus guru id=3 (butuh sesi admin)";Expected="-";Actual="Login admin gagal, test dilewati";Pass=$false}
}

# TC-07: Logout
if ($adminSession) {
    $dash = Invoke-WebRequest -Uri "$base/admin/teachers" -WebSession $adminSession -UseBasicParsing -TimeoutSec 15
    $tokenL = Get-CsrfToken $dash.Content
    $bodyL = @{ _token = $tokenL }
    $respL = Invoke-WebRequest -Uri "$base/logout" -Method POST -Body $bodyL -WebSession $adminSession -UseBasicParsing -MaximumRedirection 0 -ErrorAction SilentlyContinue -TimeoutSec 15
    $statusL = $respL.StatusCode
    $results += [PSCustomObject]@{ID="TC-07";Desc="Logout admin";Expected="Redirect ke halaman utama/login setelah logout";Actual="Status $statusL";Pass=($statusL -eq 302)}
}

$results | Format-Table -AutoSize -Wrap
$results | ConvertTo-Json | Out-File -Encoding utf8 "blackbox_results.json"

# --- TC-08: Konstrain unik penugasan mengajar (dijalankan terpisah via MySQL CLI, lihat LAMPIRAN.md) ---
# --- TC-09 & TC-10: Verifikasi konsistensi data (dijalankan terpisah via MySQL CLI, lihat LAMPIRAN.md) ---

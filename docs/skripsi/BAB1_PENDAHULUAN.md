# BAB I PENDAHULUAN

## 1.1 Latar Belakang

Perkembangan teknologi informasi dan komunikasi telah mendorong hampir seluruh sektor kehidupan, termasuk sektor pendidikan, untuk bertransformasi ke arah digital. Proses pembelajaran yang sebelumnya bergantung sepenuhnya pada interaksi tatap muka dan media fisik kini semakin banyak diintegrasikan dengan platform digital yang memungkinkan distribusi materi, pemberian tugas, pelaksanaan ujian, hingga rekapitulasi nilai dilakukan secara terpusat dan dapat diakses kapan saja. Platform semacam ini secara umum dikenal dengan istilah *Learning Management System* (LMS), yaitu sebuah sistem perangkat lunak yang dirancang untuk merencanakan, melaksanakan, dan menilai proses pembelajaran secara elektronik.

SMK Negeri 9 Garut, sebagaimana sekolah menengah kejuruan pada umumnya, menyelenggarakan proses akademik yang melibatkan tiga peran utama, yaitu Administrator, Guru, dan Siswa. Berdasarkan pengamatan awal terhadap proses bisnis yang berjalan, ditemukan bahwa sebagian besar aktivitas akademik masih dilakukan secara manual atau semi-manual, di antaranya:

1. Distribusi materi pembelajaran yang masih mengandalkan media fisik (fotokopi) atau pengiriman berkas melalui aplikasi perpesanan pribadi yang tidak terdokumentasi secara terpusat.
2. Presensi siswa yang dicatat menggunakan kertas, sehingga rentan terhadap kehilangan data dan menyulitkan proses rekapitulasi kehadiran per semester.
3. Pengumpulan dan penilaian tugas yang dilakukan secara langsung (luring) tanpa jejak digital, sehingga sulit ditelusuri riwayatnya.
4. Pelaksanaan ujian yang sepenuhnya berbasis kertas, sehingga proses koreksi soal pilihan ganda memakan waktu dan berpotensi menimbulkan kesalahan penjumlahan skor manual.
5. Rekapitulasi nilai akhir yang dilakukan menggunakan berkas spreadsheet terpisah oleh masing-masing guru, sehingga tidak ada satu sumber data (*single source of truth*) yang dapat diakses secara real-time oleh siswa maupun pihak administrasi sekolah.

Permasalahan-permasalahan di atas berdampak pada efisiensi waktu guru dan staf administrasi, transparansi informasi akademik bagi siswa, serta akurasi data yang dibutuhkan untuk kepentingan pelaporan dan akreditasi sekolah. Kondisi ini menunjukkan adanya kebutuhan nyata (*needs*) akan sebuah sistem informasi akademik terpadu yang mampu mengakomodasi seluruh proses belajar-mengajar dalam satu platform berbasis web, dapat diakses oleh ketiga peran (Administrator, Guru, Siswa) sesuai kewenangannya masing-masing, serta tidak bergantung pada infrastruktur jaringan internet yang mahal atau kompleks — mengingat keterbatasan infrastruktur TI yang umum dihadapi sekolah negeri di daerah.

> ⚠️ **TODO (Anda lengkapi):** Paragraf di atas menggambarkan kondisi umum yang lazim ditemukan di sekolah dan konsisten dengan `docs/ANALISIS_KEBUTUHAN.md`. Jika Anda melakukan observasi/wawancara langsung ke SMKN 9 Garut, perkuat paragraf ini dengan data primer: tanggal observasi, nama dan jabatan narasumber yang diwawancarai, serta kutipan singkat hasil wawancara (mis. "Menurut Bapak/Ibu [nama], [jabatan], proses presensi manual menyebabkan ..."). Ini akan membuat latar belakang lebih kuat secara akademik karena berbasis bukti lapangan, bukan asumsi umum.

Berdasarkan permasalahan tersebut, penelitian ini mengembangkan sebuah **Learning Management System (LMS) berbasis web** yang menghubungkan tiga peran pengguna — Administrator, Guru, dan Siswa — dalam satu sistem terpadu untuk manajemen data akademik, distribusi materi pembelajaran, pengelolaan tugas, kuis, ujian daring dengan penilaian otomatis, presensi, nilai, jadwal, hingga komunikasi sekolah melalui pengumuman dan forum diskusi. Sistem dikembangkan menggunakan **framework Laravel 12** berbasis bahasa pemrograman PHP dan basis data MySQL, dengan pendekatan penelitian **Design and Development Research (DDR)** sebagaimana dikemukakan oleh Richey & Klein (2007), yang menekankan proses sistematis mulai dari analisis kebutuhan, perancangan, pengembangan, hingga evaluasi produk sebagai satu siklus penelitian yang terdokumentasi dan dapat dipertanggungjawabkan secara ilmiah.

Pemilihan metode DDR didasarkan pada karakteristik penelitian ini yang tidak sekadar membangun perangkat lunak (rekayasa perangkat lunak murni), melainkan juga bertujuan menghasilkan sebuah **produk pendidikan (educational product)** yang divalidasi kelayakannya melalui pengujian fungsional (black-box testing) dan evaluasi penerimaan pengguna (user acceptance) oleh calon pengguna aktual, yaitu administrator, guru, dan siswa SMKN 9 Garut, sehingga hasil akhir penelitian tidak hanya berupa sistem yang berjalan (*working system*), tetapi juga bukti empiris mengenai kelayakan dan penerimaan sistem tersebut oleh penggunanya.

## 1.2 Rumusan Masalah

Berdasarkan latar belakang di atas, rumusan masalah dalam penelitian ini adalah sebagai berikut:

1. Bagaimana menganalisis kebutuhan fungsional dan nonfungsional sistem Learning Management System yang sesuai dengan proses bisnis akademik di SMKN 9 Garut?
2. Bagaimana merancang arsitektur sistem, basis data, dan alur proses (use case, activity, dan sequence diagram) untuk LMS yang mengakomodasi tiga peran pengguna (Administrator, Guru, Siswa)?
3. Bagaimana mengimplementasikan rancangan tersebut menjadi sebuah sistem LMS berbasis web menggunakan framework Laravel 12 dengan menerapkan prinsip keamanan aplikasi web (CSRF, XSS, SQL Injection protection, dan Role-Based Access Control)?
4. Bagaimana hasil pengujian fungsional (black-box testing) terhadap sistem LMS yang telah dibangun, ditinjau dari kesesuaian antara skenario uji dengan hasil yang diharapkan?
5. Bagaimana tingkat penerimaan pengguna (Administrator, Guru, dan Siswa) terhadap sistem LMS yang dikembangkan, berdasarkan hasil evaluasi menggunakan kuesioner *User Acceptance Testing*?

## 1.3 Tujuan Penelitian

Sejalan dengan rumusan masalah di atas, tujuan penelitian ini adalah:

1. Menghasilkan dokumen analisis kebutuhan fungsional dan nonfungsional sistem LMS yang sesuai dengan kebutuhan proses akademik SMKN 9 Garut.
2. Menghasilkan rancangan arsitektur sistem, Entity Relationship Diagram (ERD), serta diagram UML (use case, activity, sequence) sebagai dasar pengembangan sistem.
3. Menghasilkan sebuah sistem Learning Management System berbasis web yang dibangun menggunakan framework Laravel 12, mencakup modul manajemen data master, materi pembelajaran, tugas, kuis, ujian daring dengan penilaian otomatis, presensi, nilai, jadwal, pengumuman, dan forum diskusi.
4. Mengetahui hasil pengujian fungsional (black-box testing) terhadap fitur-fitur utama sistem untuk memastikan sistem berjalan sesuai spesifikasi yang dirancang.
5. Mengetahui tingkat penerimaan pengguna terhadap sistem LMS yang dikembangkan melalui evaluasi *User Acceptance Testing* kepada Administrator, Guru, dan Siswa SMKN 9 Garut.

## 1.4 Manfaat Penelitian

### 1.4.1 Manfaat Teoritis

Penelitian ini diharapkan dapat menjadi referensi dan memperkaya kajian ilmiah di bidang Sistem Informasi, khususnya terkait penerapan metode **Design and Development Research (DDR)** dalam pengembangan perangkat lunak pendidikan berbasis web, serta menjadi bahan rujukan bagi penelitian sejenis di masa mendatang.

### 1.4.2 Manfaat Praktis

1. **Bagi SMKN 9 Garut** — menyediakan sistem terpadu yang membantu proses distribusi materi, pemberian tugas, pelaksanaan ujian daring, presensi, dan rekapitulasi nilai secara digital, sehingga meningkatkan efisiensi administrasi akademik.
2. **Bagi Guru** — mempermudah pengelolaan materi ajar, penilaian tugas dan ujian (termasuk penilaian otomatis untuk soal pilihan ganda), serta pemantauan presensi siswa tanpa proses rekap manual.
3. **Bagi Siswa** — menyediakan akses terhadap materi pembelajaran, tugas, kuis/ujian, nilai, presensi, dan jadwal pelajaran secara mandiri (*self-service*) kapan saja diperlukan.
4. **Bagi Peneliti** — menjadi sarana penerapan ilmu yang diperoleh selama perkuliahan, khususnya dalam analisis, perancangan, dan implementasi sistem informasi berbasis web menggunakan metodologi penelitian pengembangan.

## 1.5 Batasan Masalah

Agar penelitian lebih terarah dan tidak menyimpang dari tujuan yang ditetapkan, penelitian ini dibatasi pada hal-hal berikut:

1. Sistem dikembangkan khusus untuk mendukung proses akademik di SMKN 9 Garut dan tidak mencakup proses non-akademik seperti keuangan/pembayaran SPP, kepegawaian (payroll), atau perpustakaan.
2. Registrasi akun secara publik (self sign-up) tidak disediakan; seluruh akun Guru dan Siswa dibuat dan dikelola oleh Administrator untuk menjaga validitas data akademik.
3. Sistem dibangun menggunakan framework Laravel 12 dengan basis data MySQL/MariaDB, dan dijalankan pada lingkungan server lokal XAMPP (Apache + MySQL + PHP 8.2+).
4. Antarmuka pengguna (frontend) dibangun menggunakan Bootstrap 5, Font Awesome 6, dan Chart.js yang di-*vendor* secara lokal (tidak melalui CDN maupun proses build Node.js/NPM/Vite), sehingga sistem dapat berjalan sepenuhnya secara luring (*offline*) tanpa koneksi internet.
5. Pengujian sistem dilakukan dengan metode **black-box testing** untuk menguji kesesuaian fungsional terhadap spesifikasi, serta **kuesioner User Acceptance Testing** dengan skala Likert kepada responden terbatas (perwakilan Administrator, Guru, dan Siswa SMKN 9 Garut), bukan pengujian performa/beban (*load/stress testing*) skala besar.
6. Penelitian tidak mencakup pengembangan aplikasi mobile native (Android/iOS); antarmuka web yang dibangun bersifat responsif sehingga dapat diakses melalui perangkat mobile menggunakan peramban (browser).

## 1.6 Sistematika Penulisan

Sistematika penulisan skripsi ini disusun dalam lima bab, dengan uraian sebagai berikut:

**BAB I PENDAHULUAN** — menguraikan latar belakang masalah, rumusan masalah, tujuan penelitian, manfaat penelitian, batasan masalah, dan sistematika penulisan.

**BAB II TINJAUAN PUSTAKA** — menguraikan landasan teori yang digunakan sebagai dasar penelitian, meliputi konsep Learning Management System, rekayasa perangkat lunak, framework Laravel, basis data, Unified Modeling Language (UML), Entity Relationship Diagram (ERD), metode Design and Development Research (DDR), serta kajian penelitian terdahulu yang relevan.

**BAB III METODE PENELITIAN** — menguraikan jenis penelitian, tempat dan waktu penelitian, subjek penelitian, prosedur penelitian berdasarkan tahapan DDR (Analysis, Design, Development, Evaluation), teknik pengumpulan data, instrumen penelitian, serta teknik analisis data.

**BAB IV HASIL DAN PEMBAHASAN** — menguraikan hasil penelitian pada setiap tahapan DDR: hasil analisis kebutuhan, hasil perancangan (arsitektur, ERD, diagram UML), hasil pengembangan (implementasi sistem per modul), serta hasil evaluasi berupa pengujian black-box dan evaluasi penerimaan pengguna.

**BAB V PENUTUP** — berisi kesimpulan yang menjawab rumusan masalah penelitian, serta saran untuk pengembangan dan penelitian selanjutnya.

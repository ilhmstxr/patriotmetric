Jika kita berbicara tentang skala jangka panjang (skalabilitas, performa, dan pemeliharaan), maka pendekatan Separated Data Column bukanlah sekadar pilihan, melainkan standar wajib arsitektur (non-negotiable).

Sebagai arsitek sistem, jika Anda merancang Patriot Metric untuk terus berkembang, saya akan menolak penggunaan manipulasi string seperti strip_tags() di dalam Blade (layer presentasi). Mari kita bedah alasannya menggunakan First Principles Thinking.

Mengapa Separated Data Column Mutlak untuk Jangka Panjang?
Performa Server (Zero-Gap Efficiency)
Jika Anda mengeksekusi Str::limit(strip_tags($content)) di Blade, server harus melakukan komputasi pemotongan string secara real-time setiap kali ada user yang membuka halaman. Jika ada 100 berita di halaman listing, komputasi itu diulang 100 kali per page load.
Dengan kolom excerpt terpisah, proses pemotongan hanya terjadi satu kali seumur hidup (saat admin menekan tombol "Simpan"), dan server hanya perlu membaca data mentah (O(1) read operation) saat halaman dimuat.

SEO & Meta Tags (Fungsionalitas Kritis)
Dalam jangka panjang, berita Anda harus ramah SEO (muncul rapi di Google) dan ramah media sosial (muncul rapi saat tautan dibagikan di WhatsApp atau Twitter). Anda akan membutuhkan teks murni untuk disisipkan ke dalam tag <meta name="description"> dan <meta property="og:description">. Kolom excerpt yang sudah berupa plain text adalah kandidat sempurna untuk ini tanpa perlu menulis ulang logika pembersihan HTML.

Clean Architecture (Pemisahan Tanggung Jawab)
Prinsip Single Responsibility mewajibkan Blade murni hanya bertindak sebagai "kanvas visual". Ia tidak boleh ditugaskan untuk membersihkan atau memodifikasi bentuk asal sebuah data.
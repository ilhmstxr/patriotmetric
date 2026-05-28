buatkan saya testing di sisi 

alur kronologis
peserta registrasi ~ pengisian rubrik, ditutup otomatis oleh timeline submission berakhir(status assessment otomatis berubah dari in_progress menjadi submitted), reviewer mereview (ketika status submitted), hingga selesai lalu di klik button simpan untuk mengubah status dari submitted menjadi graded (sudah selesai direview), lalu buat timeline lagi untuk pengumuman serentak (pengumumannya dilihat dari status ketika published, jadi servicenya otomatis merubah status graded => published), lalu hasilnya dapat dilihat di sisi peserta

1. peserta
dari registrasi, login, verifikasi, pengisian rubrik, hasil rubrik penilaian sementara, hasil final
2. reviewer
dari verifiksai jawaban peserta, klik button pengumpulan
3. admin
manajemen user, membuat akun reviewer, mengatur rubric,memplotting reviewer ke peserta

credential admin:
username: admin@admin.com
password: admin

untuk reviewer, buat di sisi admin dan di plotting juga di sisi admin
untuk peserta, harus melewati sesi registrasi, login & verifikasi, agar bisa mengisi rubrik 

oke, testing juga di sisi 
1. status in_progress: peserta & reviewer, 
peserta: ketika posisi in_progress / pengisian rubrik di peserta, di peserta bisa memilih / mengisi rubrik, dan mengedit profil.
reviewer: dapat melihat plottingan peserta yang akan di review, tapi tidak bisa mengklik detail rubrik (akan muncul alert)

2. status submitted: peserta & reviewer, 
peserta: tidak bisa mengubah / mengisi rubrik / mengedit profil. namun terdapat keterangan di hasil penilaian bahwa reviewer sedang memeriksa rubrik 
reviewer: bisa mengklik detail rubrik, mengisi skor dan lain lain 

3. status graded: reviewer(primary), 
peserta: tidak bisa mengubah / mengisi rubrik / mengedit profil, namun terdapat keterangan di hasil penilaian bahwa reviewer sedang memeriksa rubrik 
reviewer: ketika mengklik button simpan, maka akan otomatis merubah status menjadi graded, lalu reviewer tidak bisa merubah apapun ketika sudah mengklik button tersebut, namun masih dapat mengakses detail rubrik peserta yang telah di plotting  

4. status published: peserta(primary)
peserta: dapat melihat nilai finalnya, namun untuk rubrik tetap tidak bisa di edit
reviewer: dapat melihat plottingan penilaian nya dengan status published, namun tidak bisa di edit
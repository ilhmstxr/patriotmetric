- tabel users
id PK
name
email
password
role
nama_institusi (nullable)
alamat (nullable)
telepon (nullable)
created_at
updated_at


tabel pengumpulan
id PK
user_id FK
reviewer_id FK
status
total_skor_sistem
total_skor_akhir
created_at
updated_at

tabel pengumpulan_jawaban
id PK
submission_id FK
question_id FK
jawaban_teks
tautan_bukti_drive
skor_sistem
skor_validasi_reviewer
created_at
updated_at

tabel pertanyaan
id PK
category_id FK
teks_pertanyaan
tipe
opsi_jawaban
created_at
updated_at

tabel kategori
id PK
nama_kategori
deskripsi
bobot_presentase
created_at
updated_at

tabel pengaturan_cms
id PK
key (unique)
value
created_at
updated_at

"Saya ingin membuat fitur [NAMA_FITUR] menggunakan Laravel 12. Kamu harus mengikuti SOP arsitektur Skinny Controller yang sudah saya tetapkan:

Alur Data: Controller -> DTO -> Service -> Repository -> API Resource.

Controller: Dilarang berisi logika bisnis atau query database. Tugasnya hanya validasi request dan memanggil Service menggunakan DTO. Gunakan Trait ApiResponse untuk return data.

DTO: Gunakan readonly class dengan constructor property promotion dan static method fromRequest().

Service: Harus extends BaseService. Semua logika bisnis, perhitungan, dan integrasi pihak ketiga ada di sini.

Repository: Harus extends BaseRepository. Semua query Eloquent (where, order, join) harus dilakukan di sini.

Output: Gunakan Eloquent API Resource untuk memformat data sebelum dikirim ke client. Jangan melakukan query database di dalam Resource.

Sekarang, tolong buatkan kode untuk modul"
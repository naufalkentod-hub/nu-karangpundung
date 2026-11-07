<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Sistem Infaq IPNU Karangpundung

Halaman publik tersedia di `/infaq`. Donasi menggunakan transfer manual dan selalu dibuat dengan status `pending`; transaksi hanya dihitung setelah admin memeriksa mutasi dan memverifikasinya. Identitas donatur dan pesan tidak ditampilkan pada riwayat publik.

### Menyiapkan aplikasi

1. Atur `ADMIN_USERNAME` dan `ADMIN_PASSWORD_HASH` di `.env`. Hasilkan hash dengan membuka `php artisan tinker`, lalu menjalankan `Hash::make('kata-sandi-unik-yang-kuat')`. Salin hasil hash ke `.env` dengan tanda kutip tunggal (`ADMIN_PASSWORD_HASH='$2y$...'`) agar karakter `$` tidak diinterpolasi; jangan menyimpan kata sandi biasa di source code atau membagikannya.
2. Isi `INFAQ_BANK_NAME`, `INFAQ_ACCOUNT_NUMBER`, dan `INFAQ_ACCOUNT_HOLDER` hanya dengan rekening resmi organisasi. Selama ketiganya belum diatur, situs tidak menampilkan instruksi transfer dan tombol konfirmasi pembayaran tidak aktif.
3. Jalankan `php artisan optimize:clear` setelah mengubah `.env`, lalu jalankan migration infaq secara additive:

   ```powershell
   php artisan migrate --force
   ```

   Migration tambahan `2026_10_10_000002_add_proof_path_to_infaqs_table` menambahkan lokasi bukti transfer privat. Bukti hanya dapat diakses melalui dashboard yang dilindungi middleware admin.
4. Masuk melalui `/login`, buka **Kelola Infaq** di dashboard admin, dan periksa mutasi rekening sebelum memasukkan referensi verifikasi. Donatur harus mengunggah bukti transfer; sistem mengubah status menjadi `pending_verification`, bukan `paid`. Jangan tandai transaksi berhasil hanya berdasarkan konfirmasi donor.

Permohonan infaq tetap dapat dicatat dengan status `pending` walaupun rekening belum dikonfigurasi. Dalam kondisi itu, donatur diminta menunggu instruksi pengurus dan tidak dapat mengirim konfirmasi transfer. Setelah detail rekening resmi ditambahkan ke `.env`, instruksi transfer dan unggah bukti tersedia pada detail transaksi. Belum ada payment gateway atau webhook yang terhubung; QRIS, transfer otomatis, dan verifikasi callback memerlukan pemilihan penyedia, kredensial resmi, serta implementasi dan pengujian integrasi tersendiri.

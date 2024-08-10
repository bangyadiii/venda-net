# ISP Management System - VENDA NET

ISP Management System ini dikembangkan untuk VENDA NET, sebuah penyedia layanan internet RT/RW yang memiliki fitur-fitur utama sebagai berikut:

## Features

1. **Manajemen Pelanggan**:

   - Tambah, edit, dan hapus data pelanggan.
   - **Integrasi dengan Mikrotik**: Setiap kali data pelanggan ditambahkan, sistem secara otomatis menambahkan `PPPoE Secret` ke Mikrotik.

2. **Manajemen Paket Data**:

   - Tambah, edit, dan hapus data paket internet.
   - **Integrasi dengan Mikrotik**: Setiap kali data paket ditambahkan, sistem secara otomatis menambahkan `PPPoE Profile` ke Mikrotik.

3. **Pembayaran**:

   - **Integrasi dengan Midtrans**: Pembayaran tagihan internet dilakukan melalui payment gateway Midtrans, memastikan proses pembayaran yang aman dan mudah bagi pelanggan.

4. **Notifikasi Billing**:
   - **Integrasi dengan WABLAS**: Sistem mengirimkan notifikasi tagihan kepada pelanggan melalui WhatsApp menggunakan layanan WABLAS, memastikan pelanggan mendapatkan informasi tagihan tepat waktu.

## Instalation

1. **Clone Repository**:
   ```bash
   git clone https://github.com/bangyadiii/venda-net
   cd venda-net
   ```
2. **Install Depedencies**:
   ```bash
   composer install
   npm install
   ```
3. **Konfigurasi Environment**:
   Salin file .env.example menjadi .env dan sesuaikan konfigurasi database, Mikrotik, Midtrans, dan WABLAS sesuai kebutuhan Anda.
   ```bash
    cp .env.example.env
   ```
4. **Migrasi dan Seed Database**:
   ```bash
   php artisan migrate --seed
   ```
5. **Generate App Key**:
   ```bash
   php artisan key:generate
   ```
6. Run the app:
   ```bash
   php artisan serve
   ```

## Usage

Setelah aplikasi berjalan, Anda dapat mengakses antarmuka melalui http://localhost:8000. Masuk dengan kredensial admin yang telah diatur selama proses seeding.

## Mikrotik Integration

- Tambahkan pelanggan baru melalui menu Manajemen Pelanggan dan `PPPoE Secret` akan otomatis ditambahkan ke Mikrotik.
- Tambahkan paket data baru melalui menu Manajemen Paket Data dan `PPPoE Profile` akan otomatis ditambahkan ke Mikrotik.

## Midtrans Payment Integration

- Pembayaran dilakukan melalui gateway Midtrans. Pastikan Anda telah mengonfigurasi `MIDTRANS_SERVER_KEY` dan `MIDTRANS_CLIENT_KEY` di file `.env.`

## WABLAS Notification Integration

- Sistem akan mengirimkan notifikasi tagihan secara otomatis melalui WhatsApp menggunakan layanan WABLAS. Pastikan Anda telah mengonfigurasi `WABLAS_API_KEY` di file `.env`.

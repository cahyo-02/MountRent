# MountRent
Website peminjaman alat mendaki

📋 Sections Added:
1. Deskripsi
Penjelasan lengkap tentang MountRent sebagai platform manajemen penyewaan peralatan mendaki gunung dengan integrasi Midtrans

2. Key Features
👤 Fitur user: browse, booking, keranjang, pembayaran, tracking status, cek pembayaran, cetak struk
🔧 Fitur admin: manajemen barang, pembayaran, transaksi, return flow, maintenance, damage report, fines management
3. Preview (API Endpoints)
Contoh lengkap endpoint dengan:

User endpoints: List barang, Booking, Cek Status Pembayaran, Cetak Struk
Admin endpoints: List Transaksi, Update Status, Proses Pengembalian, Record Maintenance, Damaged Items, Fines
Setiap endpoint menampilkan: Request format, Validasi, Response success, Response error
4. Project Structure
Diagram lengkap folder structure dengan penjelasan:

App folder (Controllers, Models, Middleware)
Routes configuration
Resources (views, CSS, JS)
Database (migrations, seeders)
File-file penting dengan deskripsi
5. Tech Stack
Backend: Laravel 11, MySQL 8.0, Eloquent ORM
Frontend: Blade, Tailwind CSS, Alpine.js, Vite
Payment: Midtrans SDK PHP
Testing: Pest PHP
Tools: PHP 8.2+, Git, Node.js
6. Local Setup
Panduan step-by-step lengkap:

✅ Prerequisites (PHP, Composer, MySQL, Git, Node.js, Midtrans account)
📝 Installation: Clone, install dependencies, setup .env, database, build assets
🚀 Cara menjalankan server
🔧 Troubleshooting section
7. Documentation
Tabel lengkap dengan link dokumentasi:

Laravel, Blade, Eloquent, Routing, Form Requests
Tailwind CSS, Alpine.js, Vite
Midtrans, Pest PHP, MySQL
Plus guides untuk payment integration & webhook
8. Commit Format Standards
Conventional Commits format
Type: feat, fix, refactor, style, test, docs, chore, perf
Scope examples: payment, transaksi, barang, migration, ui, auth
12 sample commit messages dengan contoh real dari project:
Feature commits (payment order_id, return flow)
Fix commits (order_id format, UI styling)
Refactor, docs, chore commits
Multi-change commits

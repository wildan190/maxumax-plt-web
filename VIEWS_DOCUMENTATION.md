# Authentication & Admin Dashboard Views

Dokumentasi lengkap untuk struktur views, styling, dan komponen yang telah dibuat.

## 📁 Struktur File

```
resources/
├── views/
│   ├── layouts/
│   │   ├── auth.blade.php              # Layout untuk halaman auth
│   │   ├── app.blade.php               # Layout untuk halaman admin
│   │   └── partials/
│   │       ├── sidebar.blade.php       # Sidebar admin
│   │       ├── breadcrumb.blade.php    # Breadcrumb navigation
│   │       └── topbar.blade.php        # Top navigation bar
│   ├── auth/
│   │   ├── login.blade.php             # Halaman login
│   │   ├── register.blade.php          # Halaman register
│   │   ├── forgot-password.blade.php   # Halaman lupa password
│   │   ├── reset-password.blade.php    # Halaman reset password
│   │   ├── verify-email.blade.php      # Halaman verifikasi email
│   │   └── confirm-password.blade.php  # Halaman konfirmasi password
│   └── admin/
│       ├── dashboard.blade.php         # Dashboard admin
│       └── profile.blade.php           # Halaman profil user
├── css/
│   └── layouts.css                     # Styling untuk semua layouts
└── js/
    ├── app.js                          # Main JavaScript entry
    └── layouts.js                      # Layout-specific scripts
```

## 🎨 Design System

### Color Palette
- **Primary**: #000000 (Black)
- **Secondary**: #ffffff (White)
- **Dark Gray**: #1f2937
- **Light Gray**: #f3f4f6
- **Border Gray**: #e5e7eb
- **Text Dark**: #111827
- **Text Light**: #6b7280
- **Error**: #dc2626
- **Success**: #10b981

### Typography
- **Font Family**: Inter
- **Font Weights**: 400, 500, 600, 700, 800
- **Base Font Size**: 16px (1rem)

## 🔐 Authentication Views

### Login (`auth/login.blade.php`)
- Form login dengan email dan password
- Opsi "Ingat saya" (Remember Me)
- Link ke forgot password dan register
- Error handling untuk validasi

### Register (`auth/register.blade.php`)
- Form registrasi dengan validasi lengkap
- Konfirmasi password
- Checkbox terms & conditions
- Link ke halaman login

### Forgot Password (`auth/forgot-password.blade.php`)
- Form untuk mengirim reset password link
- Status notification untuk feedback user
- Link kembali ke login

### Reset Password (`auth/reset-password.blade.php`)
- Form untuk membuat password baru
- Validasi password baru dan konfirmasi
- Token handling untuk keamanan
- Link kembali ke login

### Verify Email (`auth/verify-email.blade.php`)
- Notifikasi untuk verifikasi email
- Button untuk mengirim ulang verifikasi link
- Option untuk logout

### Confirm Password (`auth/confirm-password.blade.php`)
- Form konfirmasi password untuk keamanan
- Minimal form untuk user experience yang baik

## 📊 Admin Dashboard

### Dashboard (`admin/dashboard.blade.php`)
- **Stat Cards**: 4 kartu statistik dengan icon
  - Total Pengguna
  - Aktivitas Hari Ini
  - Tingkat Pertumbuhan
  - Performa Sistem
- **Recent Activity**: Feed aktivitas terbaru dengan timestamps

### Profile (`admin/profile.blade.php`)
- **Profile Card**: Menampilkan informasi user dengan avatar
- **Update Profile Form**: Edit nama dan email
- **Update Password Form**: Ubah kata sandi dengan validasi
- **Danger Zone**: Opsi untuk menghapus akun

## 🧩 Partials Components

### Sidebar (`layouts/partials/sidebar.blade.php`)
Features:
- Logo dan branding
- Navigasi utama (Dashboard, Profil)
- Navigasi manajemen (Pengguna, Pengaturan)
- Tools section (Pesan, Bantuan)
- Account section (Logout)
- Active state indication
- Responsive untuk mobile

### Breadcrumb (`layouts/partials/breadcrumb.blade.php`)
- Menampilkan breadcrumb navigation
- Format: Home > Section > Page
- Menggunakan Feather Icons untuk separator
- Data breadcrumbs dikirim sebagai parameter

### Topbar (`layouts/partials/topbar.blade.php`)
Features:
- Sidebar toggle button (mobile)
- Page title display
- User info dengan avatar
- Responsive design
- User menu trigger

## 🔧 Implementasi

### Menggunakan Auth Layout
```blade
@extends('layouts.auth')

@section('auth-title', 'Judul')
@section('auth-subtitle', 'Sub judul')

@section('content')
    <!-- Form content -->
@endsection
```

### Menggunakan App Layout (Admin)
```blade
@extends('layouts.app')

@section('title', 'Page Title')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome message')

@section('content')
    <!-- Page content -->
@endsection
```

### Menggunakan Breadcrumb
```php
$breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('dashboard')],
    ['label' => 'Profil', 'url' => route('profile.show')],
    ['label' => 'Edit', 'url' => '#'],
];

return view('page', compact('breadcrumbs'));
```

## 🎯 Routes Configuration

Tambahkan routes berikut di `routes/web.php`:

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', /* */)->name('dashboard');
    Route::get('/profile', /* */)->name('profile.show');
    Route::put('/profile', /* */)->name('profile.update');
    Route::put('/password', /* */)->name('password.update');
    Route::delete('/profile', /* */)->name('profile.destroy');
});
```

## 📱 Responsive Design

- **Desktop (>768px)**: Full layout dengan sidebar permanent
- **Tablet (≤768px)**: Sidebar toggle button, sidebar berubah absolute
- **Mobile (<480px)**: Optimized untuk layar kecil

### Breakpoints
- `@media (max-width: 768px)`: Mobile/Tablet adjustments
- Grid system responsive dengan `grid-template-columns: repeat(auto-fit, ...)`

## 🎭 Feather Icons

Semua icons menggunakan Feather Icons dari CDN:
```html
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
```

Contoh penggunaan:
```html
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" 
     fill="none" stroke="currentColor" stroke-width="2" 
     stroke-linecap="round" stroke-linejoin="round">
    <polyline points="20 6 9 17 4 12"></polyline>
</svg>
```

## 💅 Styling dengan Vite

Semua CSS diimport melalui Vite:
```blade
@vite(['resources/css/app.css', 'resources/css/layouts.css'])
```

CSS variables untuk mudah customization:
```css
:root {
    --primary: #000000;
    --secondary: #ffffff;
    /* ... other variables ... */
}
```

## 🔄 JavaScript Interactivity

### Sidebar Toggle
```javascript
const sidebarToggle = document.getElementById('sidebarToggle');
const adminSidebar = document.getElementById('adminSidebar');

sidebarToggle.addEventListener('click', function() {
    adminSidebar.classList.toggle('active');
});
```

### Form Validation
```javascript
validateEmail(email)      // Check valid email format
validatePassword(password) // Check password requirements
showNotification(msg, type) // Display notifications
```

## ✅ Checklist Konfigurasi

- [ ] Install Fortify: `composer require laravel/fortify`
- [ ] Publish Fortify: `php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"`
- [ ] Update `FortifyServiceProvider.php` dengan view paths
- [ ] Configure routes di `routes/web.php`
- [ ] Run migrations: `php artisan migrate`
- [ ] Build assets: `npm run build`

## 🚀 Development Commands

```bash
# Develop dengan hot reload
npm run dev

# Build untuk production
npm run build

# Run Laravel server
php artisan serve

# Run migrations
php artisan migrate

# Create test user
php artisan tinker
# User::create(['name' => 'Test', 'email' => 'test@example.com', 'password' => Hash::make('password')])
```

## 📝 Notes

- Semua forms sudah terintegrasi dengan CSRF token
- Error handling menggunakan `@error` blade directive
- Responsive design tested di mobile, tablet, dan desktop
- All components menggunakan semantic HTML
- Accessibility considerations implemented
- Dark mode ready dengan CSS variables

## 🐛 Troubleshooting

**Icons tidak tampil?**
- Pastikan Feather Icons CDN accessible
- Panggil `feather.replace()` setelah DOM ready

**Styling tidak diterapkan?**
- Jalankan `npm run build` untuk compile CSS
- Clear browser cache
- Cek apakah `@vite` directive ada di layout

**Form tidak bekerja?**
- Pastikan CSRF token ada di form
- Cek route names sesuai dengan yang didefinisikan
- Verify middleware auth:sanctum

---

**Created**: 2026-01-03
**Last Updated**: 2026-01-03
**Version**: 1.0.0

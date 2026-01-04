# Setup Instructions untuk Authentication & Admin Dashboard

Dokumentasi langkah demi langkah untuk mengkonfigurasi Fortify dan views yang telah dibuat.

## 1️⃣ Pre-requisites

Pastikan Anda sudah memiliki:
- Laravel 12.0+
- PHP 8.4+
- Fortify sudah di-install (`composer require laravel/fortify`)
- Node.js & npm untuk Vite

## 2️⃣ Verifikasi Fortify Installation

```bash
# Pastikan Fortify sudah di-publish
php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"

# Jalankan migrations untuk 2FA (two-factor authentication)
php artisan migrate
```

## 3️⃣ Register Views di FortifyServiceProvider

File: `app/Providers/FortifyServiceProvider.php`

Pastikan sudah menambahkan view bindings di method `boot()`:

```php
public function boot(): void
{
    // ... existing code ...

    // View Paths
    Fortify::loginView(function () {
        return view('auth.login');
    });

    Fortify::registerView(function () {
        return view('auth.register');
    });

    Fortify::requestPasswordResetLinkView(function () {
        return view('auth.forgot-password');
    });

    Fortify::resetPasswordView(function ($request) {
        return view('auth.reset-password', ['request' => $request]);
    });

    Fortify::verifyEmailView(function () {
        return view('auth.verify-email');
    });

    Fortify::confirmPasswordView(function () {
        return view('auth.confirm-password');
    });
}
```

## 4️⃣ Setup Routes

File: `routes/web.php`

```php
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('profile.show');

    Route::put('/profile', function () {
        // Handle profile update
        return back();
    })->name('profile.update');

    Route::put('/password', function () {
        // Handle password update
        return back();
    })->name('password.update');

    Route::delete('/profile', function () {
        // Handle profile deletion
        return redirect('/');
    })->name('profile.destroy');
});
```

## 5️⃣ Konfigurasi Fortify (Optional)

File: `config/fortify.php`

```php
return [
    'guard' => 'sanctum',

    'passwords' => 'users',

    'username' => 'email',

    'email' => 'email',

    'home' => '/dashboard',

    'features' => [
        Features::registration(),
        Features::resetPasswords(),
        // Features::emailVerification(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        Features::twoFactorAuthentication([
            'confirmPassword' => true,
        ]),
    ],
];
```

## 6️⃣ Build Assets dengan Vite

```bash
# Development dengan hot reload
npm run dev

# Build untuk production
npm run build
```

## 7️⃣ Database & User Seeding (Optional)

```bash
# Run migrations
php artisan migrate

# Create test user via Tinker
php artisan tinker

# Di dalam tinker shell:
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'email_verified_at' => now(),
    'password' => Hash::make('password'),
]);

exit
```

## 8️⃣ Test Authentication

Jalankan development server:

```bash
php artisan serve
```

Buka di browser:
- Register: `http://localhost:8000/register`
- Login: `http://localhost:8000/login`
- Forgot Password: `http://localhost:8000/forgot-password`
- Dashboard: `http://localhost:8000/dashboard`

## 9️⃣ Customize Styling

Semua styling ada di: `resources/css/layouts.css`

Edit CSS variables di `:root`:
```css
:root {
    --primary: #000000;      /* Ubah primary color */
    --secondary: #ffffff;    /* Ubah secondary color */
    --dark-gray: #1f2937;    /* Ubah dark gray */
    /* ... */
}
```

## 🔟 Troubleshooting

### ❌ Icons tidak tampil
**Solusi**: 
```bash
# Pastikan Feather Icons CDN accessible
# Cek di Network tab browser developer tools
# Atau gunakan CDN lain atau install via npm
npm install feather-icons
```

### ❌ Styling tidak terlihat
**Solusi**:
```bash
# Clear cache dan rebuild
npm run build

# Atau development mode dengan hot reload
npm run dev

# Clear browser cache (Ctrl+Shift+Delete)
```

### ❌ Form tidak submit
**Solusi**:
1. Pastikan CSRF token ada di form
2. Cek route names sesuai (gunakan `route('name')`)
3. Verify middleware auth:sanctum di protected routes
4. Check controller methods menangani request

### ❌ Breadcrumb tidak tampil
**Solusi**:
```php
// Pastikan breadcrumbs array dikirim ke view
$breadcrumbs = [
    ['label' => 'Home', 'url' => route('dashboard')],
    ['label' => 'Current', 'url' => '#'],
];

return view('your-view', compact('breadcrumbs'));
```

## 📋 Checklist Setelah Setup

- [ ] Fortify di-publish
- [ ] Views telah dibuat (login, register, dll)
- [ ] FortifyServiceProvider di-update dengan view bindings
- [ ] Routes di-setup di web.php
- [ ] Assets di-build dengan `npm run build`
- [ ] Database migrations berjalan
- [ ] Test user dibuat
- [ ] Semua views dan styling test di browser
- [ ] Icons tampil dengan benar
- [ ] Responsive design test di mobile

## 🎯 Penggunaan di Controller

Contoh implementasi di controller:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
        ];

        return view('admin.dashboard', compact('breadcrumbs'));
    }

    public function profile()
    {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Profile', 'url' => '#'],
        ];

        return view('admin.profile', compact('breadcrumbs'));
    }
}
```

## 🔒 Security Best Practices

1. **CSRF Protection**: Semua form sudah ada `@csrf`
2. **Password Hashing**: Gunakan `Hash::make()` saat create/update password
3. **Email Verification**: Enable di config/fortify.php jika diperlukan
4. **Two-Factor Auth**: Fortify sudah support, enable di config
5. **Rate Limiting**: Sudah dikonfigurasi di FortifyServiceProvider

## 📚 Resources

- [Laravel Fortify Documentation](https://laravel.com/docs/fortify)
- [Laravel Authentication](https://laravel.com/docs/authentication)
- [Feather Icons](https://feathericons.com/)
- [Vite](https://vitejs.dev/)
- [Blade Templating](https://laravel.com/docs/blade)

## ✅ Selesai!

Selamat! Anda sudah berhasil setup authentication dan admin dashboard dengan desain modern dan clean code. 🎉

Untuk pertanyaan lebih lanjut, lihat dokumentasi di `VIEWS_DOCUMENTATION.md`

# 📘 Panduan Setup Social Login dengan Laravel Socialite

## 🎯 Overview

Aplikasi SIMPUS sekarang sudah dilengkapi dengan fitur **Social Login** menggunakan Laravel Socialite. User bisa login dengan:

- 🔵 **Google**
- 🔷 **Facebook**  
- 🍎 **Apple**

## ✅ Yang Sudah Diimplementasikan

### 1. Backend

- ✅ Laravel Socialite package terinstall
- ✅ Database migration untuk social login fields
- ✅ User model updated dengan fillable fields
- ✅ SocialAuthController dengan methods untuk ketiga provider
- ✅ Routes untuk social authentication
- ✅ Konfigurasi di `config/services.php`

### 2. Frontend

- ✅ Social login buttons di halaman login terhubung ke routes
- ✅ UI modern dengan icon Google, Facebook, dan Apple

## 🔧 Yang Perlu Dilakukan

### Step 1: Jalankan Migration

Jalankan migration untuk menambah kolom social login ke tabel users:

```bash
php artisan migrate
```

Ini akan menambahkan kolom:

- `google_id`
- `facebook_id`
- `apple_id`
- `avatar`

---

## 🔑 Setup Credentials

Untuk mengaktifkan social login, Anda perlu mendapatkan **Client ID** dan **Client Secret** dari masing-masing provider.

### 📌 1. Google OAuth Setup

#### Langkah-langkah

1. **Buka Google Cloud Console**
   - Kunjungi: <https://console.cloud.google.com/>

2. **Buat Project Baru** (atau pilih project yang sudah ada)
   - Klik "Select a project" → "New Project"
   - Beri nama project (contoh: "SIMPUS")
   - Klik "Create"

3. **Enable Google+ API**
   - Di sidebar, pilih "APIs & Services" → "Library"
   - Cari "Google+ API"
   - Klik dan pilih "Enable"

4. **Buat OAuth 2.0 Credentials**
   - Di sidebar, pilih "APIs & Services" → "Credentials"
   - Klik "Create Credentials" → "OAuth client ID"
   - Pilih "Web application"
   - Beri nama (contoh: "SIMPUS Web")

5. **Konfigurasi Authorized Redirect URIs**
   - Tambahkan URL callback:

     ```
     http://127.0.0.1:8000/auth/google/callback
     http://localhost:8000/auth/google/callback
     ```

   - Untuk production, tambahkan:

     ```
     https://yourdomain.com/auth/google/callback
     ```

6. **Copy Credentials**
   - Setelah dibuat, Anda akan mendapat:
     - **Client ID**: `xxxxx.apps.googleusercontent.com`
     - **Client Secret**: `xxxxx`

7. **Tambahkan ke `.env`**

   ```env
   GOOGLE_CLIENT_ID=your-google-client-id
   GOOGLE_CLIENT_SECRET=your-google-client-secret
   GOOGLE_REDIRECT_URL=http://127.0.0.1:8000/auth/google/callback
   ```

---

### 📌 2. Facebook OAuth Setup

#### Langkah-langkah

1. **Buka Facebook Developers**
   - Kunjungi: <https://developers.facebook.com/>

2. **Buat App Baru**
   - Klik "My Apps" → "Create App"
   - Pilih "Consumer" atau "Business"
   - Beri nama app (contoh: "SIMPUS")
   - Masukkan email kontak
   - Klik "Create App"

3. **Setup Facebook Login**
   - Di dashboard app, pilih "Add Product"
   - Cari "Facebook Login" dan klik "Set Up"
   - Pilih "Web"

4. **Konfigurasi OAuth Redirect URIs**
   - Di sidebar, pilih "Facebook Login" → "Settings"
   - Di "Valid OAuth Redirect URIs", tambahkan:

     ```
     http://127.0.0.1:8000/auth/facebook/callback
     http://localhost:8000/auth/facebook/callback
     ```

   - Untuk production:

     ```
     https://yourdomain.com/auth/facebook/callback
     ```

   - Klik "Save Changes"

5. **Copy Credentials**
   - Di sidebar, pilih "Settings" → "Basic"
   - Anda akan melihat:
     - **App ID**: `xxxxx`
     - **App Secret**: `xxxxx` (klik "Show" untuk melihat)

6. **Tambahkan ke `.env`**

   ```env
   FACEBOOK_CLIENT_ID=your-facebook-app-id
   FACEBOOK_CLIENT_SECRET=your-facebook-app-secret
   FACEBOOK_REDIRECT_URL=http://127.0.0.1:8000/auth/facebook/callback
   ```

7. **Mode Development vs Production**
   - Secara default, app Facebook dalam mode "Development"
   - Untuk production, Anda perlu switch ke "Live" mode di dashboard

---

### 📌 3. Apple Sign In Setup

> ⚠️ **Note**: Apple Sign In lebih kompleks dan memerlukan Apple Developer Account ($99/tahun)

#### Langkah-langkah

1. **Buka Apple Developer**
   - Kunjungi: <https://developer.apple.com/account/>

2. **Buat App ID**
   - Di sidebar, pilih "Certificates, IDs & Profiles"
   - Pilih "Identifiers" → "+" (tambah baru)
   - Pilih "App IDs" → "Continue"
   - Pilih "App" → "Continue"
   - Isi:
     - **Description**: SIMPUS
     - **Bundle ID**: com.yourcompany.simpus
   - Enable "Sign in with Apple"
   - Klik "Continue" → "Register"

3. **Buat Service ID**
   - Kembali ke "Identifiers" → "+" (tambah baru)
   - Pilih "Services IDs" → "Continue"
   - Isi:
     - **Description**: SIMPUS Web
     - **Identifier**: com.yourcompany.simpus.web
   - Enable "Sign in with Apple"
   - Klik "Configure"

4. **Konfigurasi Domains and Redirect URLs**
   - **Domains and Subdomains**: `yourdomain.com` (atau `127.0.0.1` untuk development)
   - **Return URLs**:

     ```
     http://127.0.0.1:8000/auth/apple/callback
     ```

   - Klik "Save" → "Continue" → "Register"

5. **Buat Private Key**
   - Di sidebar, pilih "Keys" → "+" (tambah baru)
   - Beri nama: "SIMPUS Apple Sign In Key"
   - Enable "Sign in with Apple"
   - Klik "Configure" → pilih App ID yang sudah dibuat
   - Klik "Save" → "Continue" → "Register"
   - **Download** file `.p8` (ini hanya bisa didownload sekali!)

6. **Copy Credentials**
   - **Client ID**: Service ID yang dibuat (contoh: `com.yourcompany.simpus.web`)
   - **Team ID**: Bisa dilihat di pojok kanan atas Apple Developer dashboard
   - **Key ID**: ID dari private key yang dibuat
   - **Private Key**: Isi dari file `.p8` yang didownload

7. **Tambahkan ke `.env`**

   ```env
   APPLE_CLIENT_ID=com.yourcompany.simpus.web
   APPLE_CLIENT_SECRET=your-apple-client-secret
   APPLE_REDIRECT_URL=http://127.0.0.1:8000/auth/apple/callback
   ```

> **Note**: Untuk Apple, `APPLE_CLIENT_SECRET` perlu di-generate menggunakan JWT. Ini lebih kompleks dan mungkin memerlukan package tambahan seperti `lcobucci/jwt`.

---

## 🚀 Testing

### 1. Development (Local)

Setelah setup credentials:

1. Buka aplikasi di browser: `http://127.0.0.1:8000/login`
2. Klik salah satu social login button (Google/Facebook/Apple)
3. Anda akan diredirect ke halaman OAuth provider
4. Login dengan akun Anda
5. Setelah berhasil, akan redirect kembali ke aplikasi dan otomatis login

### 2. Troubleshooting

**Error: "redirect_uri_mismatch"**

- Pastikan redirect URL di `.env` sama persis dengan yang didaftarkan di provider
- Cek juga protocol (http vs https)

**Error: "invalid_client"**

- Cek kembali Client ID dan Client Secret
- Pastikan tidak ada spasi atau karakter tambahan

**Error: "This app is in development mode"** (Facebook)

- Tambahkan email Anda sebagai test user di Facebook App dashboard
- Atau switch app ke "Live" mode

---

## 📝 Environment Variables Template

Tambahkan ini ke file `.env` Anda:

```env
# Google OAuth
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URL=http://127.0.0.1:8000/auth/google/callback

# Facebook OAuth
FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
FACEBOOK_REDIRECT_URL=http://127.0.0.1:8000/auth/facebook/callback

# Apple Sign In
APPLE_CLIENT_ID=
APPLE_CLIENT_SECRET=
APPLE_REDIRECT_URL=http://127.0.0.1:8000/auth/apple/callback
```

---

## 🔒 Security Notes

1. **Jangan commit credentials** ke Git
   - Pastikan `.env` ada di `.gitignore`

2. **Gunakan HTTPS di production**
   - OAuth providers memerlukan HTTPS untuk production

3. **Validasi email**
   - Social login otomatis menggunakan email dari provider
   - Pastikan email unique di database

4. **Random password**
   - User yang login via social akan mendapat random password
   - Mereka tidak bisa login dengan email/password biasa kecuali reset password

---

## 📚 Resources

- [Laravel Socialite Documentation](https://laravel.com/docs/socialite)
- [Google OAuth Documentation](https://developers.google.com/identity/protocols/oauth2)
- [Facebook Login Documentation](https://developers.facebook.com/docs/facebook-login)
- [Apple Sign In Documentation](https://developer.apple.com/sign-in-with-apple/)

---

## ✨ Next Steps

Setelah setup credentials:

1. ✅ Jalankan `php artisan migrate`
2. ✅ Tambahkan credentials ke `.env`
3. ✅ Test social login di browser
4. ✅ Deploy ke production dengan HTTPS
5. ✅ Update redirect URLs untuk production domain

---

**Happy Coding! 🚀**

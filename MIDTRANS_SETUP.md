# Setup Midtrans di Railway

## Error yang Terjadi

```
The ServerKey/ClientKey is null. You need to set the server-key from Config.
```

Error ini terjadi karena environment variables Midtrans belum diset di Railway.

## Cara Setup Environment Variables di Railway

### 1. Login ke Railway Dashboard

-   Buka https://railway.app
-   Login dengan akun GitHub Anda
-   Pilih project **Livora-Web-App**

### 2. Buka Settings

-   Klik tab **Variables** di dashboard project
-   Atau klik **Settings** → **Variables**

### 3. Tambahkan Environment Variables Berikut

Tambahkan satu per satu dengan klik **+ New Variable**:

#### Required (Wajib):

```
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxxxxxxxxxxxxx
MIDTRANS_MERCHANT_ID=G123456789
```

#### Optional (Opsional):

```
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### 4. Cara Mendapatkan API Keys Midtrans

#### A. Jika Belum Punya Akun Midtrans:

1. Daftar di https://dashboard.midtrans.com/register
2. Verifikasi email
3. Login ke dashboard

#### B. Mendapatkan Sandbox Keys (Testing):

1. Login ke https://dashboard.midtrans.com
2. Pilih **Sandbox** environment (toggle di kanan atas)
3. Klik **Settings** → **Access Keys**
4. Copy:
    - **Server Key** → `MIDTRANS_SERVER_KEY`
    - **Client Key** → `MIDTRANS_CLIENT_KEY`
    - **Merchant ID** → `MIDTRANS_MERCHANT_ID`

#### C. Mendapatkan Production Keys (Live):

1. Login ke https://dashboard.midtrans.com
2. Pilih **Production** environment
3. Lengkapi verifikasi bisnis (dokumen, NPWP, dll)
4. Setelah approved, klik **Settings** → **Access Keys**
5. Copy keys dan set `MIDTRANS_IS_PRODUCTION=true`

### 5. Format Server Key & Client Key

**Sandbox (Testing):**

```
Server Key: SB-Mid-server-xxxxxxxxxxxxxxxxxxxxxxxx
Client Key: SB-Mid-client-xxxxxxxxxxxxxxxxxxxxxxxx
```

**Production (Live):**

```
Server Key: Mid-server-xxxxxxxxxxxxxxxxxxxxxxxx
Client Key: Mid-client-xxxxxxxxxxxxxxxxxxxxxxxx
```

### 6. Setelah Menambahkan Variables

1. **Railway akan otomatis redeploy** aplikasi
2. Tunggu proses build selesai (~2-3 menit)
3. Cek logs dengan klik **Deployments** → **View Logs**
4. Pastikan tidak ada error terkait Midtrans

### 7. Test Pembayaran

Setelah deploy selesai:

1. Login sebagai tenant
2. Buat booking baru
3. Setelah owner konfirmasi, klik tombol **Bayar**
4. Halaman Midtrans Snap seharusnya muncul

### 8. Testing Payment (Sandbox Mode)

Gunakan test card berikut di Midtrans Snap:

**Berhasil:**

-   Card: `4811 1111 1111 1114`
-   CVV: `123`
-   Exp: `01/25`

**Ditolak:**

-   Card: `4911 1111 1111 1113`
-   CVV: `123`
-   Exp: `01/25`

Lebih lengkap: https://docs.midtrans.com/en/technical-reference/sandbox-test

## Troubleshooting

### Error: "Server Key is null"

-   ✅ Pastikan variabel `MIDTRANS_SERVER_KEY` sudah diset di Railway
-   ✅ Pastikan tidak ada spasi di awal/akhir value
-   ✅ Pastikan Railway sudah selesai redeploy

### Error: "Unauthorized"

-   ❌ Server Key salah atau kadaluarsa
-   ✅ Re-copy dari Midtrans Dashboard
-   ✅ Pastikan environment match (Sandbox vs Production)

### Pembayaran Tidak Tercatat

-   ✅ Cek webhook notification URL sudah benar: `https://arkanta.my.id/api/payment/notification`
-   ✅ Set di Midtrans Dashboard → **Settings** → **Configuration** → **Notification URL**

## Security Notes

⚠️ **PENTING:**

-   ❌ **JANGAN** commit Server Key ke Git
-   ❌ **JANGAN** share Server Key di public
-   ✅ **SELALU** gunakan environment variables
-   ✅ **AKTIFKAN** 3D Secure (is_3ds=true)
-   ✅ **AKTIFKAN** Sanitized (is_sanitized=true)

## Support

Jika masih ada masalah:

-   Midtrans Docs: https://docs.midtrans.com
-   Midtrans Support: support@midtrans.com
-   Railway Docs: https://docs.railway.app

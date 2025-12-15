<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Antarshuka Barbershop</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
      body {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        font-family: sans-serif;
      }
      .card {
        border-radius: 14px;
        box-shadow: 0 6px 30px rgba(30,30,30,0.08);
      }
      .brand {
        width: 72px;
        height: 72px;
        border-radius: 12px;
        object-fit: cover;
        background: linear-gradient(135deg,#222,#555);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 20px;
      }
      .form-footer { font-size: 14px; }
      /* Gaya untuk input yang salah dari backend */
      .is-invalid {
        border-color: #dc3545 !important;
      }
      .invalid-feedback {
        display: block !important;
      }
    </style>
  </head>
  <body>

    <main class="w-100" style="max-width:420px;">
      <div class="card p-4">
        <div class="text-center mb-3">
          
          <h5 class="mb-0">Antarshuka Barbershop</h5>
          <small class="text-muted">Shave • Haircut • Grooming</small>
        </div>

        <!-- FORM LARAVEL: METHOD POST, ACTION KE ROUTE LOGIN -->
        <form id="loginForm" method="POST" action="{{ route('login') }}" novalidate>
            @csrf <!-- Wajib ada untuk Laravel -->
            
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <!-- NAMA INPUT DIUBAH MENJADI 'email' -->
            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                   id="email" name="email" placeholder="masukkan email" required autofocus 
                   value="{{ old('email') }}">
            
            <!-- MENAMPILKAN ERROR VALIDASI DARI BACKEND -->
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
          </div>

          <div class="mb-3 position-relative">
            <label for="password" class="form-label">Kata Sandi</label>
            <div class="input-group">
              <input type="password" class="form-control @error('password') is-invalid @enderror" 
                     id="password" name="password" placeholder="masukkan kata sandi" required>
              <button class="btn btn-outline-secondary" type="button" id="togglePwd" aria-label="Tampilkan kata sandi">👁️</button>
            </div>
             @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
              <label class="form-check-label small" for="remember">Ingat saya</label>
            </div>
            <a href="#" class="small">Lupa kata sandi?</a>
          </div>

          <div class="d-grid mb-3">
            <button type="submit" class="btn btn-dark">Masuk</button>
          </div>

          <div class="text-center form-footer">
            <span class="text-muted">Belum punya akun? </span><a href="#">Daftar sekarang</a>
          </div>
        </form>

      </div>

      <p class="text-center text-muted mt-3 small">© <span id="year"></span> Barbershop — tampil rapi, percaya diri.</p>
    </main>

    <!-- Bootstrap JS + optional Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
      // Year
      document.getElementById('year').textContent = new Date().getFullYear();

      // Toggle password visibility
      const pwd = document.getElementById('password');
      const toggle = document.getElementById('togglePwd');
      toggle.addEventListener('click', () => {
        if (pwd.type === 'password') {
          pwd.type = 'text';
          toggle.textContent = '🙈';
        } else {
          pwd.type = 'password';
          toggle.textContent = '👁️';
        }
      });

      // Hapus Javascript client-side validation karena kita mengandalkan Laravel
      // Namun, kita tetap menggunakan kelas was-validated untuk styling Bootstrap
      (function () {
        'use strict'
        const form = document.getElementById('loginForm');
        form.addEventListener('submit', function (event) {
          // Hanya tambahkan kelas untuk styling feedback visual jika form di-submit
          form.classList.add('was-validated');
        }, false);
      })();
    </script>
  </body>
</html>
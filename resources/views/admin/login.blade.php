<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - NU Karangpundung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --ipnu-primary: #198754;
            --ipnu-dark: #145c38;
            --ipnu-light: #e8f5e9;
        }

        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 1.25rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #334155;
            background: linear-gradient(135deg, #edf7ef, #f8faf9 55%, #e2f1e6);
        }

        .login-panel {
            width: min(100%, 420px);
            padding: 2.5rem;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 18px 50px rgba(20, 92, 56, .1);
        }

        .brand-mark {
            display: grid;
            place-items: center;
            margin: 0 auto 1rem;
        }

        .brand-logo {
            display: block;
            width: 56px;
            height: 56px;
            object-fit: contain;
        }

        .form-control { min-height: 46px; }
        .form-control:focus { border-color: var(--ipnu-primary); box-shadow: 0 0 0 .2rem rgba(25, 135, 84, .15); }
        .btn-ipnu { min-height: 46px; background: var(--ipnu-primary); border-color: var(--ipnu-primary); }
        .btn-ipnu:hover { background: var(--ipnu-dark); border-color: var(--ipnu-dark); }

        @media (max-width: 480px) {
            .login-panel { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>
    <main class="login-panel">
        <div class="text-center mb-4">
            <div class="brand-mark"><img class="brand-logo" src="{{ asset('images/logo-nu.png') }}" alt="Lambang Nahdlatul Ulama" width="56" height="56"></div>
            <h1 class="h3 fw-bold mb-1">NU Karangpundung</h1>
            <p class="text-secondary mb-0">Nahdlatul Ulama · Masuk ke panel administrasi</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.process') }}">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label fw-semibold">Username</label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" autocomplete="username" required autofocus>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" autocomplete="current-password" required>
            </div>
            <button type="submit" class="btn btn-ipnu btn-success text-white w-100 fw-semibold">Login</button>
        </form>
    </main>
</body>
</html>
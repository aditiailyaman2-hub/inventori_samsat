<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Login - SAMSAT Inventori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-1">Login</h1>
                    <p class="text-muted mb-4">Sistem Manajemen Inventori Gudang SAMSAT</p>

                    @if ($errors->any())
                        <div class="alert alert-danger py-2">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" name="password" type="password" class="form-control" required>
                        </div>

                        <div class="d-flex align-items-center justify-content-between">
                            <label class="d-flex align-items-center gap-2 text-muted" style="font-size: 0.9rem;">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                Remember me
                            </label>
                            @if (Route::has('password.request'))
                                <a class="text-decoration-none" href="{{ route('password.request') }}">Lupa password?</a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-3">Masuk</button>
                    </form>
                </div>
            </div>

            <div class="text-center text-muted mt-3" style="font-size: 0.85rem;">
                Gunakan akun: <b>super_admin</b> atau <b>petugas</b>.
            </div>
        </div>
    </div>
</div>

</body>
</html>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>{{ config('app.name', 'SAMSAT Inventori') }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar { min-height: 100vh; }
    </style>
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">SAMSAT Inventori</a>

        <div class="d-flex gap-2">
            <a class="btn btn-outline-light btn-sm" href="{{ route('dashboard') }}">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <aside class="col-2 bg-light sidebar border-end">
            <div class="p-3">
                <h6 class="text-muted mb-3">Menu</h6>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>

                    @auth
                        @if(auth()->user()->role === 'super_admin')
                            <li class="nav-item"><a class="nav-link" href="{{ route('items.index') }}">Master Barang</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('reports.index') }}">Laporan</a></li>
                        @endif

                        @if(in_array(auth()->user()->role, ['petugas_gudang']))
                            <li class="nav-item"><a class="nav-link" href="{{ route('transactions.ins.index') }}">Barang Masuk</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('transactions.outs.index') }}">Barang Keluar</a></li>
                        @endif
                    @endauth
                </ul>
            </div>
        </aside>

        <main class="col-10 p-4">
            {{ $slot ?? '' }}

            @isset($header)
                <div class="mb-3">
                    <h2 class="mb-0">{{ $header }}</h2>
                </div>
            @endisset
        </main>
    </div>
</div>

@stack('scripts')

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


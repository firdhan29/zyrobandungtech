<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zyro Bandung Tech - @yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2563eb;
            --sidebar-width: 250px;
        }
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: white;
            border-right: 1px solid #e2e8f0;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link {
            color: #64748b;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin: 0.25rem 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            font-weight: 500;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: #f1f5f9;
            color: var(--primary);
            transform: translateX(5px);
        }
        .main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
            min-height: 100vh;
        }
        .navbar-top {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
        }
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-radius: 16px;
        }
        .cursor-pointer {
            cursor: pointer;
        }
        .btn-primary {
            background: linear-gradient(45deg, var(--primary), #3b82f6);
            border: none;
            border-radius: 12px;
            padding: 10px 24px;
        }
        .alert {
            border-radius: 12px;
            border: none;
        }
        @media (max-width: 768px) {
            .sidebar { margin-left: calc(-1 * var(--sidebar-width)); }
            .sidebar.active { margin-left: 0; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

    <div class="sidebar d-flex flex-column p-3" id="sidebar">
        <div class="text-center mb-5 pb-4 border-bottom">
            <h2 class="text-primary mb-1 fw-bold">
                <i class="fas fa-rocket me-2"></i>ZBTech
            </h2>
            <small class="text-muted">Zyro Bandung Tech</small>
        </div>

        <nav class="flex-column flex-grow-1">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt me-3"></i> Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="{{ route('projects.index') }}">
                <i class="fas fa-project-diagram me-3"></i> Projects
            </a>
            <a class="nav-link {{ request()->routeIs('teams.*') ? 'active' : '' }}" href="{{ route('teams.index') }}">
                <i class="fas fa-users me-3"></i> Team
            </a>
            <a class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}" href="{{ route('clients.index') }}">
                <i class="fas fa-user-check me-3"></i> History Klien
            </a>
        </nav>

        <div class="mt-auto pt-4 border-top">
            <div class="user-profile mb-4">
                <div class="d-flex align-items-center p-3 bg-light rounded shadow-sm position-relative">
                    <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" id="photoForm">
                        @csrf
                        <input type="file" name="photo" id="photoInput" class="d-none" onchange="document.getElementById('photoForm').submit()">
                    </form>
                    
                    <div class="position-relative cursor-pointer" onclick="document.getElementById('photoInput').click()" title="Ganti Foto">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('uploads/avatars/' . Auth::user()->avatar) }}" class="rounded-circle me-3 border border-2 border-primary" style="width: 45px; height: 45px; object-fit: cover;">
                        @else
                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        <div class="position-absolute bottom-0 end-0 bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 18px; height: 18px; margin-right: 12px;">
                            <i class="fas fa-camera text-primary" style="font-size: 10px;"></i>
                        </div>
                    </div>
                    
                    <div class="overflow-hidden">
                        <div class="fw-bold text-truncate">{{ Auth::user()->name }}</div>
                        <div class="text-muted small text-truncate">Admin/Owner</div>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="nav-link text-danger w-100 text-start bg-transparent border-0 py-2">
                    <i class="fas fa-sign-out-alt me-3"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <nav class="navbar navbar-top navbar-expand-lg">
            <div class="container-fluid">
                <button class="btn btn-link d-md-none" id="sidebarToggle">
                    <i class="fas fa-bars fa-2x text-dark"></i>
                </button>
                
                <div class="ms-auto d-flex align-items-center">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center position-relative" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell fs-5"></i>
                            @if($notifications->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                                    {{ $notifications->count() }}
                                </span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0" style="width: 320px;">
                            <div class="p-3 border-bottom bg-light rounded-top">
                                <h6 class="mb-0 fw-bold">Notifikasi Terkini</h6>
                            </div>
                            <div class="notification-list" style="max-height: 400px; overflow-y: auto;">
                                @forelse($notifications as $notif)
                                    <a class="dropdown-item d-flex align-items-start p-3 border-bottom" href="{{ $notif['url'] }}">
                                        <div class="bg-light rounded-circle p-2 me-3">
                                            <i class="{{ $notif['icon'] }} fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold small">{{ $notif['title'] }}</div>
                                            <div class="text-muted small mb-1">{{ $notif['message'] }}</div>
                                            <div class="text-primary" style="font-size: 11px;">{{ $notif['time'] }}</div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-4 text-center text-muted">
                                        <i class="fas fa-bell-slash d-block mb-2 fs-3"></i>
                                        <small>Tidak ada notifikasi baru</small>
                                    </div>
                                @endforelse
                            </div>
                            <div class="p-2 text-center bg-light rounded-bottom">
                                <small class="text-primary fw-bold">Zyro Tech Live Report</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="container-fluid py-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Toggle Sidebar for Mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Currency Formatting
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
        }

        document.querySelectorAll('.currency-input').forEach(function(input) {
            input.addEventListener('keyup', function(e) {
                this.value = formatRupiah(this.value);
            });
        });

        // Strip dots before form submission
        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function() {
                form.querySelectorAll('.currency-input').forEach(function(input) {
                    input.value = input.value.replace(/\./g, '');
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
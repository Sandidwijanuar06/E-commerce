<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central Store | High Quality Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');

        :root { 
            --primary-color: #1a1a1a; 
            --accent-color: #ff4757; 
            --soft-bg: #f8f9fa;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--soft-bg);
            color: #333;
        }

        .navbar {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.9) !important;
        }

        /* --- STYLING LOGO & BRAND --- */
        .navbar-brand { 
            font-weight: 800; 
            letter-spacing: -1px; 
            color: var(--primary-color) !important; 
            font-size: 1.5rem; 
            display: flex; /* Membuat logo dan teks sejajar */
            align-items: center;
        }

        .logo-img {
            height: 35px; /* Sesuaikan tinggi logo di sini */
            width: auto;
            margin-right: 10px; /* Jarak antara logo dan teks */
        }
        /* --------------------------- */
        
        .search-bar {
            background-color: #f1f2f6;
            border: none;
            padding: 0.6rem 1.5rem;
            transition: all 0.3s;
        }

        .search-bar:focus {
            background-color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(255, 71, 87, 0.1);
            border-color: var(--accent-color);
        }

        .nav-icon { color: var(--primary-color); font-size: 1.2rem; transition: 0.3s; }
        .nav-icon:hover { color: var(--accent-color); }

        .product-card { 
            border: none; 
            border-radius: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            overflow: hidden;
        }

        .product-card:hover { 
            transform: translateY(-10px); 
            box-shadow: 0 20px 40px rgba(0,0,0,0.08); 
        }

        .btn-accent { 
            background: var(--accent-color); 
            color: white; 
            border-radius: 12px; 
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border: none;
        }

        .btn-accent:hover { background: #ff2e44; color: white; }

        footer { background: #1a1a1a; }
        .footer-link { color: #aaa; text-decoration: none; transition: 0.3s; }
        .footer-link:hover { color: #fff; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand" href="/">
                {{-- Sisipkan Logo di sini --}}
                <img src="{{ asset('images/Logo.png') }}" alt="Logo" class="logo-img">
                CENTRAL<span style="color: var(--accent-color)">STORE</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navContent">
                <div class="d-flex flex-grow-1 mx-lg-5 my-3 my-lg-0">
                    <div class="input-group">
                        <input type="text" class="form-control search-bar rounded-pill" placeholder="Cari barang idamanmu...">
                    </div>
                </div>
                
                <div class="navbar-nav align-items-center">
                    <a class="nav-link position-relative px-3" href="/cart">
                        <i class="fa-solid fa-bag-shopping nav-icon"></i>
                        <span class="position-absolute top-1 start-75 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">3</span>
                    </a>
                    
                    @guest
                        <a class="btn btn-outline-dark ms-2 rounded-pill px-4" href="/login">Login</a>
                    @else
                        <div class="dropdown ms-3">
                            <a class="nav-link dropdown-toggle fw-600 d-flex align-items-center" href="#" id="userDrop" data-bs-toggle="dropdown">
                                <i class="far fa-user-circle fa-lg me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                                <li><a class="dropdown-item" href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-danger border-0 bg-transparent w-100 text-start px-3 py-2">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="text-white pt-5 pb-4 mt-5">
        <div class="container text-center">
            <div class="d-flex justify-content-center align-items-center mb-4">
                <img src="{{ asset('images/Logo.png') }}" alt="Logo" class="logo-img" style="filter: brightness(0) invert(1);">
                <h5 class="fw-bold mb-0">CENTRAL STORE</h5>
            </div>
            <div class="mb-4">
                <a href="#" class="footer-link mx-3">Home</a>
                <a href="#" class="footer-link mx-3">Shop</a>
                <a href="#" class="footer-link mx-3">Categories</a>
                <a href="#" class="footer-link mx-3">Contact</a>
            </div>
            <p class="text-muted small">&copy; 2026 Enterprise Central. Built for Excellence.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
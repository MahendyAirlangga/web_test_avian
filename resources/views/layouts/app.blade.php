<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content=" Pencatatan Transaksi">
    <meta name="keywords" content="Pencatatan Transaksi">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <title>Pencatatan Transaksi - @yield('title')</title>

    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    
    <!-- CSS Smart Monitoring -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/fontawesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/icofont.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/themify.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/flag-icon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/feather-icon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    @stack('styles')
    <style>
        /* Custom animation for greeting card */
        .profile-greeting {
            background-image: url('https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            position: relative;
            z-index: 1;
            border: none;
            overflow: hidden;
            color: #fff !important;
            transition: all 0.5s ease;
        }
        .profile-greeting::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, rgba(36, 105, 92, 0.9) 0%, rgba(0, 0, 0, 0.4) 100%);
            z-index: -1;
        }
        .profile-greeting:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        .profile-greeting h4, .profile-greeting p {
            color: #fff !important;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        /* Floating animation for greeting */
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .profile-vector img {
            animation: floating 3s ease-in-out infinite;
        }

        /* Blinking animation for live status */
        .blink {
            animation: blinker 1.5s linear infinite;
        }
        @keyframes blinker {
            50% { opacity: 0.3; }
        }

        /* Pulse animation for monitoring icons */
        .pulse-icon {
            animation: pulse-red 2s infinite;
        }
        @keyframes pulse-red {
            0% { transform: scale(0.95); text-shadow: 0 0 0 rgba(255, 255, 255, 0.7); }
            70% { transform: scale(1); text-shadow: 0 0 0 10px rgba(255, 255, 255, 0); }
            100% { transform: scale(0.95); text-shadow: 0 0 0 rgba(255, 255, 255, 0); }
        }
        /* Balanced Spacing */
        .page-header {
            margin-top: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 15px !important;
        }
        .page-body {
            padding-top: 0px !important;
        }

        /* Dark Mode Navbar Fixes */
        .dark-only .logo-wrapper h5,
        .dark-only .logo-wrapper a h5 {
            color: #ffffff !important;
        }
        .dark-only .nav-link-custom {
            color: #c8d6e5 !important;
        }
        .dark-only .nav-link-custom:hover,
        .dark-only .nav-item-custom:hover i {
            color: #7effd4 !important;
        }
        .dark-only .page-main-header {
            background-color: #1a1f26 !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.4) !important;
        }
    </style>
    
    @stack('css')
    <script>
        // Apply theme IMMEDIATELY before ANY rendering to prevent flash
        (function() {
            const savedTheme = localStorage.getItem('body-class');
            if (savedTheme === 'dark-only') {
                document.documentElement.classList.add('dark-only');
            }
        })();
    </script>
</head>
<body>
    <!-- Loader starts-->
    <div class="loader-wrapper">
        <div class="theme-loader"></div>
    </div>
    <!-- Loader ends-->
    
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-sidebar" id="pageWrapper">
        
        <!-- Navbar / Header -->
        @include('layouts.navbar')
        
        <!-- Page Body Start-->
        <div class="page-body-wrapper sidebar-icon" id="pageBodyWrapper" style="padding-top:65px !important;">
            
            <!-- Sidebar Navigation -->
            @include('layouts.sidebar')
            
            <div class="page-body" {!! Request::is('monitoring/map') ? 'style="margin-left: 0 !important; width: 100% !important; padding: 0 !important; margin-top: 0 !important;"' : '' !!}>
                @yield('content')
            </div>
            
            <!-- Footer -->
            @include('layouts.footer')
            
        </div>
    </div>
    
    <!-- latest jquery-->
    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js') }}"></script>

    <!-- DataTables Buttons: Excel & PDF export -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <style>
        /* DataTables Buttons custom styling */
        div.dt-buttons {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }
        div.dt-buttons .btn {
            font-size: 0.8rem;
            padding: 5px 12px;
            border-radius: 6px;
            font-weight: 500;
            box-shadow: none !important;
            outline: none !important;
            border: none;
        }
        div.dt-buttons .btn:focus,
        div.dt-buttons .btn:active {
            box-shadow: none !important;
        }
        div.dt-buttons .btn-success { background-color: #1d8348; color: #fff; }
        div.dt-buttons .btn-success:hover { background-color: #17693b; }
        div.dt-buttons .btn-danger  { background-color: #c0392b; color: #fff; }
        div.dt-buttons .btn-danger:hover  { background-color: #a93226; }
        div.dt-buttons .btn-secondary { background-color: #5d6d7e; color: #fff; }
        div.dt-buttons .btn-secondary:hover { background-color: #4d5f6f; }
    </style>
    
    @stack('scripts')

    <script>
        // Global CSRF token setup for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    
    <script>
        $(document).ready(function() {
            // Success Alert
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false,
                    background: '#1a1f26',
                    color: '#fff'
                });
            @endif

            // Error Alert
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Peringatan!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#24695c',
                    background: '#1a1f26',
                    color: '#fff'
                });
            @endif

            // Delete Confirmation
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                let form = $(this).closest('form');
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#24695c',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    background: '#1a1f26',
                    color: '#fff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Dark Mode Persistence Fix
        (function() {
            const savedBodyClass = localStorage.getItem('body-class');
            
            function forceApplyTheme() {
                if (savedBodyClass === 'dark-only') {
                    document.documentElement.classList.add('dark-only');
                    document.body.classList.add('dark-only');
                    if ($('.mode i').length) {
                        $('.mode i').removeClass('fa-moon-o').addClass('fa-lightbulb-o');
                    }
                } else {
                    document.body.classList.remove('dark-only');
                    document.documentElement.classList.remove('dark-only');
                }
            }

            // Apply on DOM ready - runs AFTER config.js
            $(document).ready(function() {
                // Apply immediately on ready
                forceApplyTheme();

                // Re-apply after a short delay to override any template scripts
                setTimeout(forceApplyTheme, 100);
                setTimeout(forceApplyTheme, 300);

                // Track mode toggle clicks and save correct state
                $('.mode').on('click', function() {
                    setTimeout(function() {
                        const isDark = document.body.classList.contains('dark-only');
                        localStorage.setItem('body-class', isDark ? 'dark-only' : 'light-only');
                    }, 350);
                });
            });
        })();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        $(document).ready(function() {
            AOS.init({
                duration: 800,
                once: true,
            });
        });
    </script>
</body>
</html>

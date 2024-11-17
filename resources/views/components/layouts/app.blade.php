<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">

    <link rel="shortcut icon" href="{{ $website_favicon ?? asset('favicon/favicon.webp') }}" type="image/x-icon">
    <meta name="description" content="{{ $website_description ?? $website_name }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')

    <title>{{ $website_name ?? 'set title' }}{{ $subtitle ? ' - ' . $subtitle : '- set subtitle' }}</title>
</head>

<body class="bg-body-secondary">
    <nav class="navbar navbar-expand-lg navbar-white bg-white" aria-label="Offcanvas navbar large">
        <div class="container fs-6">
            <a class="navbar-brand" href="/">
                @if ($website_logo != '')
                    <img src="{{ asset('storage/' . $website_logo) }}" alt="Logo" width="30" height="24"
                        class="d-inline-block align-text-top">
                @endif
                {{ $website_name ?? 'set title' }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar2"
                aria-controls="offcanvasNavbar2" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end " tabindex="-1" id="offcanvasNavbar2"
                aria-labelledby="offcanvasNavbar2Label">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbar2Label">{{ $website_name ?? 'set title' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/">Home</a>
                        </li>
                        @if (Route::is('home'))
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#packages">Travel Package</a>
                            </li>
                            @php
                                // dd($gallery);
                            @endphp
                            @if ($gallery && $gallery == '1')
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page"
                                        href="#gallery">Gallery</a>
                                </li>
                            @endif

                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#contact">About</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="{{ route('listings') }}">Travel
                                    Package</a>
                            </li>
                        @endif


                        @if ((auth()->check() && auth()->user()->role == 'user') || (auth()->check() && auth()->user()->role == 'agent'))
                            <li class="nav-item d-flex align-items-center justify-content-center">
                                <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#000000"
                                        class="bi bi-person" viewBox="0 0 16 16">
                                        <path
                                            d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                                    </svg>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item"
                                            href="{{ route('filament.user.pages.dashboard') }}">Dashboard</a></li>
                                    <li>
                                        <form action="{{ route('filament.user.auth.logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item"
                                                style="background: none; border: none; margin: 0; cursor: pointer;">
                                                Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('filament.user.auth.login') }}">
                                    Login
                                </a>
                            </li>
                        @endif

                    </ul>

                </div>
            </div>
        </div>
    </nav>
    <main>
        {{ $slot }}
    </main>
    <footer class="bg-black text-white pt-4">
        <div
            class="container d-flex justify-content-md-between flex-column flex-md-row align-items-center align-items-md-start">
            <p class="text-center d-flex justify-content-center align-items-center fs-6">
                {{ $copyright ?? 'set title' }}
            </p>
            <div class="d-flex column-gap-3 d-flex justify-content-center align-items-center mb-4 mb-md-0">
                {{ $socmed }}
            </div>
        </div>
    </footer>
    <script src="{{ asset('vendor/bootstrap/js/popper.min.js') }}"></script>

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    @stack('scripts')

</body>

</html>

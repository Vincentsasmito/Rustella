<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rustella</title>

    <!-- CSRF token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- jQuery & Bootstrap CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
      crossorigin="anonymous"
    >

    <style>
        body { background-color: #EFE9E1; }
        .custom-hr {
            margin: 3rem 0; height: 2px;
            background-color: #322D29; border: none;
            opacity: 0.75;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <nav class="navbar navbar-expand-lg py-4" style="background-color: #D1C7BD;">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto gap-4">
                    <li class="nav-item"><a class="nav-link active" href="#">About Us</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Our Services</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('homepage') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('products.catalogue') }}">Catalogue</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Contact Us</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="text-center my-5">
            <img src="/WebsiteStockImage/Rustella.png"
                 alt="Rustella Florist Logo"
                 class="img-fluid"
                 style="max-width: 150px;">
        </div>
    </div>

    {{-- remove the extra semicolon here --}}
    @yield('content')

    <!-- Bootstrap JS bundle -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
      crossorigin="anonymous"
    ></script>

    <!-- this is where all your @push('scripts') blocks will appear -->
    @stack('scripts')
</body>
</html>

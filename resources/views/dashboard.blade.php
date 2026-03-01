<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name') }} &middot; DuitNow QR </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root,
        [data-bs-theme="light"],
        [data-bs-theme="dark"] {
            --bs-primary: #ED2E67;
            --bs-primary-rgb: 237, 46, 103;
        }

        .btn-primary {
            --bs-btn-color: #fff;
            --bs-btn-bg: #ED2E67;
            --bs-btn-border-color: #ED2E67;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #d8285d;
            --bs-btn-hover-border-color: #d8285d;
            --bs-btn-focus-shadow-rgb: 237, 46, 103;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #c62454;
            --bs-btn-active-border-color: #c62454;
            --bs-btn-disabled-color: #fff;
            --bs-btn-disabled-bg: #ED2E67;
            --bs-btn-disabled-border-color: #ED2E67;
        }

        .btn-outline-primary {
            --bs-btn-color: #ED2E67;
            --bs-btn-border-color: #ED2E67;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #ED2E67;
            --bs-btn-hover-border-color: #ED2E67;
            --bs-btn-focus-shadow-rgb: 237, 46, 103;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #d8285d;
            --bs-btn-active-border-color: #d8285d;
            --bs-btn-disabled-color: #ED2E67;
            --bs-btn-disabled-bg: transparent;
            --bs-btn-disabled-border-color: #ED2E67;
            --bs-gradient: none;
        }

        .form-check-input:checked {
            background-color: #ED2E67;
            border-color: #ED2E67;
        }

        .form-check-input:focus {
            border-color: #ED2E67;
            box-shadow: 0 0 0 .25rem rgba(237, 46, 103, .25);
        }

        .navbar .nav-link.router-link-active,
        .navbar .nav-link.router-link-exact-active,
        .navbar .nav-link.active {
            color: #ED2E67;
            font-weight: 700;
        }

        body,
        button,
        input,
        select,
        textarea {
            font-family: 'Rubik', sans-serif;
        }
    </style>
</head>

<body class="bg-body-tertiary">
    <div id="app"></div>
    <script>
        window.DNQR_DASHBOARD = {
            apiBase: '{{ route('duitnowqr.dashboard.index') }}'.replace(/\/$/, '') + '/api',
            isProduction: {{ app()->environment('production') ? 'true' : 'false' }},
            csrfToken: '{{ csrf_token() }}',
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.5.13/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-router@4.5.0/dist/vue-router.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.8.4/dist/axios.min.js"></script>
    <script type="module" src="{{ route('duitnowqr.dashboard.assets', ['file' => 'app.vuejs']) }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

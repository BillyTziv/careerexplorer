<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="author" content="CareerExplorer">
        <meta name="keyword" content="career, explorer, test, job, profession, orientation, careerexplorer, career explorer, career test, job test, profession test, orientation test">
        <meta name="description" content="Δοκίμασε το αξιόπιστο τεστ επαγγελματικού προσανατολισμού μας και ανακάλυψε την ιδανική καριέρα σου σε μόλις λίγα λεπτά.">

        <!-- CSRF Token -->
        <title inertia>{{ config('app.name', 'CareerExplorer') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link id="theme-link" href="/layout/styles/theme/theme-light/indigo/theme.css" rel="stylesheet"/>

        <!-- Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-K1D451YJDT"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-K1D451YJDT');
        </script>
        
        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>

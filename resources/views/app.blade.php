@php
    use App\Models\Company;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" class="rtl" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        {{-- <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script> --}}

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        {{-- <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style> --}}

        <title inertia>{{ config('app.name', 'Laravel') }}</title>
        @if (Company::first() && Company::first()->image)
            <link rel="shortcut icon" href="{{asset('storage/'.Company::find(1)->image->url)}}" type="image/x-icon">
        @endif
        {{-- <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" /> --}}

        @routes
        @vite(['resources/js/app.ts'])
        @inertiaHead
    </head>
    <body class="opacity-0 transition-opacity duration-300">
        <div id="initial-loader" class="fixed inset-0 flex items-center justify-center bg-white dark:bg-gray-900 z-50">
            <span class="text-gray-500 dark:text-gray-300 text-lg">در حال بارگذاری...</span>
        </div>

        @inertia


    </body>

</html>

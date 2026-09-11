<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="theme-color" content="#166534" />
<link rel="manifest" href="{{ asset('manifest.webmanifest') }}" />
<link rel="icon" href="{{ asset('icons/pwa-192.svg') }}" type="image/svg+xml" />
<link rel="apple-touch-icon" href="{{ asset('icons/pwa-192.svg') }}" />

@head
@fonts
@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles

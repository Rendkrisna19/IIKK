<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Security App - PT MNA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mna: { dark: '#004B49', teal: '#006C68', accent: '#C5A065' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-900 h-screen w-screen overflow-hidden flex justify-center">
    <div class="w-full max-w-md bg-gray-50 h-full relative shadow-2xl overflow-hidden flex flex-col">
        @yield('content')
    </div>
</body>
</html>
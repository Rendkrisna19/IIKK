<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-IKK System Wilmar MNA</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        mna: {
                            dark: '#004B49',   /* Hijau Tua Wilmar */
                            teal: '#006C68',   /* Warna Utama */
                            light: '#E6F2F2',  /* Background Menu Aktif */
                            accent: '#C5A065', /* Gold/Kuning Aksen */
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Animasi halus untuk input */
        .input-transition { transition: border-color 0.3s ease-in-out; }
        
        /* Animasi pergerakan background (Zoom & Pan) */
        @keyframes moveBackground {
            0% { transform: scale(1) translate(0, 0); }
            50% { transform: scale(1.1) translate(-15px, -15px); }
            100% { transform: scale(1) translate(0, 0); }
        }
        .animate-bg {
            animation: moveBackground 25s infinite alternate ease-in-out;
        }

        /* Animasi Angin Hijau (Glow Berjalan) */
        @keyframes wind1 {
            0% { transform: translate(0, 0) scale(1) rotate(0deg); opacity: 0.3; }
            33% { transform: translate(40px, -50px) scale(1.2) rotate(120deg); opacity: 0.7; }
            66% { transform: translate(-20px, 30px) scale(0.8) rotate(240deg); opacity: 0.5; }
            100% { transform: translate(0, 0) scale(1) rotate(360deg); opacity: 0.3; }
        }
        @keyframes wind2 {
            0% { transform: translate(0, 0) scale(1) rotate(0deg); opacity: 0.4; }
            33% { transform: translate(-50px, 40px) scale(1.1) rotate(-120deg); opacity: 0.6; }
            66% { transform: translate(30px, -40px) scale(0.9) rotate(-240deg); opacity: 0.3; }
            100% { transform: translate(0, 0) scale(1) rotate(-360deg); opacity: 0.4; }
        }
        .animate-wind-1 { animation: wind1 12s infinite alternate ease-in-out; }
        .animate-wind-2 { animation: wind2 15s infinite alternate ease-in-out; }

        /* Hilangkan autofill background warna default browser */
        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 30px transparent inset !important;
            -webkit-text-fill-color: #f3f4f6 !important; /* Disesuaikan ke warna terang */
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>
<body class="antialiased font-sans">

    <div class="min-h-screen relative flex items-center justify-center overflow-hidden bg-gray-900">

        <div class="absolute inset-0 z-0 overflow-hidden">
           <img src="{{ asset('assets/images/baground.png') }}" alt="Wilmar Background" 
                 class="w-full h-full object-cover animate-bg">
            
            <div class="absolute inset-0 bg-gradient-to-br from-gray-900/90 via-mna-dark/70 to-gray-800/80 mix-blend-multiply"></div>
        </div>

        <div class="absolute inset-0 z-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTEgMWgydjJIMXYtMnptNCAwaDJ2Mkg1di0yem00IDBoMnYySDl2LTJ6bTQgMGgydjJIMTN2LTJ6bTQgMGgydjJIMTd2LTJ6TTEgNWgydjJIMXYtMnptNCAwaDJ2Mkg1di0yem00IDBoMnYySDl2LTJ6bTQgMGgydjJIMTN2LTJ6bTQgMGgydjJIMTd2LTJ6TTEgOWgydjJIMXYtMnptNCAwaDJ2Mkg1di0yem00IDBoMnYySDl2LTJ6bTQgMGgydjJIMTN2LTJ6bTQgMGgydjJIMTd2LTJ6TTEgMTNoMnYySDF2LTJ6bTQgMGgydjJIMTV2LTJ6bTQgMGgydjJIMXYtMnptNCAwaDJ2Mkg1di0yem00IDBoMnYySDl2LTJ6TTEgMTdoMnYySDF2LTJ6bTQgMGgydjJIMTV2LTJ6bTQgMGgydjJIMXYtMnptNCAwaDJ2Mkg1di0yem00IDBoMnYySDl2LTJ6IiBmaWxsPSIjZmZmZmZmIiBmaWxsLW9wYWNpdHk9IjAuNCIgZmlsbC1ydWxlPSJldmVub2RkIi8+PC9zdmc+')]"></div>

        <div class="relative z-10 w-full max-w-[420px] p-6">
            
            <div class="absolute top-0 -left-12 w-64 h-64 bg-mna-teal rounded-full mix-blend-screen filter blur-[70px] animate-wind-1"></div>
            <div class="absolute -bottom-10 -right-12 w-72 h-72 bg-mna-dark rounded-full mix-blend-screen filter blur-[80px] animate-wind-2"></div>

            <div class="relative z-20 bg-white/10 backdrop-blur-2xl border border-white/20 rounded-3xl shadow-[0_8px_32px_0_rgba(0,0,0,0.5)] overflow-hidden p-8">
                
                <div class="text-center mb-6">
                    <img src="https://companieslogo.com/img/orig/F34.SI_BIG-9bf6d287.png?t=1652516639" alt="Wilmar MNA Logo" 
                         class="h-16 mx-auto mb-4 drop-shadow-md brightness-0 invert opacity-90"> <h2 class="text-3xl font-bold text-gray-100 tracking-tight">Login</h2>
                    <p class="text-gray-200 text-sm mt-1 font-medium">E-IKK System Wilmar MNA</p>
                </div>

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-200 bg-green-900/50 backdrop-blur-md p-3 rounded-xl border border-green-500/30">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-4 font-medium text-sm text-red-200 bg-red-900/50 backdrop-blur-md p-3 rounded-xl border border-red-500/30">
                        Gagal Login. Periksa email dan password Anda.
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="relative group">
                        <label for="email" class="block text-sm font-semibold text-gray-200 mb-1">Email</label>
                        <div class="relative">
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                                class="input-transition w-full bg-transparent border-b-2 border-gray-400/50 text-gray-100 font-medium py-2 pr-10 focus:outline-none focus:border-mna-teal placeholder-gray-300/50"
                                placeholder="contoh@wilmar.co.id">
                            
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="relative group" x-data="{ show: false }">
                        <label for="password" class="block text-sm font-semibold text-gray-200 mb-1">Password</label>
                        <div class="relative">
                            <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                                class="input-transition w-full bg-transparent border-b-2 border-gray-400/50 text-gray-100 font-medium py-2 pr-10 focus:outline-none focus:border-mna-teal placeholder-gray-300/50"
                                placeholder="••••••••">
                            
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-2 flex items-center text-gray-300 hover:text-white focus:outline-none transition-colors">
                                <svg x-show="!show" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                                <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-2">
                        <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-400 text-mna-teal shadow-sm focus:ring-mna-teal cursor-pointer transition bg-transparent" name="remember">
                            <span class="ml-2 text-xs font-semibold text-gray-300 group-hover:text-white transition-colors">Remember me</span>
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a class="text-xs text-gray-300 hover:text-white font-semibold transition-colors" href="{{ route('password.request') }}">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                            class="w-full flex justify-center items-center py-3 px-6 border border-transparent rounded-lg text-base font-bold text-white bg-mna-dark hover:bg-mna-teal focus:outline-none focus:ring-4 focus:ring-mna-teal/40 shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            Login
                        </button>
                    </div>
                </form>
                
            </div>
        </div>

        <div class="absolute bottom-4 text-center w-full z-10">
            <p class="text-white/60 text-xs font-medium tracking-wide">
                &copy; {{ date('Y') }} PT Multi Nabati Asahan<br>Asahan, Sumatera Utara
            </p>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
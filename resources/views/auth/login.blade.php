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
        .input-transition { transition: all 0.3s ease-in-out; }
    </style>
</head>
<body class="antialiased font-sans">

    <div class="min-h-screen relative flex items-center justify-center overflow-hidden bg-gray-900">

        <div class="absolute inset-0 z-0">
            <img src="https://assets.theedgemarkets.com/Wilmar-International-Ltd_www.wilmar-international.com__3_0.jpg?a.SS3ntqveg8OYA.eX63KtbOA9SWFkjB" alt="Wilmar Background" 
                 class="w-full h-full object-cover scale-105 blur-[2px]">
            
            <div class="absolute inset-0 bg-gradient-to-br from-mna-dark/90 via-mna-dark/70 to-mna-teal/50 mix-blend-multiply"></div>
        </div>

        <div class="absolute inset-0 z-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTEgMWgydjJIMXYtMnptNCAwaDJ2Mkg1di0yem00IDBoMnYySDl2LTJ6bTQgMGgydjJIMTN2LTJ6bTQgMGgydjJIMTd2LTJ6TTEgNWgydjJIMXYtMnptNCAwaDJ2Mkg1di0yem00IDBoMnYySDl2LTJ6bTQgMGgydjJIMTN2LTJ6bTQgMGgydjJIMTd2LTJ6TTEgOWgydjJIMXYtMnptNCAwaDJ2Mkg1di0yem00IDBoMnYySDl2LTJ6bTQgMGgydjJIMTN2LTJ6bTQgMGgydjJIMTd2LTJ6TTEgMTNoMnYySDF2LTJ6bTQgMGgydjJIMTV2LTJ6bTQgMGgydjJIMXYtMnptNCAwaDJ2Mkg1di0yem00IDBoMnYySDl2LTJ6TTEgMTdoMnYySDF2LTJ6bTQgMGgydjJIMTV2LTJ6bTQgMGgydjJIMXYtMnptNCAwaDJ2Mkg1di0yem00IDBoMnYySDl2LTJ6IiBmaWxsPSIjZmZmZmZmIiBmaWxsLW9wYWNpdHk9IjAuNCIgZmlsbC1ydWxlPSJldmVub2RkIi8+PC9zdmc+')]"></div>


        <div class="relative z-10 w-full max-w-[450px] p-4">
            
            <div class="absolute -inset-2 bg-gradient-to-r from-mna-teal to-mna-accent rounded-[2rem] blur-xl opacity-30 md:opacity-40"></div>

            <div class="relative bg-white/95 backdrop-blur-sm rounded-[2rem] shadow-2xl overflow-hidden">
                
                <div class="pt-10 pb-6 px-8 text-center">
                    <img src="https://companieslogo.com/img/orig/F34.SI_BIG-9bf6d287.png?t=1652516639" alt="Wilmar MNA Logo" 
                         class="h-20 mx-auto mb-6 drop-shadow-sm hover:scale-105 transition-transform">
                    
                    <h2 class="text-2xl font-bold text-mna-dark tracking-tight">E-IKK System</h2>
                    <p class="text-gray-500 text-sm mt-2">PT Multi Nabati Asahan (Wilmar Group)</p>
                </div>

                <div class="p-8 pt-2">

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-xl">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-4 font-medium text-sm text-red-600 bg-red-50 p-3 rounded-xl">
                            Gagal Login. Periksa email dan password Anda.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div class="group">
                            <label for="email" class="block text-sm font-semibold text-gray-600 mb-2 ml-1 group-focus-within:text-mna-teal transition-colors">Email Perusahaan</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-mna-teal transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                                    class="input-transition w-full pl-12 pr-4 py-3.5 bg-gray-50 border-2 border-gray-100 text-gray-800 font-medium rounded-xl focus:outline-none focus:border-mna-teal focus:ring-4 focus:ring-mna-teal/10 placeholder-gray-400"
                                    placeholder="contoh@wilmar.co.id">
                            </div>
                        </div>

                        <div class="group">
                            <label for="password" class="block text-sm font-semibold text-gray-600 mb-2 ml-1 group-focus-within:text-mna-teal transition-colors">Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-mna-teal transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                                    class="input-transition w-full pl-12 pr-12 py-3.5 bg-gray-50 border-2 border-gray-100 text-gray-800 font-medium rounded-xl focus:outline-none focus:border-mna-teal focus:ring-4 focus:ring-mna-teal/10 placeholder-gray-400"
                                    placeholder="••••••••">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-mna-teal focus:outline-none">
                                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.059 10.059 0 011.591-3.077"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.116 6.116A10.025 10.025 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.08 10.08 0 01-1.387 2.849M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" /></svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                                <input id="remember_me" type="checkbox" class="rounded-md border-gray-300 text-mna-teal shadow-sm focus:ring-mna-teal cursor-pointer transition" name="remember">
                                <span class="ml-2 text-sm text-gray-600 group-hover:text-mna-teal transition-colors">Ingat saya</span>
                            </label>
                            
                            @if (Route::has('password.request'))
                                <a class="text-sm text-mna-teal hover:text-mna-dark font-semibold underline-offset-4 hover:underline transition-colors" href="{{ route('password.request') }}">
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        <div class="pt-4">
                            <button type="submit" 
                                class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl text-base font-bold text-white bg-gradient-to-r from-mna-dark to-mna-teal hover:from-mna-teal hover:to-mna-dark focus:outline-none focus:ring-4 focus:ring-mna-teal/30 shadow-lg shadow-mna-teal/20 transform hover:-translate-y-0.5 transition-all duration-200">
                                Masuk Aplikasi
                                <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-500">
                        &copy; {{ date('Y') }} Wilmar International. <br>Developed for Internal Use Only.
                    </p>
                </div>
            </div>
        </div>

        <div class="absolute bottom-4 text-center w-full z-10">
            <p class="text-white/60 text-xs font-medium">PT Multi Nabati Asahan - Asahan, Sumatera Utara</p>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'SistemLoginMatDis') }} - Tugas Akhir Matematika Diskrit</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="bg-slate-50 dark:bg-[#0B1120] text-slate-900 dark:text-slate-200 antialiased min-h-screen flex flex-col justify-between selection:bg-cyan-500/30">
        
        <header class="sticky top-0 z-50 w-full backdrop-blur-md bg-white/70 dark:bg-[#0B1120]/80 border-b border-slate-200 dark:border-slate-800 transition-all">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-3 group cursor-default">
                    <div class="bg-gradient-to-br from-cyan-500 to-blue-600 text-white font-mono px-2.5 py-1.5 rounded-lg text-xs font-bold tracking-widest shadow-lg shadow-blue-500/30 group-hover:shadow-blue-500/50 group-hover:-translate-y-0.5 transition-all duration-300">
                        U∩R
                    </div>
                    <span class="font-bold text-lg tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-600 dark:from-white dark:to-slate-400">
                        SistemLoginMatDis
                    </span>
                </div>
                
                @if (Route::has('login'))
                    <nav class="flex items-center gap-5">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-cyan-600 dark:text-cyan-400 hover:text-cyan-700 dark:hover:text-cyan-300 transition-colors">
                                Dashboard &rarr;
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium hover:text-cyan-600 dark:hover:text-cyan-400 transition-colors">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-block bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-5 py-2.5 rounded-lg text-sm font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </header>

        <main class="w-full max-w-6xl mx-auto px-6 py-16 lg:py-24 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center flex-grow">
            
            <div class="lg:col-span-7 space-y-8 text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-50 dark:bg-cyan-900/20 border border-cyan-200 dark:border-cyan-800/50 rounded-full text-xs font-semibold text-cyan-700 dark:text-cyan-400 tracking-wide shadow-sm">
                    <span class="relative flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-cyan-500"></span>
                    </span>
                    Project Matematika Diskrit
                </div>
                
                <h1 class="text-4xl lg:text-5xl lg:leading-[1.2] font-extrabold tracking-tight">
                    Autentikasi Berbasis <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 via-blue-500 to-indigo-500">
                        Himpunan, Relasi & Kombinatorika
                    </span>
                </h1>
                
                <p class="text-base text-slate-600 dark:text-slate-400 leading-relaxed max-w-xl">
                    Sistem validasi multi-lapis yang menerapkan relasi pemetaan <strong>Role-Badge</strong>, perhitungan probabilitas ruang sampel OTP, dan pengamanan kriptografi HMAC yang meminimalisir risiko <em>brute-force</em>.
                </p>
                
                <div class="pt-2 flex flex-col sm:flex-row items-center gap-4">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-cyan-600 to-blue-600 text-white font-semibold rounded-xl shadow-lg shadow-cyan-500/30 hover:shadow-cyan-500/50 hover:-translate-y-1 transition-all duration-300 text-center">
                        Simulasi Login
                    </a>
                    <a href="#metodologi" class="w-full sm:w-auto px-8 py-3.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 font-semibold rounded-xl text-center shadow-sm hover:border-cyan-500 dark:hover:border-cyan-500 hover:text-cyan-600 dark:hover:text-cyan-400 hover:-translate-y-1 transition-all duration-300">
                        Parameter Matematis
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5 bg-[#0F172A] border border-slate-800 rounded-2xl p-6 shadow-2xl shadow-blue-900/20 font-mono text-sm relative group hover:border-blue-500/50 transition-colors duration-500 overflow-hidden">
                <div class="absolute -inset-1 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-2xl blur opacity-0 group-hover:opacity-20 transition duration-1000"></div>
                
                <div class="relative">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-700/50 mb-5">
                        <span class="text-slate-400 text-xs font-semibold">logic_relations.txt</span>
                        <div class="flex gap-2">
                            <span class="w-3 h-3 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.5)]"></span>
                            <span class="w-3 h-3 rounded-full bg-yellow-400 shadow-[0_0_8px_rgba(250,204,21,0.5)]"></span>
                            <span class="w-3 h-3 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.5)]"></span>
                        </div>
                    </div>
                    
                    <div class="space-y-4 text-left leading-relaxed text-slate-300">
                        <div>
                            <p class="text-slate-500 italic mb-1">// Notasi Himpunan Semesta</p>
                            <p><span class="text-cyan-400 font-bold">U</span> = { u | u ∈ <span class="text-yellow-300">tabel users</span> }</p>
                            <p><span class="text-cyan-400 font-bold">R</span> = { Admin, Manager, Staff, User }</p>
                            <p><span class="text-cyan-400 font-bold">OTP</span> = { 000000...999999 }</p>
                        </div>
                        
                        <div>
                            <p class="text-slate-500 italic mb-1">// Relasi & Pemetaan</p>
                            <p><span class="text-blue-400">ValidRoleBadge</span> ⊆ R × <span class="text-purple-400">B_role</span></p>
                            <p><span class="text-blue-400">AuthRelation</span> ⊆ U × <span class="text-emerald-400">Credentials</span></p>
                        </div>
                        
                        <div>
                            <p class="text-slate-500 italic mb-2">// Syarat Autentikasi</p>
                            <div class="bg-slate-900/80 p-4 rounded-xl border border-slate-700/50 leading-loose shadow-inner">
                                <span class="text-pink-500 font-bold">IF</span> (role, badge_id) ∈ <span class="text-blue-400">ValidRoleBadge</span> <br>
                                <span class="text-pink-500 font-bold">AND</span> <span class="text-yellow-300">hmac</span>(otp) == u.<span class="text-purple-400">otp_code</span> <br>
                                <span class="text-pink-500 font-bold">THEN</span> Access = <span class="text-green-400 font-bold">Granted</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <section id="metodologi" class="w-full bg-white dark:bg-[#0B1120] border-t border-slate-200 dark:border-slate-800/50 py-24 relative overflow-hidden">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-cyan-500/10 dark:bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-500/10 dark:bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="max-w-6xl mx-auto px-6 relative z-10">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <h2 class="text-3xl font-extrabold tracking-tight">Formulasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-500">Matematika & Kriptografi</span></h2>
                    <p class="text-slate-500 dark:text-slate-400 mt-4 text-base">Alur Decision Tree sistem dipetakan secara akurat ke dalam 3 area utama teori diskrit.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="group p-8 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-3xl hover:border-cyan-500/50 dark:hover:border-cyan-500/50 hover:shadow-2xl hover:shadow-cyan-500/10 hover:-translate-y-2 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-cyan-500/20 to-transparent rounded-bl-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="inline-flex items-center justify-center w-14 h-14 bg-cyan-100 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 rounded-2xl font-bold font-mono text-xl mb-6 shadow-sm group-hover:scale-110 transition-transform duration-300">
                            ⊆
                        </div>
                        <h3 class="font-bold text-xl mb-3">Logika Himpunan</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Validasi kredensial didasarkan pada relasi himpunan <code class="px-1.5 py-0.5 bg-slate-200 dark:bg-slate-800 rounded text-cyan-600 dark:text-cyan-400 font-mono text-xs">ValidRoleBadge</code>. Akses diverifikasi hanya jika identitas merupakan irisan dari himpunan kredensial valid.
                        </p>
                    </div>

                    <div class="group p-8 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-3xl hover:border-blue-500/50 dark:hover:border-blue-500/50 hover:shadow-2xl hover:shadow-blue-500/10 hover:-translate-y-2 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-blue-500/20 to-transparent rounded-bl-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="inline-flex items-center justify-center h-14 px-4 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-2xl font-bold font-mono text-lg mb-6 shadow-sm group-hover:scale-110 transition-transform duration-300">
                            4×10⁹
                        </div>
                        <h3 class="font-bold text-xl mb-3">Kombinatorika</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Kombinasi pola badge dan ruang OTP menciptakan total ruang vektor otentikasi mencapai 4 miliar kemungkinan, menekan probabilitas tebakan ke angka <strong>0.0001%</strong> per percobaan.
                        </p>
                    </div>

                    <div class="group p-8 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-3xl hover:border-emerald-500/50 dark:hover:border-emerald-500/50 hover:shadow-2xl hover:shadow-emerald-500/10 hover:-translate-y-2 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-emerald-500/20 to-transparent rounded-bl-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="inline-flex items-center justify-center h-14 px-4 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-2xl font-bold font-mono text-lg mb-6 shadow-sm group-hover:scale-110 transition-transform duration-300">
                            ƒ(hmac)
                        </div>
                        <h3 class="font-bold text-xl mb-3">Kriptografi OTP</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Penerapan fungsi <code class="px-1.5 py-0.5 bg-slate-200 dark:bg-slate-800 rounded text-emerald-600 dark:text-emerald-400 font-mono text-xs">hash_equals()</code> untuk cegah <em>timing attack</em>, dikombinasikan dengan pembatasan <em>state</em> maksimal 3 kali kegagalan via <em>Decision Tree</em>.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- <footer class="w-full py-8 text-sm text-slate-500 dark:text-slate-500 border-t border-slate-200 dark:border-slate-800/50 bg-white dark:bg-[#0B1120]">
            <div class="max-w-7xl mx-auto px-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p>&copy; {{ date('Y') }} SistemLoginMatDis. Proyek Tugas Akhir.</p>
                <p class="flex items-center gap-2">
                    Dikembangkan oleh 
                    <span class="px-2 py-1 bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white rounded-md font-semibold text-xs border border-slate-200 dark:border-slate-700">
                        Kelompok Anda
                    </span>
                </p>
            </div>
        </footer> -->

    </body>
</html>
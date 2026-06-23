<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SistemLoginMatDis</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class', // Mendukung dark mode sesuai class di tag html
        }
    </script>
</head>
<body class="bg-slate-50 dark:bg-[#0B1120] text-slate-900 dark:text-slate-200 antialiased min-h-screen transition-colors duration-300">

    <header class="sticky top-0 z-50 w-full backdrop-blur-md bg-white/70 dark:bg-[#0B1120]/80 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-gradient-to-br from-cyan-500 to-blue-600 text-white font-mono px-2.5 py-1.5 rounded-lg text-xs font-bold tracking-widest shadow-lg shadow-blue-500/30">
                    U∩R
                </div>
                <span class="font-bold text-lg tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-600 dark:from-white dark:to-slate-400">
                    SistemLoginMatDis
                </span>
            </div>
            
            <a href="{{ route('logout.perform') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="inline-flex items-center justify-center bg-red-50 dark:bg-red-950/30 hover:bg-red-100 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 px-4 py-2 rounded-xl text-sm font-semibold border border-red-200/50 dark:border-red-900/50 transition-all duration-200">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout.perform') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-6 py-10 space-y-12">
        
        <section class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 md:p-8 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-bl from-cyan-500/10 to-transparent rounded-bl-full pointer-events-none"></div>
            <div>
                <span class="inline-flex items-center gap-1.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 px-3 py-1 rounded-full text-xs font-bold tracking-wider mb-3 uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    {{ $user->role }}
                </span>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Selamat Datang, {{ $user->name }}!</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm md:text-base">Anda berhasil melewati gerbang autentikasi berlapis Matematika Diskrit.</p>
            </div>
            
            <div class="w-full md:w-auto bg-slate-50 dark:bg-slate-950 p-4 rounded-2xl border border-slate-200/60 dark:border-slate-800/60 grid grid-cols-1 sm:grid-cols-3 md:flex md:flex-col gap-3 font-mono text-xs text-slate-600 dark:text-slate-400 minimum-w-[240px]">
                <div><span class="text-slate-400">Badge ID :</span> <span class="text-cyan-500 dark:text-cyan-400 font-bold">{{ $user->badge_id }}</span></div>
                <div><span class="text-slate-400">Email    :</span> <span class="text-slate-800 dark:text-slate-200">{{ $user->email }}</span></div>
                <div><span class="text-slate-400">Role     :</span> <span class="text-slate-800 dark:text-slate-200">{{ $user->role }}</span></div>
            </div>
        </section>

        <section class="space-y-8">
            <div class="border-b border-slate-200 dark:border-slate-800 pb-4">
                <h2 class="text-xl md:text-2xl font-extrabold tracking-tight">
                    Analisis Logika Sistem Autentikasi Anda
                </h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Pembuktian matematis arsitektur login menggunakan prinsip Short-Circuit Evaluation.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm space-y-4">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-lg bg-cyan-100 dark:bg-cyan-900/50 text-cyan-600 dark:text-cyan-400 text-xs font-bold">1</span>
                        Definisi Variabel Proposisi
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 text-sm">
                        <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200/50 dark:border-slate-800/50">
                            <code class="text-cyan-500 dark:text-cyan-400 font-bold text-base">A</code>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Email valid dan terdaftar di database.</p>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200/50 dark:border-slate-800/50">
                            <code class="text-cyan-500 dark:text-cyan-400 font-bold text-base">B</code>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Password sesuai (lolos <code>Hash::check</code>).</p>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200/50 dark:border-slate-800/50">
                            <code class="text-cyan-500 dark:text-cyan-400 font-bold text-base">C</code>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Role sesuai dengan record di database.</p>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200/50 dark:border-slate-800/50">
                            <code class="text-cyan-500 dark:text-cyan-400 font-bold text-base">D</code>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kode OTP valid dan belum kedaluwarsa.</p>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200/50 dark:border-slate-800/50 md:col-span-2">
                            <code class="text-cyan-500 dark:text-cyan-400 font-bold text-base">E</code>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Akun aktif (tidak sedang dikunci sementara).</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-6">
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm flex-1 flex flex-col justify-between">
                        <h3 class="font-bold text-sm uppercase tracking-wider text-slate-400 flex items-center gap-2">
                            <span>2</span> Ekspresi Logika
                        </h3>
                        <div class="my-4 text-center font-mono text-xl md:text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-500">
                            S = A ∧ B ∧ C ∧ D ∧ E
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Operasi konjungsi AND (∧) mewajibkan seluruh proposisi bernilai <span class="text-emerald-500 font-semibold">True</span>.</p>
                    </div>

                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl shadow-sm flex-1 flex flex-col justify-between">
                        <h3 class="font-bold text-sm uppercase tracking-wider text-slate-400 flex items-center gap-2">
                            <span>4</span> Teori Himpunan
                        </h3>
                        <div class="my-4 text-center font-mono text-xl md:text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-indigo-500">
                            H<sub>S</sub> = H<sub>A</sub> ∩ H<sub>B</sub> ∩ H<sub>C</sub> ∩ H<sub>D</sub> ∩ H<sub>E</sub>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Akses sukses berada pada irisan terkecil (<span class="font-mono">∩</span>) dari seluruh himpunan prasyarat.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-lg bg-cyan-100 dark:bg-cyan-900/50 text-cyan-600 dark:text-cyan-400 text-xs font-bold">3</span>
                        Tabel Kebenaran (Short-Circuit Evaluation)
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950 font-mono text-xs text-slate-500 border-b border-slate-200 dark:border-slate-800">
                                <th class="p-4 text-center w-16">Baris</th>
                                <th class="p-4 text-center">A (Email)</th>
                                <th class="p-4 text-center">B (Pass)</th>
                                <th class="p-4 text-center">C (Role)</th>
                                <th class="p-4 text-center">D (OTP)</th>
                                <th class="p-4 text-center">E (Aktif)</th>
                                <th class="p-4 text-center">Status</th>
                                <th class="p-4">Penjelasan Alur di Laravel Controller</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm font-mono">
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/30">
                                <td class="p-4 text-center text-slate-400">1</td>
                                <td class="p-4 text-center text-red-500 font-bold">F</td>
                                <td class="p-4 text-center text-slate-400">—</td>
                                <td class="p-4 text-center text-slate-400">—</td>
                                <td class="p-4 text-center text-slate-400">—</td>
                                <td class="p-4 text-center text-slate-400">—</td>
                                <td class="p-4 text-center"><span class="px-2 py-0.5 bg-red-100 dark:bg-red-950/40 text-red-700 dark:text-red-400 text-xs font-bold rounded">GAGAL</span></td>
                                <td class="p-4 font-sans text-slate-500 dark:text-slate-400">Email tidak ditemukan. Eksekusi berhenti seketika.</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/30">
                                <td class="p-4 text-center text-slate-400">2</td>
                                <td class="p-4 text-center text-emerald-500 font-bold">T</td>
                                <td class="p-4 text-center text-red-500 font-bold">F</td>
                                <td class="p-4 text-center text-slate-400">—</td>
                                <td class="p-4 text-center text-slate-400">—</td>
                                <td class="p-4 text-center text-slate-400">—</td>
                                <td class="p-4 text-center"><span class="px-2 py-0.5 bg-red-100 dark:bg-red-950/40 text-red-700 dark:text-red-400 text-xs font-bold rounded">GAGAL</span></td>
                                <td class="p-4 font-sans text-slate-500 dark:text-slate-400">Email terdaftar, namun password salah. Role dkk dilewati.</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/30">
                                <td class="p-4 text-center text-slate-400">3</td>
                                <td class="p-4 text-center text-emerald-500 font-bold">T</td>
                                <td class="p-4 text-center text-emerald-500 font-bold">T</td>
                                <td class="p-4 text-center text-red-500 font-bold">F</td>
                                <td class="p-4 text-center text-slate-400">—</td>
                                <td class="p-4 text-center text-slate-400">—</td>
                                <td class="p-4 text-center"><span class="px-2 py-0.5 bg-red-100 dark:bg-red-950/40 text-red-700 dark:text-red-400 text-xs font-bold rounded">GAGAL</span></td>
                                <td class="p-4 font-sans text-slate-500 dark:text-slate-400">Email & password benar, namun role tidak cocok.</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/30">
                                <td class="p-4 text-center text-slate-400">4</td>
                                <td class="p-4 text-center text-emerald-500 font-bold">T</td>
                                <td class="p-4 text-center text-emerald-500 font-bold">T</td>
                                <td class="p-4 text-center text-emerald-500 font-bold">T</td>
                                <td class="p-4 text-center text-red-500 font-bold">F</td>
                                <td class="p-4 text-center text-slate-400">—</td>
                                <td class="p-4 text-center"><span class="px-2 py-0.5 bg-red-100 dark:bg-red-950/40 text-red-700 dark:text-red-400 text-xs font-bold rounded">GAGAL</span></td>
                                <td class="p-4 font-sans text-slate-500 dark:text-slate-400">Kredensial dan role benar, namun OTP salah atau kedaluwarsa.</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/30">
                                <td class="p-4 text-center text-slate-400">5</td>
                                <td class="p-4 text-center text-emerald-500 font-bold">T</td>
                                <td class="p-4 text-center text-emerald-500 font-bold">T</td>
                                <td class="p-4 text-center text-emerald-500 font-bold">T</td>
                                <td class="p-4 text-center text-emerald-500 font-bold">T</td>
                                <td class="p-4 text-center text-red-500 font-bold">F</td>
                                <td class="p-4 text-center"><span class="px-2 py-0.5 bg-red-100 dark:bg-red-950/40 text-red-700 dark:text-red-400 text-xs font-bold rounded">GAGAL</span></td>
                                <td class="p-4 font-sans text-slate-500 dark:text-slate-400">OTP divalidasi dengan benar, namun akun sedang dikunci sementara.</td>
                            </tr>
                            <tr class="bg-emerald-50/30 dark:bg-emerald-950/10 hover:bg-emerald-50/50 dark:hover:bg-emerald-950/20">
                                <td class="p-4 text-center text-slate-400 font-bold">6</td>
                                <td class="p-4 text-center text-emerald-500 font-bold">T</td>
                                <td class="p-4 text-center text-emerald-500 font-bold">T</td>
                                <td class="p-4 text-center text-emerald-500 font-bold">T</td>
                                <td class="p-4 text-center text-emerald-500 font-bold">T</td>
                                <td class="p-4 text-center text-emerald-500 font-bold">T</td>
                                <td class="p-4 text-center"><span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 text-xs font-bold rounded">SUKSES</span></td>
                                <td class="p-4 font-sans text-slate-600 dark:text-slate-300 font-semibold">Seluruh syarat terpenuhi. Dashboard terbuka.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-[#0F172A] border border-slate-800 rounded-2xl p-6 shadow-xl text-slate-300 font-mono text-xs md:text-sm relative group overflow-hidden">
                <div class="absolute -inset-1 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-2xl blur opacity-10 pointer-events-none"></div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-800 mb-5">
                    <h3 class="font-bold text-sm text-slate-400 uppercase tracking-wider flex items-center gap-2">
                        <span>5</span> Decision Tree / Pohon Keputusan (Short-Circuit)
                    </h3>
                    <div class="flex gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500/80"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-400/80"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500/80"></span>
                    </div>
                </div>
                
                <div class="overflow-x-auto text-center md:text-left py-4 px-2 bg-slate-950/60 rounded-xl border border-slate-800/80 shadow-inner">
                    <pre class="inline-block text-left leading-relaxed tracking-wide text-cyan-400/90 select-none">
               [Form Login Di-submit]
                         |
                 Apakah Email Ada? (A)
                   /               \
                (No)               (Yes)
                 /                   \
             <span class="text-red-500 font-bold">[GAGAL]</span>             Apakah Password Benar? (B)
                                     /               \
                                  (No)               (Yes)
                                   /                   \
                               <span class="text-red-500 font-bold">[GAGAL]</span>             Apakah Role Cocok? (C)
                                                       /               \
                                                    (No)               (Yes)
                                                     /                   \
                                                 <span class="text-red-500 font-bold">[GAGAL]</span>             Apakah OTP Valid? (D)
                                                                         /               \
                                                                      (No)               (Yes)
                                                                       /                  \
                                                                   <span class="text-red-500 font-bold">[GAGAL]</span>             Apakah Akun Aktif? (E)
                                                                                           /               \
                                                                                        (No)               (Yes)
                                                                                         /                   \
                                                                                     <span class="text-red-500 font-bold">[GAGAL]</span>               <span class="text-emerald-400 font-bold animate-pulse">[SUKSES]</span>
                    </pre>
                </div>
            </div>

        </section>
    </main>

    <footer class="w-full border-t border-slate-200 dark:border-slate-800/50 py-6 text-center text-xs text-slate-400">
        &copy; 2026 {{ config('app.name', 'SistemLoginMatDis') }} — Tugas Akhir Matematika Diskrit
    </footer>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Monika's Kitchen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(20px);}to{opacity:1;transform:translateY(0);} }
        .fade-up { animation: fadeUp 0.6s ease both; }
        .login-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
        }
        input:focus { box-shadow: 0 0 0 3px rgba(249,115,22,0.3); }
        .login-btn:hover { background: #c2410c; transform: translateY(-1px); box-shadow: 0 8px 20px rgba(249,115,22,0.4); }
        .login-btn { transition: all 0.25s; }
    </style>
</head>
<body class="login-bg flex items-center justify-center p-4">

    {{-- Decorative blobs --}}
    <div class="fixed top-20 left-20 w-64 h-64 bg-orange-500 rounded-full opacity-10 blur-3xl pointer-events-none"></div>
    <div class="fixed bottom-20 right-20 w-80 h-80 bg-blue-500 rounded-full opacity-10 blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-sm fade-up">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-white tracking-tighter">MONIKA<span class="text-orange-500">KITCHEN.</span></h1>
            <p class="text-slate-400 text-sm mt-1 font-medium">Admin Panel Login</p>
        </div>

        {{-- Login Card --}}
        <div class="glass-card rounded-2xl p-6 md:p-8">
            @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-sm font-bold px-4 py-3 rounded-xl mb-5">
                ⚠️ {{ session('error') }}
            </div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-sm font-bold text-slate-300 mb-2">📧 Email</label>
                    <input type="email" id="email" name="email" required autofocus
                           placeholder="admin@monikaskitchen.com"
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 rounded-xl bg-slate-800/50 border border-slate-600 text-white placeholder-slate-500 focus:border-orange-500 outline-none text-sm font-semibold transition">
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm font-bold text-slate-300 mb-2">🔒 Password</label>
                    <input type="password" id="password" name="password" required
                           placeholder="••••••••"
                           class="w-full px-4 py-3 rounded-xl bg-slate-800/50 border border-slate-600 text-white placeholder-slate-500 focus:border-orange-500 outline-none text-sm font-semibold transition">
                </div>
                <button type="submit" class="login-btn w-full bg-orange-500 text-white font-bold py-3.5 rounded-xl shadow-lg text-sm">
                    Masuk ke Admin Panel
                </button>
            </form>
        </div>

        <p class="text-center text-slate-500 text-xs mt-5 font-semibold">
            <a href="{{ url('/') }}" class="hover:text-orange-400 transition">← Kembali ke Toko</a>
        </p>
    </div>
</body>
</html>


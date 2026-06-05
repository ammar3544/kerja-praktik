<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Buzzer Monitor v4</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
</head>
<body class="bg-[#0f172a] text-slate-200 font-['Plus_Jakarta_Sans']">

    <div class="min-h-screen flex flex-col items-center justify-center px-4 relative">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-indigo-600/10 blur-[120px] rounded-full"></div>

        <div class="w-full max-w-[450px] z-10">
            <div class="bg-slate-800/40 backdrop-blur-2xl p-8 rounded-[2rem] border border-slate-700/50 shadow-2xl">
                <h2 class="text-2xl font-bold text-white mb-2 text-center">Create Account</h2>
                <p class="text-slate-400 text-sm text-center mb-8">Join the Intelligence Network</p>

                <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2 ml-1">Full Name</label>
                        <input type="text" name="name" class="w-full px-4 py-3 bg-slate-900/60 border border-slate-700 rounded-xl text-white focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all" placeholder="Ammar Siraj" required>
                    </div>

                    <div>
                        <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2 ml-1">Email Address</label>
                        <input type="email" name="email" class="w-full px-4 py-3 bg-slate-900/60 border border-slate-700 rounded-xl text-white focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all" placeholder="admin@intelligence.io" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2 ml-1">Password</label>
                            <input type="password" name="password" class="w-full px-4 py-3 bg-slate-900/60 border border-slate-700 rounded-xl text-white focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all" placeholder="••••••••" required>
                        </div>
                        <div>
                            <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2 ml-1">Confirm</label>
                            <input type="password" name="password_confirmation" class="w-full px-4 py-3 bg-slate-900/60 border border-slate-700 rounded-xl text-white focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold shadow-xl shadow-indigo-600/20 transition-all transform active:scale-[0.98] mt-4">
                        Register Identity
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-slate-400 text-xs">Sudah punya akun? <a href="{{ route('login') }}" class="text-indigo-400 hover:underline">Masuk di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Buzzer Monitor</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>


<body class="bg-gradient-to-br from-slate-900 via-indigo-900 to-slate-900 min-h-screen flex items-center justify-center">


    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-8">

        <div class="text-center mb-6">

            <h1 class="text-2xl font-bold text-slate-800">
                🧠 Buzzer Monitor
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Social Media Intelligence System
            </p>

        </div>


        <form method="POST" action="/login">

            @csrf


            <div class="mb-4">

                <label class="text-sm text-gray-600">
                    Email
                </label>

                <input type="email"
                    name="email"
                    class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                    required>

            </div>


            <div class="mb-6">

                <label class="text-sm text-gray-600">
                    Password
                </label>

                <input type="password"
                    name="password"
                    class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                    required>

            </div>


            <button class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">

                Login

            </button>

        </form>

    </div>

</body>

</html>
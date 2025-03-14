<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen">

    <div class="bg-[#2c3e50] p-8 rounded-lg shadow-lg w-96">
        <div class="text-center mb-6">
            <div class="w-20 h-20 mx-auto flex items-center justify-center bg-gray-700 rounded-full">
                <i class="fa-solid fa-face-grin-wink text-white text-6xl"></i>
            </div>
            <h2 class="text-2xl font-semibold text-white">Selamat Datang!</h2>
            <p class="text-gray-300 text-sm">Masuk sekarang untuk melanjutkan</p>
        </div>        

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-500 text-white rounded">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-gray-300">Email Address</label>
                <div class="flex items-center border rounded-lg px-3 py-2 bg-gray-700">
                    <span class="text-gray-400">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input type="email" name="email" required placeholder="Masukan email" class="w-full bg-transparent outline-none px-2 text-white">
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-300">Password</label>
                <div class="flex items-center border rounded-lg px-3 py-2 bg-gray-700">
                    <span class="text-gray-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" name="password" required placeholder="Masukan password" class="w-full bg-transparent outline-none px-2 text-white">
                </div>
            </div>

            <div class="flex justify-between text-sm mb-4">
                <a href="#" class="text-blue-400 hover:underline">Forget password?</a>
            </div>

            <button type="submit" class="w-full bg-white text-[#2c3e50] py-2 rounded-lg text-lg font-semibold hover:bg-gray-300 transition">Login</button>

            <p class="text-center text-sm text-gray-300 mt-4">
                Don't have an account? <a href="#" class="text-blue-400 font-semibold hover:underline">Sign Up</a>
            </p>
        </form>
    </div>
</body>
</html>

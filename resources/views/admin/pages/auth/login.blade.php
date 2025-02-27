<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <div class="text-center mb-6">
            <div class="w-20 h-20 mx-auto flex items-center justify-center bg-blue-100 rounded-full">
                <i class="fa-solid fa-face-grin-wink text-blue-600 text-6xl"></i>
            </div>
            <h2 class="text-2xl font-semibold text-gray-800">Welcome Back</h2>
            <p class="text-gray-500 text-sm">Log in now to continue</p>
        </div>        

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-600 rounded">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email Address</label>
                <div class="flex items-center border rounded-lg px-3 py-2 bg-gray-100">
                    <span class="text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0a2.25 2.25 0 00-2.25-2.25H4.5a2.25 2.25 0 00-2.25 2.25m19.5 0L12 12.75 3.75 6.75" />
                        </svg>
                    </span>
                    <input type="email" name="email" required placeholder="Enter your email address" class="w-full bg-transparent outline-none px-2">
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <div class="flex items-center border rounded-lg px-3 py-2 bg-gray-100">
                    <span class="text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5v-1.875a4.5 4.5 0 10-9 0V10.5m9 0h1.125A2.625 2.625 0 0121.75 13.125v6.75a2.625 2.625 0 01-2.625 2.625H4.875A2.625 2.625 0 012.25 19.875v-6.75A2.625 2.625 0 014.875 10.5H6" />
                        </svg>
                    </span>
                    <input type="password" name="password" required placeholder="Enter your password" class="w-full bg-transparent outline-none px-2">
                </div>
            </div>

            <div class="flex justify-between text-sm mb-4">
                <a href="#" class="text-blue-500 hover:underline">Forget password?</a>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg text-lg font-semibold hover:bg-blue-700 transition">Login</button>

            <p class="text-center text-sm text-gray-600 mt-4">
                Don't have an account? <a href="#" class="text-blue-500 font-semibold hover:underline">Sign Up</a>
            </p>
        </form>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kasir | Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class=" bg-[#f4f4f4] max-w-md mx-auto h-screen flex justify-center items-center">
    <div class="p-7 rounded-3xl bg-white shadow border w-full">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <h1 class="text-2xl font-semibold text-blue-800">Login</h1>
            <div class="flex flex-col gap-2 mt-4">
                <label for="email" class=" text-gray-600 text-sm">Email </label>
                <input type="email" class="border rounded-lg p-2" name="email">
                @error('email')
                    <span class="invalid-feedback text-red-500 text-sm" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <label for="password" class=" text-gray-600 text-sm">Password </label>
                <input type="password" class="border rounded-lg p-2" name="password">
                @error('password')
                    <span class="invalid-feedback text-red-500 text-sm" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="flex justify-end items-end">
                <button
                    class="bg-blue-800 mt-5 rounded-md text-white text-sm hover:bg-blue-900 focus:backdrop-blur-2xl transition-shadow duration-500 p-2"
                    type="submit">
                    Login
                </button>
            </div>
        </form>
    </div>
</body>
<div>
</div>

</html>

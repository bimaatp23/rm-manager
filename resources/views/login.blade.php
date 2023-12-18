<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body class="font-sans">
    <div class="flex bg-green-500 items-center justify-center h-screen">
        <div class="flex w-full">
            <div class="flex-1 bg-green-500 text-white p-8">
                <h2 class="text-2xl font-semibold mb-4 text-center">SIMPEDE</h2>
            </div>
            <div class="flex-1 text-white p-8">
                <h2 class="text-2xl font-semibold mb-4">Login</h2>
                <form action="{{route("authenticate")}}" method="post">
                    @csrf
                    <div class="mb-4">
                        <label for="username" class="block text-sm font-medium">Username</label>
                        <input type="text" id="username" name="username" class="mt-1 p-2 w-full border rounded text-black" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium">Password</label>
                        <input type="password" id="password" name="password" class="mt-1 p-2 w-full border rounded text-black" required>
                    </div>
                    <div class="mb-4 flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="mr-2">
                        <label for="remember" class="text-sm font-medium">Remember me</label>
                    </div>
                    <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-bold">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

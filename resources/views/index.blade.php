<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield("title", "Admin Dashboard")</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    @yield("style")
</head>

<body class="font-sans">
    <div class="flex h-screen">
        <div class="fixed flex flex-col bg-green-500 text-white w-64 h-full">
            <div class="w-full flex align-middle justify-center h-14">
                <h2 class="text-xl font-semibold p-4">SIMPEDE</h2>
            </div>
            <div class="flex flex-col">
                <ul>
                    <a href="{{route("dashboard")}}">
                        <li class="hover:bg-green-600 {{request()->route()->getName() === "dashboard" ? "bg-green-600" : ""}} px-4 py-2 text-lg">
                            Dashboard
                        </li>
                    </a>
                    <a href="{{route("rekamMedis")}}">
                        <li class="hover:bg-green-600 {{request()->route()->getName() === "rekamMedis" ? "bg-green-600" : ""}} px-4 py-2 text-lg">
                            Rekam Medis
                        </li>
                    </a>
                    <a href="{{route("peminjaman")}}">
                        <li class="hover:bg-green-600 {{request()->route()->getName() === "peminjaman" ? "bg-green-600" : ""}} px-4 py-2 text-lg">
                            Peminjaman
                        </li>
                    </a>
                    <a href="{{route("dokter")}}">
                        <li class="hover:bg-green-600 {{request()->route()->getName() === "dokter" ? "bg-green-600" : ""}} px-4 py-2 text-lg">
                            Dokter
                        </li>
                    </a>
                </ul>
                <ul class="absolute bottom-0 w-full">
                    <a href="{{route("logout")}}">
                        <li class="hover:bg-green-600 px-4 py-2 text-lg">
                            Logout
                        </li>
                    </a>
                </ul>
            </div>
        </div>
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-green-500 text-white flex h-14 align-middle justify-end pr-4 w-full">
                <div class="flex items-center justify-end">
                    <span class="mr-2 font-bold">{{$current->nama}}</span>
                </div>
            </header>
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 ml-64">
                <h1 class="text-2xl font-semibold text-green-800 mb-2">@yield("title", "Admin Dashboard")</h1>
                @yield("content")
            </main>
        </div>
    </div>
</body>

</html>

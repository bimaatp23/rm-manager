<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield("title", "Admin Dashboard")</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        // Function to call the reminder API
        function callReminderApi() {
            $.ajax({
                url: "/api/reminder",
                type: "POST",
                success: function (response) {
                    console.log(response.message);
                },
                error: function (error) {
                    console.error("Error calling API:", error);
                }
            });
        }

        // Call the API in after load
        callReminderApi()

        // Call the API every minute
        setInterval(callReminderApi, 60 * 1000);
    </script>
</head>

<body class="font-sans" onload="notificationAlert('{{$current->notificationIcon}}', '{{$current->notificationMessage}}')">
    <div id="changePasswordModal" class="hidden fixed inset-0 overflow-auto bg-black bg-opacity-40 p-16">
        <div class="bg-white mx-auto my-5 p-4 border border-gray-300 w-96 rounded">
            <form action="{{route("changePassword")}}" method="post">
                @csrf
                @method("put")
                <input type="text" name="username" value="{{$current->username}}" class="hidden">
                <div class="mb-2">
                    <label for="password" class="block text-sm font-medium text-gray-600">Password Lama:</label>
                    <input type="password" name="password" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
                </div>
                <div class="mb-2">
                    <label for="password" class="block text-sm font-medium text-gray-600">Password Baru:</label>
                    <input type="password" name="new_password" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
                </div>
                <div class="mb-2">
                    <label for="password" class="block text-sm font-medium text-gray-600">Ulangi Password Baru:</label>
                    <input type="password" name="renew_password" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
                </div>
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded w-full">
                    Simpan
                </button>
            </form>
        </div>
    </div>
    <div class="flex h-screen">
        <div class="fixed flex flex-col bg-green-500 text-white w-64 h-full">
            <div class="w-full flex align-middle justify-center h-14">
                <h2 class="text-xl font-semibold p-4">SIMPEPE</h2>
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
                    @if ($current->role == "Kepala Puskesmas")
                    <a href="{{route("dokter")}}">
                        <li class="hover:bg-green-600 {{request()->route()->getName() === "dokter" ? "bg-green-600" : ""}} px-4 py-2 text-lg">
                            Dokter
                        </li>
                    </a>
                    <a href="{{route("petugas")}}">
                        <li class="hover:bg-green-600 {{request()->route()->getName() === "petugas" ? "bg-green-600" : ""}} px-4 py-2 text-lg">
                            Petugas
                        </li>
                    </a>
                    @endif
                </ul>
                <ul class="absolute bottom-0 w-full">
                    <a href="#" onclick="openChangePasswordModal()">
                        <li class="hover:bg-green-600 px-4 py-2 text-lg">
                            Change Password
                        </li>
                    </a>
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
                    <span class="mr-2 font-bold">{{$current->nama}} ({{$current->role}})</span>
                </div>
            </header>
            <main class="flex-1 overflow-auto p-4 ml-64">
                <h1 class="text-2xl font-semibold text-green-800 mb-2">@yield("title", "Admin Dashboard")</h1>
                @yield("content")
            </main>
        </div>
    </div>
    <script>
        function openChangePasswordModal() {
            document.getElementById("changePasswordModal").classList.add("block")
            document.getElementById("changePasswordModal").classList.remove("hidden")
        }

        function closeChangePasswordModal() {
            document.getElementById("changePasswordModal").classList.remove("block")
            document.getElementById("changePasswordModal").classList.add("hidden")
        }

        function notificationAlert(notificationIcon, notificationMessage) {
            if (notificationIcon != "" && notificationMessage != "") {
                Swal.fire({
                    title: notificationMessage,
                    icon: notificationIcon,
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        }
    </script>
</body>

</html>

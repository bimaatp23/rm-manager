@extends("index")

@section("title", "Checkup ($rekamMedisData->nama_pasien)")

@section("content")
@if ($current->role == "Petugas RM")
<button
    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mb-2"
    onclick="openAddModal()"
>
    Tambah
</button>
@endif
<div id="addModal" class="hidden fixed inset-0 overflow-auto bg-black bg-opacity-40 p-16">
    <div class="bg-white mx-auto my-5 p-4 border border-gray-300 w-96 rounded">
        <form action="{{route("createCheckup", ["idRekamMedis" => $idRekamMedis])}}" method="post">
            @csrf
            <div class="mb-2">
                <label for="id_dokter" class="block text-sm font-medium text-gray-600">Dokter:</label>
                <select name="id_dokter" class="form-select border border-gray-500 rounded mt-1 block w-full" required>
                    <option value=""></option>
                    @foreach ($dokterData as $data)
                    <option value="{{$data->id}}">{{$data->nama}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2">
                <label for="diagnosis" class="block text-sm font-medium text-gray-600">Diagnosis:</label>
                <input type="text" name="diagnosis" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
            </div>
            <div class="mb-2">
                <label for="resep" class="block text-sm font-medium text-gray-600">Resep:</label>
                <textarea name="resep" class="form-input border border-gray-500 rounded mt-1 block w-full" required></textarea>
            </div>
            <div class="mb-2">
                <label for="tanggal" class="block text-sm font-medium text-gray-600">Tanggal:</label>
                <input type="date" value="{{ now()->format('Y-m-d') }}" name="tanggal" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
            </div>
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded w-full">
                Tambah
            </button>
        </form>
    </div>
</div>
<table class="min-w-full border-collapse border border-gray-300">
    <thead>
        <tr>
            <th class="border border-gray-300 px-4 py-2">#</th>
            <th class="border border-gray-300 px-4 py-2">Dokter</th>
            <th class="border border-gray-300 px-4 py-2">Diagnosis</th>
            <th class="border border-gray-300 px-4 py-2">Resep</th>
            <th class="border border-gray-300 px-4 py-2">Tanggal</th>
            @if ($current->role == "Petugas RM")
            <th class="border border-gray-300 px-4 py-2">Action</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($checkupData as $index => $data)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{$index + 1}}</td>
                <td class="border border-gray-300 px-4 py-2">{{$data->nama_dokter}}</td>
                <td class="border border-gray-300 px-4 py-2">{{$data->diagnosis}}</td>
                <td class="border border-gray-300 px-4 py-2">{{$data->resep}}</td>
                <td class="border border-gray-300 px-4 py-2">{{$data->tanggal}}</td>
                @if ($current->role == "Petugas RM")
                <td class="border border-gray-300 px-4 py-2">
                    <div class="flex space-x-2">
                        <button
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mb-2"
                            onclick="openEditModal({{json_encode($data)}})"
                        >
                            Edit
                        </button>
                        <button
                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded mb-2"
                            onclick="openDeleteModal({{json_encode($data)}})"
                        >
                            Hapus
                        </button>
                    </div>
                </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
<div id="editModal" class="hidden fixed inset-0 overflow-auto bg-black bg-opacity-40 p-16">
    <div class="bg-white mx-auto my-5 p-4 border border-gray-300 w-96 rounded">
        <form action="{{route("updateCheckup", ["idRekamMedis" => $idRekamMedis])}}" method="post">
            @csrf
            @method("put")
            <input type="text" name="id" id="edit_id" class="hidden">
            <div class="mb-2">
                <label for="id_dokter" class="block text-sm font-medium text-gray-600">Dokter:</label>
                <select name="id_dokter" class="form-select border border-gray-500 rounded mt-1 block w-full" required>
                    <option value=""></option>
                    @foreach ($dokterData as $data)
                    <option value="{{$data->id}}">{{$data->nama}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2">
                <label for="diagnosis" class="block text-sm font-medium text-gray-600">Diagnosis:</label>
                <input type="text" name="diagnosis" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
            </div>
            <div class="mb-2">
                <label for="resep" class="block text-sm font-medium text-gray-600">Resep:</label>
                <textarea name="resep" class="form-input border border-gray-500 rounded mt-1 block w-full" required></textarea>
            </div>
            <div class="mb-2">
                <label for="tanggal" class="block text-sm font-medium text-gray-600">Tanggal:</label>
                <input type="date" name="tanggal" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
            </div>
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded w-full">
                Simpan
            </button>
        </form>
    </div>
</div>
<div id="deleteModal" class="hidden fixed inset-0 overflow-auto bg-black bg-opacity-40 p-16">
    <div class="bg-white mx-auto my-5 p-4 border border-gray-300 w-96 rounded">
        <form action="{{route("deleteCheckup", ["idRekamMedis" => $idRekamMedis])}}" method="post">
            @csrf
            @method("delete")
            <input type="text" name="id" id="delete_id" class="hidden">
            <p class="font-bold mb-4 text-center">Yakin hapus checkup ini ? </p>
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded w-full">
                Hapus
            </button>
        </form>
    </div>
</div>
<script>
    function openAddModal() {
        document.getElementById("addModal").classList.add("block")
        document.getElementById("addModal").classList.remove("hidden")
    }

    function closeAddModal() {
        document.getElementById("addModal").classList.remove("block")
        document.getElementById("addModal").classList.add("hidden")
    }

    function openEditModal(data) {
        console.log(data.id)
        document.getElementById("edit_id").value = data.id
        document.getElementsByName("id_dokter")[1].value = data.id_dokter
        document.getElementsByName("diagnosis")[1].value = data.diagnosis
        document.getElementsByName("resep")[1].value = data.resep
        document.getElementsByName("tanggal")[1].value = data.tanggal
        document.getElementById("editModal").classList.add("block")
        document.getElementById("editModal").classList.remove("hidden")
    }

    function closeEditModal() {
        document.getElementById("editModal").classList.remove("block")
        document.getElementById("editModal").classList.add("hidden")
    }

    function openDeleteModal(data) {
        document.getElementById("delete_id").value = data.id
        document.getElementById("deleteModal").classList.add("block")
        document.getElementById("deleteModal").classList.remove("hidden")
    }

    function closeDeleteModal() {
        document.getElementById("deleteModal").classList.remove("block")
        document.getElementById("deleteModal").classList.add("hidden")
    }

    window.onclick = function (event) {
        if (event.target === document.getElementById("addModal")) {
            closeAddModal()
        } else if (event.target === document.getElementById("editModal")) {
            closeEditModal()
        } else if (event.target === document.getElementById("deleteModal")) {
            closeDeleteModal()
        } else if (event.target === document.getElementById("changePasswordModal")) {
            closeChangePasswordModal()
        }
    }
</script>
@endsection

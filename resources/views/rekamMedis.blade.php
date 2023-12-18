@extends("index")

@section("title", "Rekam Medis")

@section("content")
<button
    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mb-2"
    onclick="openAddModal()"
>
    Tambah
</button>
<div id="addModal" class="hidden fixed inset-0 overflow-auto bg-black bg-opacity-40 p-16">
    <div class="bg-white mx-auto my-5 p-4 border border-gray-300 w-96 rounded">
        <form action="{{route("createRekamMedis")}}" method="post">
            @csrf
            <div class="mb-2">
                <label for="nama_pasien" class="block text-sm font-medium text-gray-600">Nama Pasien:</label>
                <input type="text" name="nama_pasien" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
            </div>
            <div class="mb-2">
                <label for="nik" class="block text-sm font-medium text-gray-600">NIK:</label>
                <input type="text" name="nik" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
            </div>
            <div class="mb-2">
                <label for="tempat_lahir" class="block text-sm font-medium text-gray-600">Tempat Lahir:</label>
                <input type="text" name="tempat_lahir" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
            </div>
            <div class="mb-2">
                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-600">Tanggal Lahir:</label>
                <input type="date" name="tanggal_lahir" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
            </div>
            <div class="mb-2">
                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-600">Jenis Kelamin:</label>
                <select name="jenis_kelamin" class="form-select border border-gray-500 rounded mt-1 block w-full" required>
                    <option value="0">Tidak diketahui</option>
                    <option value="1">Laki-laki</option>
                    <option value="2">Perempuan</option>
                    <option value="3">Tidak dapat ditentukan</option>
                    <option value="4">Tidak mengisi</option>
                </select>
            </div>
            <div class="mb-2">
                <label for="alamat" class="block text-sm font-medium text-gray-600">Alamat:</label>
                <input type="text" name="alamat" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
            </div>
            <div class="mb-4">
                <label for="nomor_kontak" class="block text-sm font-medium text-gray-600">Nomor Kontak:</label>
                <input type="text" name="nomor_kontak" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
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
            <th class="border border-gray-300 px-4 py-2">No RM</th>
            <th class="border border-gray-300 px-4 py-2">Nama Pasien</th>
            <th class="border border-gray-300 px-4 py-2">NIK</th>
            <th class="border border-gray-300 px-4 py-2">Tempat Lahir</th>
            <th class="border border-gray-300 px-4 py-2">Tanggal Lahir</th>
            <th class="border border-gray-300 px-4 py-2">Jenis Kelamin</th>
            <th class="border border-gray-300 px-4 py-2">Alamat</th>
            <th class="border border-gray-300 px-4 py-2">Nomor Kontak</th>
            <th class="border border-gray-300 px-4 py-2">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rekamMedisData as $index => $data)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{$index + 1}}</td>
                <td class="border border-gray-300 px-4 py-2">RM{{$data->id + 100000}}</td>
                <td class="border border-gray-300 px-4 py-2">{{$data->nama_pasien}}</td>
                <td class="border border-gray-300 px-4 py-2">{{$data->nik}}</td>
                <td class="border border-gray-300 px-4 py-2">{{$data->tempat_lahir}}</td>
                <td class="border border-gray-300 px-4 py-2">{{$data->tanggal_lahir}}</td>
                <td class="border border-gray-300 px-4 py-2">{{$data->jenis_kelamin}}</td>
                <td class="border border-gray-300 px-4 py-2">{{$data->alamat}}</td>
                <td class="border border-gray-300 px-4 py-2">{{$data->nomor_kontak}}</td>
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
            </tr>
        @endforeach
    </tbody>
</table>
<div id="editModal" class="hidden fixed inset-0 overflow-auto bg-black bg-opacity-40 p-16">
    <div class="bg-white mx-auto my-5 p-4 border border-gray-300 w-96 rounded">
        <form action="{{route("updateRekamMedis")}}" method="post">
            @csrf
            @method("put")
            <input type="text" name="id" id="edit_id" class="hidden">
            <div class="mb-2">
                <label for="nama_pasien" class="block text-sm font-medium text-gray-600">Nama Pasien:</label>
                <input type="text" name="nama_pasien" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
            </div>
            <div class="mb-2">
                <label for="nik" class="block text-sm font-medium text-gray-600">NIK:</label>
                <input type="text" name="nik" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
            </div>
            <div class="mb-2">
                <label for="tempat_lahir" class="block text-sm font-medium text-gray-600">Tempat Lahir:</label>
                <input type="text" name="tempat_lahir" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
            </div>
            <div class="mb-2">
                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-600">Tanggal Lahir:</label>
                <input type="date" name="tanggal_lahir" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
            </div>
            <div class="mb-2">
                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-600">Jenis Kelamin:</label>
                <select name="jenis_kelamin" class="form-select border border-gray-500 rounded mt-1 block w-full" required>
                    <option value="0">Tidak diketahui</option>
                    <option value="1">Laki-laki</option>
                    <option value="2">Perempuan</option>
                    <option value="3">Tidak dapat ditentukan</option>
                    <option value="4">Tidak mengisi</option>
                </select>
            </div>
            <div class="mb-2">
                <label for="alamat" class="block text-sm font-medium text-gray-600">Alamat:</label>
                <input type="text" name="alamat" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
            </div>
            <div class="mb-4">
                <label for="nomor_kontak" class="block text-sm font-medium text-gray-600">Nomor Kontak:</label>
                <input type="text" name="nomor_kontak" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
            </div>
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded w-full">
                Simpan
            </button>
        </form>
    </div>
</div>
<div id="deleteModal" class="hidden fixed inset-0 overflow-auto bg-black bg-opacity-40 p-16">
    <div class="bg-white mx-auto my-5 p-4 border border-gray-300 w-96 rounded">
        <form action="#" method="post">
            @csrf
            @method("delete")
            <input type="text" name="id" id="delete_id" class="hidden">
            <p class="font-bold mb-4">Yakin hapus data ini ? </p>
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded w-full">
                Hapus
            </button>
        </form>
    </div>
</div>
<script>
    function jenisKelamin(textJenisKelamin) {
        switch (textJenisKelamin) {
            case "Tidak diketahui":
                return "0"
            case "Laki-laki":
                return "1"
            case "Perempuan":
                return "2"
            case "Tidak dapat ditentukan":
                return "3"
            case "Tidak mengisi":
                return "4"
            default:
                return "0"
        }
    }

    function openAddModal() {
        document.getElementById("addModal").classList.add("block")
        document.getElementById("addModal").classList.remove("hidden")
    }

    function closeAddModal() {
        document.getElementById("addModal").classList.remove("block")
        document.getElementById("addModal").classList.add("hidden")
    }

    function openEditModal(data) {
        document.getElementById("edit_id").value = data.id
        document.getElementsByName("nama_pasien")[1].value = data.nama_pasien
        document.getElementsByName("nik")[1].value = data.nik
        document.getElementsByName("tempat_lahir")[1].value = data.tempat_lahir
        document.getElementsByName("tanggal_lahir")[1].value = data.tanggal_lahir
        document.getElementsByName("jenis_kelamin")[1].value = jenisKelamin(data.jenis_kelamin)
        document.getElementsByName("alamat")[1].value = data.alamat
        document.getElementsByName("nomor_kontak")[1].value = data.nomor_kontak
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
        }
    }
</script>
@endsection

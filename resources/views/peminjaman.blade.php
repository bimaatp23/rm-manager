@extends("index")

@section("title", "Peminjaman")

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
        <form action="{{route("createPeminjaman")}}" method="post">
            @csrf
            <div class="mb-2">
                <label for="id_rekam_medis" class="block text-sm font-medium text-gray-600">No RM:</label>
                <select name="id_rekam_medis" class="form-select border border-gray-500 rounded mt-1 block w-full" required>
                    <option value=""></option>
                    @foreach ($rekamMedisData as $data)
                    <option value="{{$data->id}}">RM{{$data->id + 100000}} ({{$data->nama_pasien}})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2">
                <label for="nama_peminjam" class="block text-sm font-medium text-gray-600">Nama Peminjam:</label>
                <input type="text" name="nama_peminjam" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
            </div>
            <div class="mb-2">
                <label for="kontak_peminjam" class="block text-sm font-medium text-gray-600">Kontak Peminjam:</label>
                <input type="text" name="kontak_peminjam" class="form-input border border-gray-500 rounded mt-1 block w-full" required>
            </div>
            <div class="mb-2">
                <label for="keperluan" class="block text-sm font-medium text-gray-600">Keperluan:</label>
                <select name="keperluan" class="form-select border border-gray-500 rounded mt-1 block w-full" required>
                    <option value=""></option>
                    <option value="Rawat Inap">Rawat Inap</option>
                    <option value="Rawat Jalan">Rawat Jalan</option>
                </select>
            </div>
            <div class="mb-2">
                <label for="keterangan" class="block text-sm font-medium text-gray-600">Keterangan:</label>
                <input type="text" name="keterangan" class="form-input border border-gray-500 rounded mt-1 block w-full">
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
            <th class="border border-gray-300 px-4 py-2">Nama Peminjam</th>
            <th class="border border-gray-300 px-4 py-2">Kontak Peminjam</th>
            <th class="border border-gray-300 px-4 py-2">Keperluan</th>
            <th class="border border-gray-300 px-4 py-2">Keterangan</th>
            <th class="border border-gray-300 px-4 py-2">Tanggal Peminjaman</th>
            <th class="border border-gray-300 px-4 py-2">Batas Pengembalian</th>
            <th class="border border-gray-300 px-4 py-2">Tanggal Pengembalian</th>
            <th class="border border-gray-300 px-4 py-2">Reminder</th>
            @if ($current->role == "Petugas RM")
            <th class="border border-gray-300 px-4 py-2">Action</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($peminjamanData as $index => $data)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{$index + 1}}</td>
                <td class="border border-gray-300 px-4 py-2">RM{{$data->id_rekam_medis + 100000}}</td>
                <td class="border border-gray-300 px-4 py-2">{{$data->nama_peminjam}}</td>
                <td class="border border-gray-300 px-4 py-2">{{$data->kontak_peminjam}}</td>
                <td class="border border-gray-300 px-4 py-2">{{$data->keperluan}}</td>
                <td class="border border-gray-300 px-4 py-2">{{$data->keterangan}}</td>
                <td class="border border-gray-300 px-4 py-2">{{$data->tanggal_peminjaman}}</td>
                <td class="border border-gray-300 px-4 py-2">{{$data->batas_pengembalian}}</td>
                <td class="border border-gray-300 px-4 py-2">{{$data->tanggal_pengembalian}}</td>
                <td class="border border-gray-300 px-4 py-2">{{$data->reminder == 0 ? "No" : "Yes"}}</td>
                @if ($current->role == "Petugas RM")
                <td class="border border-gray-300 px-4 py-2">
                    <div class="flex space-x-2">
                        @if ($data->tanggal_pengembalian == NULL)
                        <button
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mb-2"
                            onclick="openEditModal({{json_encode($data)}})"
                        >
                            Pengembalian
                        </button>
                        <button
                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded mb-2"
                            onclick="openDeleteModal({{json_encode($data)}})"
                        >
                            Hapus
                        </button>
                        @endif
                    </div>
                </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
<div id="editModal" class="hidden fixed inset-0 overflow-auto bg-black bg-opacity-40 p-16">
    <div class="bg-white mx-auto my-5 p-4 border border-gray-300 w-96 rounded">
        <form action="{{route("updatePeminjaman")}}" method="post">
            @csrf
            @method("put")
            <input type="text" name="id" id="edit_id" class="hidden">
            <p class="font-bold mb-4 text-center">Yakin dokumen sudah dikembalikan ? </p>
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded w-full">
                Sudah
            </button>
        </form>
    </div>
</div>
<div id="deleteModal" class="hidden fixed inset-0 overflow-auto bg-black bg-opacity-40 p-16">
    <div class="bg-white mx-auto my-5 p-4 border border-gray-300 w-96 rounded">
        <form action="{{route("deletePeminjaman")}}" method="post">
            @csrf
            @method("delete")
            <input type="text" name="id" id="delete_id" class="hidden">
            <p class="font-bold mb-4 text-center">Yakin hapus peminjaman ini ? </p>
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
        document.getElementById("edit_id").value = data.id
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

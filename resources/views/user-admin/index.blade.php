@extends('layouts.app')

@section('page_title', 'User')
@section('page_breadcrumb', 'User')

@section('content')
    <div class="w-full h-full bg-white p-7 rounded-xl">
        <!-- Menampilkan pesan flash session -->
        @if (session('success'))
            <div class="alert alert-success p-3 mb-4 bg-green-500 text-white rounded-lg" id="success-alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error p-3 mb-4 bg-red-500 text-white rounded-lg" id="error-alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex justify-between">
            <a href="{{ route('users.create') }}" class="btn-primary">
                Tambah
            </a>
            <form action="{{ route('users.index') }}" method="GET">
                <div class="flex gap-3">
                    <input type="text" class="p-2 px-3 border rounded-lg" name="name" placeholder="Search..."
                        value="{{ request()->input('name') }}">
                    <input type="submit" class="btn-primary" value="Search">
                    <a href="{{ route('products.index') }}" class="btn-secondary">Reset</a>

                </div>
            </form>
        </div>

        @if ($users->count() > 0)
            <table class="w-full mt-8 text-left border-separate">
                <thead class="text-gray-600">
                    <tr class="text-black">
                        <th class="border-b-2  border-gray-100 pb-4">No</th>
                        <th class="border-b-2  border-gray-100 pb-4">Nama</th>
                        <th class="border-b-2  border-gray-100 pb-4">Email</th>
                        <th class="border-b-2  border-gray-100 pb-4">Role</th>
                        <th class="border-b-2  border-gray-100 pb-4 w-[130px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @if (session('error'))
                        <div class="alert alert-error p-3 mb-4 bg-red-500 text-white rounded-lg" id="error-alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @php($number = 1)
                    @foreach ($users as $value)
                        <tr>
                            <td class="pt-5">{{ $number++ }}</td>
                            <td class="pt-5">{{ $value->name }}</td>
                            <td class="pt-5">{{ $value->email }}</td>
                            <td class="pt-5">{{ $value->role }}</td>
                            <td class="flex items-center gap-3 w-[145px] pt-5">
                                <a href="{{ route('users.edit', $value->id) }}" class="btn-warning">Edit</a>

                                @if ($value->sale()->count() === 0 && $value->email !== 'admin@gmail.com')
                                    <form action="{{ route('users.delete', $value->id) }}" method="POST"
                                        class="delete-form">
                                        @csrf
                                        @method('delete')
                                        <button class="btn-danger" type="submit">Hapus</button>
                                    </form>
                                @else
                                    <button class="btn-danger btn-linked-sale" type="button">Hapus</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center mt-4 text-lg text-red-500">Data tidak ditemukan</p>
        @endif

        <!-- Pagination -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

    <!-- JavaScript untuk menyembunyikan alert setelah 5 detik -->
    <script>
        @if (session('success'))
            setTimeout(function() {
                document.getElementById('success-alert').style.display = 'none';
            }, 3000);
        @endif

        @if (session('error'))
            setTimeout(function() {
                document.getElementById('error-alert').style.display = 'none';
            }, 300);
        @endif

        // Menangani klik tombol hapus
        document.querySelectorAll('.btn-linked-sale').forEach(function(button) {
            button.addEventListener('click', function() {
                alert('Akun sudah tertaut dengan pembelian');
            });
        });
    </script>
@endsection

<div id="dialog" class="fixed inset-0 z-10 hidden bg-black/50 backdrop-blur-sm items-center justify-center">
    <div class="max-h-screen max-w-lg mx-auto bg-gray-50 mt-5 rounded-3xl p-5 mb-5">
        <h2 class="text-md font-semibold border-b-2 pb-4">Update Stok Produk</h2>

        <form method="POST">
            @csrf
            @method('PATCH')
            <div class="flex flex-col mt-4">
                <label for="name">Nama Produk</label>
                <input type="text" name="name" class="mt-2 bg-gray-200 p-2 px-3 border rounded-lg cursor-auto"
                    readonly>
            </div>
            <div class="flex flex-col mt-4 border-b-[1.5px] pb-5">
                <label for="stock">Stok</label>
                <input type="number" name="stock" class="mt-2 bg-transparent p-2 px-3 border rounded-lg">
            </div>
            <div class="flex gap-2 justify-end mt-4">
                <button onclick="document.getElementById('dialog').classList.add('hidden')" type="button"
                    class="btn-secondary">
                    Batal
                </button>
                <button class="btn-primary" type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDialog(id, name, stock) {
        const dialog = document.getElementById('dialog');
        dialog.classList.remove('hidden');

        // Isi input hidden atau form
        const form = dialog.querySelector('form');

        // Ubah action form sesuai ID (pastikan route-nya benar)
        form.action = `/products/updateStock/${id}`;

        // Isi nilai input
        form.querySelector('input[name="name"]').value = name;
        form.querySelector('input[name="stock"]').value = stock;
    }
</script>

<div id="dialog" class="fixed inset-0 z-30 bg-black/50 backdrop-blur-sm items-center justify-center hidden">
    <div class="w-[90%] max-w-xl bg-gray-50 rounded-3xl mt-5 p-10">
        <h2 class="text-md font-semibold border-b-2 pb-4">Detail Penjualan</h2>
        <div class="flex flex-col gap-2" id="info-member">
        </div>
        <div class="overflow-x-auto mt-10">
            <table class="min-w-full text-sm text-gray-600 text-center">
                <thead class="">
                    <tr>
                        <th>Nama <br>Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody id="items">

                </tbody>
            </table>

            <div class="flex gap-4 justify-end px-4 py-3 text-sm">
                <span class="text-gray-600 font-semibold">Total</span>
                <span class="text-gray-600 font-semibold total-harga" id="total-price"></span>
            </div>
            <div class="flex justify-center items-center flex-col py-3 border-b-[1.5px] pb-5 border-gray-300">
                <span class="text-sm ">Dibuat pada: <span id="created-at"></span></span>
                <span class="text-sm created-by">Dibuat oleh: <span id="user-name"></span>
                </span>
            </div>
            <div class="flex justify-end mt-3">
                <button onclick="document.getElementById('dialog').classList.add('hidden')" class="btn-secondary"
                    type="button">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openDialog(id) {
        console.log(id)
        const dialog = $('#dialog')
        $.ajax({
            url: '/sale/dialog/' + id,
            success: (response) => {
                dialog.find('#info-member').html(setInfoMember(response.data.member, response.data
                    .member_status))
                dialog.find('#items').html(setItems(response.data.items))
                dialog.find('#total-price').html(formattedPrice(response.data.total_price))
                dialog.find('#created-at').html(response.data.sale_created)
                dialog.find('#user-name').html(response.data.user.name)
                dialog.removeClass('hidden')
                dialog.addClass('flex')
            },
            beforeSend: () => {
                dialog.removeClass('flex')
                dialog.addClass('hidden')
            }
        })
    }

    function setInfoMember(member, status) {
        return `
        <div class="flex justify-between pt-5">
                <span class="text-gray-600 text-sm">Member Status : <span class="status-member">${status ? "Member" : "Bukan Member"}</span></span>
                <span class="text-gray-600 text-sm">Bergabung Sejak : <span class="tanggal-bergabung">${member.join_date}</span></span>
        </div>
        <span class="text-gray-600 text-sm">No. HP : <span class="hp-member">${member.phone_number}</span></span>
            <span class="text-gray-600 text-sm">Poin Member : <span class="poin-member">${member.poin}</span></span>
        `
    }

    function setItems(items) {
        let html = []
        $.each(items, function(index, value) {
            html.push(
                `
            <tr>
                <td class="py-4 nama-produk">${value.product.name}</td>
                <td class="py-4 qty">${value.qty}</td>
                <td class="py-4 harga">${formattedPrice(value.product.price)}</td>
                <td class="py-4 subtotal">${formattedPrice(value.qty*value.product.price)}</td>
            </tr>
        `
            )
        })
        return html
    }

    function formattedPrice(price) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(price);
    }
</script>

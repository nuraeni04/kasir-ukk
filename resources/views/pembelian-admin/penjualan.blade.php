@extends('layouts.app')

@section('page_title', 'Penjualan')
@section('page_breadcrumb', 'Penjualan')

@section('content')
    <div class="rounded-xl p-12 border">
        <div class="grid grid-cols-2 gap-7 items-start">
            <div>
                <h1 class="text-2xl font-semibold">Produk yang di pilih</h1>
                {{-- Menampilkan pesanan yang dipilih --}}
                <div class="flex flex-col gap-4 mt-3" id="order-container"></div>
                {{-- End --}}
                <div class="flex items-center gap-20 mt-7">
                    <h1 class="text-xl text-gray-600">Total</h1>
                    <h1 class="text-2xl text-gray-600" id="grand-total"></h1>
                </div>
            </div>
            <div>
                <span>Status Member <span class="text-sm text-red-500">Dapat juga membuat member</span></span>
                <form class="flex flex-col gap-3" id="sale-form" method="POST" action="{{ route('sale.store') }}">
                    @csrf
                    <input type="hidden" id="input-order" name="orders" class="w-full border p-3" />
                    <input type="hidden" name="total_price" class="w-full border p-3" />
                    <div>
                        <select name="member_type" id="member-type"
                            class="flex flex-col w-full p-3 rounded-lg bg-transparent border">
                            <option value="non_member">Bukan Member</option>
                            <option value="member">Member</option>
                        </select>
                    </div>

                    <!-- No Telepon -->
                    <div class="hidden flex-col gap-1.5" id="phone-field">
                        <label for="phone_number">No Telepon <span class="text-sm text-red-500">(wajib jika
                                member)</span></label>
                        <input type="text" name="phone_number" id="phone_number"
                            class="bg-transparent border p-3 rounded-lg">
                    </div>

                    <!-- Total Bayar -->
                    <div class="flex flex-col gap-1.5" id="form-total-paid">
                        <label for="total-paid">Total Bayar</label>
                        <input type="text" name="total_paid" id="total-paid" maxlength="30"
                            class="bg-transparent border p-3 rounded-lg">
                    </div>

                    <!-- Tombol -->
                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary" id="submit-btn" disabled>Pesan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function formattedPrice(price) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(price);
        }

        function getNumericPrice(price) {
            return parseInt(price.replace(/[^0-9]/g, ''), 10) || 0;
        }

        function validateForm() {
            const memberType = $('#member-type').val();
            const phone = $('#phone_number').val().trim();
            const totalPaidRaw = $('#total-paid').val();
            const totalPaid = getNumericPrice(totalPaidRaw);
            const grandTotal = getNumericPrice($('#grand-total').text());
            const submitBtn = $('#submit-btn');

            let isValid = true;

            $('#form-total-paid .error').remove();
            $('#phone-field .error').remove();

            if (memberType === 'member') {
                if (!phone) {
                    $('#phone-field').append('<p class="text-sm text-red-500 error">No telepon wajib diisi</p>');
                    isValid = false;
                } else if (!/^[0-9]+$/.test(phone)) {
                    $('#phone-field').append('<p class="text-sm text-red-500 error">No telepon hanya boleh angka</p>');
                    isValid = false;
                }
            }

            if (!totalPaidRaw) {
                isValid = false;
            } else if (totalPaid < grandTotal) {
                $('#form-total-paid').append('<p class="text-sm text-red-500 error">Jumlah bayar kurang</p>');
                isValid = false;
            }

            submitBtn.prop('disabled', !isValid);
        }

        // Format & validate input
        $(document).ready(function() {
            // Load order from localStorage
            const ordersData = localStorage.getItem("orders");
            if (ordersData) {
                const orders = JSON.parse(ordersData);
                let html = "";
                let grandTotal = 0;

                orders.forEach(function(item) {
                    const total = getNumericPrice(item.price) * parseInt(item.qty);
                    grandTotal += total;

                    html += `
                    <div class="flex items-center gap-14">
                        <div>
                            <h1 class="font-semibold">${item.name}</h1>
                            <h3 class="text-sm text-gray-600">${formattedPrice(getNumericPrice(item.price))} x ${item.qty}</h3>
                        </div>
                        <h2 class="font-semibold text-lg">${formattedPrice(total)}</h2>
                    </div>
                `;
                });
                $('input[name="orders"]').val(ordersData);
                $("#grand-total").html(formattedPrice(grandTotal));
                $('input[name="total_price"]').val(formattedPrice(grandTotal));
                $("#order-container").html(html);
            } else {
                $("#order-container").html("<p>Tidak ada data pesanan</p>");
            }

            // Trigger initial
            $('#member-type').trigger('change');
            validateForm();
        });

        // Event change select member
        $('#member-type').on('change', function() {
            const selected = $(this).val();
            const phoneField = $('#phone-field');

            if (selected === 'member') {
                phoneField.removeClass('hidden').addClass('flex flex-col');
            } else {
                phoneField.addClass('hidden').removeClass('flex flex-col');
            }

            validateForm();
        });

        $('#phone_number').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            validateForm();
        });

        $('#total-paid').on('keyup', function() {
            let input = $(this).val().replace(/[^0-9]/g, '');
            if (input) {
                $(this).val(formattedPrice(input));
            } else {
                $(this).val('');
            }
            validateForm();
        });
    </script>
@endpush

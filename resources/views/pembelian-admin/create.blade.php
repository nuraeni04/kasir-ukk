@extends('layouts.app')

@section('page_title', 'Penjualan')
@section('page_breadcrumb', 'Penjualan')

@section('content')
    <div class="rounded-xl p-12 bg-white/70">
        <div class="grid grid-cols-3 items-center gap-5" id="product">
            @foreach ($products as $product)
                <div class="border border-gray-200 p-7 rounded-lg flex items-center gap-6 card-product shadow-lg">
                    <div class="p-2 w-full">
                        <input type="hidden" value="{{ $product->id }}" class="product-id">

                        <!-- Gambar Produk dengan Border dan Responsif -->
                        <div class="w-full h-40 flex justify-center items-center">
                            <img src="{{ asset('/storage/images/' . $product->image) }}" alt="image"
                                class="object-contain h-full w-full">
                        </div>

                        <h1 class="text-center text-xl mt-3 font-medium product-name">{{ $product->name }}</h1>
                        <h1 class="text-center text-md text-gray-600 mt-3">Stok <span
                                class="product-stock">{{ $product->stock }}</span></h1>
                        <h1 class="text-center text-sm text-black mt-3 product-price">
                            {{ number_format($product->price, 0, ',', '.') }}</h1>

                        <!-- Quantity Controls -->
                        <div class="flex items-center justify-center gap-4 mt-3 action">
                            <button class="btn-primary h-6 w-5 flex items-center justify-center bg-gray-100 decrement">
                                <i class="text-primary bi bi-dash text-white hover:text-white"></i>
                            </button>
                            <input type="text" value="0" class="h-6 w-6 bg-transparent text-center qty">
                            <button class="btn-primary h-6 w-5 flex items-center justify-center bg-gray-100 increment">
                                <i class="text-primary bi bi-plus text-white hover:text-white"></i>
                            </button>
                        </div>
                        <h3 class="text-center text-md mt-3 sub-total">Sub Total Rp. 0 </h3>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex justify-end items-end mt-5">
        <button class="btn-primary" id="order">Selanjutnya</button>
    </div>
@endsection

@push('script')
    <script>
        localStorage.removeItem('orders')

        function handleQty(self, type) {
            var currentQty = parseInt(self.find('.qty').val(), 10)
            var stock = parseInt(self.find('.product-stock').text())
            if (type === 'plus') {
                if (currentQty < stock) {
                    self.find('.qty').val(currentQty + 1)
                } else {
                    self.find('.qty').val(stock)
                }
            } else {
                if (currentQty > 0) {
                    self.find('.qty').val(currentQty - 1)
                } else {
                    self.find('.qty').val(0)
                }
            }

            const qtyNow = parseInt(self.find('.qty').val());
            const price = parseInt(self.find('.product-price').text().replace(/[^0-9]/g, ''))
            const subTotal = qtyNow * price;
            self.find('.sub-total').text(`Sub Total Rp. ${subTotal.toLocaleString('id-ID')}`);
        };

        $("#product > .card-product").each(function() {
            const self = $(this)
            self.find('.increment').bind('click', function(e) {
                return handleQty(self, 'plus')
            });
            self.find('.decrement').bind('click', function(e) {
                return handleQty(self, 'minus')
            });
        });

        $('#order').click(function() {
            let orders = []
            $("#product > .card-product").each(function() {
                const self = $(this)
                const qty = parseInt(self.find('.qty').val())
                if (qty > 0) {
                    orders.push({
                        id: self.find('.product-id').val(),
                        name: self.find('.product-name').text(),
                        stock: self.find('.product-stock').text(),
                        price: self.find('.product-price').text(),
                        qty: self.find('.qty').val()
                    })
                }
            });
            if (orders.length > 0) {
                localStorage.setItem('orders', JSON.stringify(orders))
                window.location.href = '/sale/pesan'
            } else {
                localStorage.removeItem('orders')
            }
            console.log(orders)
        })
    </script>
@endpush

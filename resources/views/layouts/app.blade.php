<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @vite('resources/css/app.css')
</head>

<style>
    .pagination .page-item.active .page-link {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .pagination .page-item .page-link:hover {
        background-color: #0056b3;
        color: white;
    }

    .pagination .page-item .page-link {
        border: 1px solid #dee2e6;
    }
</style>

<body class="flex h-screen w-full overflow-hidden">
    <!-- Sidebar (Fixed) -->
    <div id="sidebar" class="fixed top-0 left-0 z-1 w-[350px] h-screen px-7 bg-white overflow-y-auto">
        <img src="/assets/img/logo.png" alt="logo" class="object-cover mt-5">
        <h2 class="mt-12 text-[#486088] font-semibold text-[16px] pl-4">Menu</h2>
        <div class="flex flex-col gap-0.5 mt-5">
            <a href="/" class="p-2.5 flex items-center rounded-md px-4 hover:bg-gray-200">
                <i class="bi bi-grid text-lg"></i>
                <span class="ml-4 text-[#486088] font-semibold">Dashboard</span>
            </a>
            <a href="{{ route('products.index') }}" class="p-2.5 flex items-center rounded-md px-4 hover:bg-gray-200">
                <i class="bi bi-box-seam text-lg"></i>
                <span class="ml-4 text-[#486088] font-semibold">Produk</span>
            </a>
            <a href="{{ route('sale.index') }}" class="p-2.5 flex items-center rounded-md px-4 hover:bg-gray-200">
                <i class="bi bi-cart text-lg"></i>
                <span class="ml-4 text-[#486088] font-semibold">Penjualan</span>
            </a>
            @if (Auth::user()->role == 'admin')
                <a href="{{ route('users.index') }}" class="p-2.5 flex items-center rounded-md px-4 hover:bg-gray-200">
                    <i class="bi bi-person text-lg"></i>
                    <span class="ml-4 text-[#486088] font-semibold">User</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Main Content (Scroll Area) -->
    <div class="ml-[350px] flex-1 bg-[#f2f7ff] h-screen">

        <!-- Topbar -->
        <div class="h-16 bg-white w-full flex justify-end items-center px-14">
            <div class="cursor-pointer hover:bg-gray-200 p-2 rounded-xl"
                onclick="getElementById('dropdown').classList.toggle('hidden')">
                <i class="bi bi-person"></i>
                <span class="ml-3">{{ Auth::user()->role }}</span>
            </div>
        </div>

        <!-- Dropdown -->
        <div id="dropdown" class="fixed top-16 right-14 z-20 w-60 bg-white rounded-xl shadow-lg hidden">
            <ul class="py-2">
                <li>
                    <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-100">
                        <i class="bi bi-person-check mr-3"></i>{{ Auth::user()->role }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('logout') }}" class="flex items-center px-4 py-2 hover:bg-gray-100"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right mr-3"></i> logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>

        <!-- Content Area -->
        <div class="mt-10 px-10 mb-32 overflow-auto h-[calc(100vh-4rem)]">
            <div class="flex gap-3 items-center">
                <a href="/dashboard">
                    <i class="bi bi-house text-lg cursor-pointer font-semibold"></i>
                </a>
                <i class="bi bi-chevron-right text-gray-400"></i>
                <span class="text-gray-400">@yield('page_breadcrumb', 'Dashboard')</span>
            </div>
            <h1 class="text-3xl font-semibold mt-2">@yield('page_title', 'Dashboard')</h1>
            <div class="mt-10 mb-32">
                @yield('content')
            </div>
        </div>
    </div>
    </div>

    <script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
    @stack('script')

</body>


</html>

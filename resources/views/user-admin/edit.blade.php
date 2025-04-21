@extends('layouts.app')

@section('page_title', 'User')
@section('page_breadcrumb', 'User')

@section('content')
    <div class="rounded-xl p-5 border">
        <form action="{{ route('users.update', $users['id']) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="flex justify-between gap-10">
                <div class="flex flex-col w-full">
                    <label for="name">Nama</label>
                    <input type="text" name="name" value="{{ $users['name'] }}"
                        class="mt-2 bg-transparent p-2 px-3 border rounded-lg">
                </div>
                <div class="flex flex-col w-full">
                    <label for="email">Email</label>
                    <input type="email" name="email" value="{{ $users['email'] }}"
                        class="mt-2 bg-transparent p-2 px-3 border rounded-lg">
                </div>
            </div>
            <div class="flex justify-between gap-10 mt-5 ">
                <div class="flex flex-col w-full">
                    <label for="role">Role</label>
                    <div class="relative mt-2">
                        <select name="role" id="role" class="w-full p-2 rounded-lg bg-transparent border px-3">
                            <option value="admin" {{ $users['role'] === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="employee" {{ $users['role'] === 'employee' ? 'selected' : '' }}>Employee</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-col w-full">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="mt-2 bg-transparent p-2 px-3 border rounded-lg">
                </div>
            </div>
            <div class="flex justify-end ">
                <button class="btn-primary mt-4 flex items-end" type="submit">Simpan</button>
            </div>
        </form>
    </div>
@endsection

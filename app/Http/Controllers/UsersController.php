<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('name');
    
        $users = User::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        })
        ->where('email', '!=', 'admin@gmail.com')
        ->paginate(5);
    
        return view('user-admin.index', compact('users'));
    }

    public function create()
    {
        return view('user-admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|min:3',
            'email'=>'required|min:3|unique:users,email',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Berhasil menambahkan data!');
    }

    public function edit($id)
    {
        $users = User::find($id);
        return view('user-admin.edit', compact('users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'nullable|min:5',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if($request->password !==null) {
            $userData['password'] = Hash::make($request->password);
        }

        User::where('id', $id)->update($userData);
        return redirect()->route('users.index')->with('success', 'Berhasil mengubah data!');
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if ($user->sale()->count() > 0) {
            return redirect()->back()->with('error', 'Pengguna ini tidak bisa dihapus karena sudah terhubung dengan transaksi pembelian.');
        }
        
        if ($user->email === 'admin@gmail.com') {
            return redirect()->back()->with('error', 'Admin tidak bisa dihapus');
        }
    
        $user->delete();
        return redirect()->back()->with('success', 'Berhasil Menghapus Data');
    }

}

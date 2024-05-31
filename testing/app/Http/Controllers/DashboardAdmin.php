<?php

namespace App\Http\Controllers;

use App\Http\Resources\SeeAllPostResource;
use App\Http\Resources\SiteUsersResource;
use App\Models\POst;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DashboardAdmin extends Controller
{
    public function index()
    {
        return response()->json('Ini Dashboard khusus admin');
    }

    public function read()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'user');
        })->get();

        return SiteUsersResource::collection($users);
    }

    public function store(Request $request)
    {
        try {
            $password = Str::random(8);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($password),
            ]);

            $role = Role::where('name', 'user')->first();

            if ($role) {
                $user->assignRole($role);
            }

            return response()->json(['message' => 'User berhasil ditambahkan'], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $users = User::findOrFail($id);

        $users->delete();

        return response()->json(['message' => 'Users berhasil dihapus'], 200);
    }

    public function postinganUsers()
    {
        $posts = POst::with('pembuatPost')->get();

        return SeeAllPostResource::collection($posts);
    }

    public function destroyPost($id)
    {
        $post = POst::findOrFail($id);

        $post->delete();

        return response()->json(['message' => 'Postingan berhasil dihapus oleh anda']);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        $totalUsers = $users->count();
        $admins = $users->where('is_admin', true)->count();
        $regulares = $users->where('is_admin', false)->count();

        return view('admin.index', compact('users', 'totalUsers', 'admins', 'regulares'));
    }

    public function toggleAdmin(User $user)
    {
        // Proteger: não pode remover o próprio admin
        if ($user->email === 'idelcioforest@gmail.com') {
            return back()->with('error', 'Não é possível alterar o admin principal.');
        }

        $user->update(['is_admin' => !$user->is_admin]);

        $msg = $user->is_admin ? "Usuário {$user->name} agora é admin." : "Permissão de admin removida de {$user->name}.";

        return back()->with('success', $msg);
    }

    public function destroy(User $user)
    {
        if ($user->email === 'idelcioforest@gmail.com') {
            return back()->with('error', 'Não é possível excluir o admin principal.');
        }

        $user->delete();

        return back()->with('success', "Usuário {$user->name} excluído.");
    }
}

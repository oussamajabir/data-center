<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //affiche liste utilisateur pour l'admin
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    //change role(valider un utilisateur)
    public function promote($id)
    {
        $user = User::findOrFail($id);
        $user->role = 'interne'; //valide comme utilisateur interne
        $user->save();

        return back()->with('success', "L'utilisateur a été validé !");
    }

    //banne utilisateur (Desactiver)
    public function toggleBan($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active; //ivnverse true->false
        $user->save();

        return back()->with('success', "Statut de l'utilisateur modifié.");
    }
}

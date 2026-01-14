<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Resource;
use App\Models;
use App\Models\Reservation;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $user = Auth::user();

        if($user->role === 'admin') {
            //voit toutes les demandes en attente
            $pendingReservations = Reservation::where('status', 'pending')->with(['user', 'resource'])->get();
            $stats = [
                'total_users' => \App\Models\User::count(),
                'total_resources' => Resource::count(),
            ];
            return view('dashboard', compact('pendingReservations', 'stats'));
        }
        else {
            //voir seulement catalogue + ses reservations
            $query = Resource::where('state', 'active');

            // Filtre par catégorie
            if ($request->has('category_id') && $request->category_id != '') {
                $query->where('category_id', $request->category_id);
            }

            // Barre de recherche
            if ($request->has('search') && $request->search != '') {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $resources = $query->get();
            $categories = \App\Models\Category::all();

            $myReservations = Reservation::where('user_id', $user->id)->with('resource')->orderBy('created_at', 'desc')->get();

            return view('dashboard', compact('resources', 'myReservations', 'categories'));
        }
    }
}

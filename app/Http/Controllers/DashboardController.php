<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Resource;
use App\Models;
use App\Models\Reservation;

class DashboardController extends Controller
{
    public function index() {
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
            $resources = Resource::where('state', 'active')->get();

            $myReservations = Reservation::where('user_id', $user->id)->with('resource')->orderBy('created_at', 'desc')->get();

            return view('dashboard', compact('resources', 'myReservations'));
        }
    }
}

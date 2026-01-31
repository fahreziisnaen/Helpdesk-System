<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isTeknisi()) {
            return $this->teknisiDashboard();
        } else {
            return $this->userDashboard();
        }
    }

    private function adminDashboard()
    {
        $stats = [
            'total_tickets' => Ticket::count(),
            'open_tickets' => Ticket::where('status', 'open')->count(),
            'in_progress_tickets' => Ticket::where('status', 'in_progress')->count(),
            'solved_tickets' => Ticket::where('status', 'solved')->count(),
            'closed_tickets' => Ticket::where('status', 'closed')->count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_teknisi' => User::where('role', 'teknisi')->count(),
        ];

        $recentTickets = Ticket::with(['user', 'assignedTechnician', 'categoryModel'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.admin', compact('stats', 'recentTickets'));
    }

    private function teknisiDashboard()
    {
        $user = Auth::user();

        $stats = [
            'assigned_tickets' => Ticket::where('assigned_to', $user->id)->count(),
            'in_progress_tickets' => Ticket::where('assigned_to', $user->id)
                ->where('status', 'in_progress')
                ->count(),
            'solved_tickets' => Ticket::where('assigned_to', $user->id)
                ->where('status', 'solved')
                ->count(),
        ];

        $assignedTickets = Ticket::with(['user', 'categoryModel'])
            ->where('assigned_to', $user->id)
            ->whereIn('status', ['open', 'in_progress'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.teknisi', compact('stats', 'assignedTickets'));
    }

    private function userDashboard()
    {
        $user = Auth::user();

        $stats = [
            'my_tickets' => Ticket::where('user_id', $user->id)->count(),
            'open_tickets' => Ticket::where('user_id', $user->id)
                ->where('status', 'open')
                ->count(),
            'in_progress_tickets' => Ticket::where('user_id', $user->id)
                ->where('status', 'in_progress')
                ->count(),
            'solved_tickets' => Ticket::where('user_id', $user->id)
                ->where('status', 'solved')
                ->count(),
        ];

        $myTickets = Ticket::with(['assignedTechnician', 'categoryModel'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.user', compact('stats', 'myTickets'));
    }
}

<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\ClientUpdate;
use App\Models\Message;
use App\Models\Setting;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('لوحة التحكم')]
class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $isAdmin = $user->isAdmin();

        if ($isAdmin) {
            $totalClients = Client::count();
            $gap = (int) Setting::get('update_gap_days', 3);
            $lateClients = Client::whereNotNull('last_update_at')
                ->where('last_update_at', '<', now()->subDays($gap))
                ->count();
            $totalEmployees = User::where('role', 'follower')->count();
            $recentUpdates = ClientUpdate::with(['client', 'user'])
                ->latest()
                ->take(10)
                ->get();
            $employeeStats = User::where('role', 'follower')
                ->withCount('clients')
                ->withCount('clientUpdates')
                ->get();

            return view('livewire.dashboard-admin', compact(
                'totalClients', 'lateClients', 'totalEmployees', 'recentUpdates', 'employeeStats'
            ));
        }

        // Employee dashboard
        $myClients = Client::where('assigned_to', $user->id)->count();
        $gap = (int) Setting::get('update_gap_days', 3);
        $pendingUpdates = Client::where('assigned_to', $user->id)
            ->whereNotNull('last_update_at')
            ->where('last_update_at', '<', now()->subDays($gap))
            ->count();
        $recentMessages = Message::whereHas('chat.client', function ($q) use ($user) {
            $q->where('assigned_to', $user->id);
        })->where('is_read', false)->count();
        $myRecentUpdates = ClientUpdate::with('client')
            ->where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.dashboard-employee', compact(
            'myClients', 'pendingUpdates', 'recentMessages', 'myRecentUpdates'
        ));
    }
}

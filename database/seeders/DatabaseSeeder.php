<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@crm.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create Follower Employees
        $emp1 = User::create([
            'name' => 'Ahmed Hassan',
            'email' => 'ahmed@crm.com',
            'password' => bcrypt('password'),
            'role' => 'follower',
        ]);

        $emp2 = User::create([
            'name' => 'Sara Ali',
            'email' => 'sara@crm.com',
            'password' => bcrypt('password'),
            'role' => 'follower',
        ]);

        // Default Settings
        Setting::set('update_gap_days', '3');

        // Sample Clients
        $clients = [
            ['name' => 'Acme Corp', 'phone' => '0501234567', 'email' => 'info@acme.com', 'status' => 'active', 'assigned_to' => $emp1->id, 'last_update_at' => now()],
            ['name' => 'Globe Industries', 'phone' => '0557654321', 'email' => 'contact@globe.com', 'status' => 'new', 'assigned_to' => $emp1->id, 'last_update_at' => now()->subDays(5)],
            ['name' => 'Tech Solutions', 'phone' => '0559988776', 'email' => 'hello@techsol.com', 'status' => 'active', 'assigned_to' => $emp2->id, 'last_update_at' => now()->subDays(1)],
            ['name' => 'Nova Enterprises', 'phone' => '0551122334', 'email' => 'nova@ent.com', 'status' => 'inactive', 'assigned_to' => $emp2->id, 'last_update_at' => now()->subDays(10)],
            ['name' => 'Summit LLC', 'phone' => '0504455667', 'email' => 'summit@llc.com', 'status' => 'completed', 'assigned_to' => $emp1->id, 'last_update_at' => now()->subDays(2)],
        ];

        foreach ($clients as $data) {
            Client::create($data);
        }
    }
}

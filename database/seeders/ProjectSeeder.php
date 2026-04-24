<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Project;
use App\Models\Team;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Clients
        $client1 = Client::create([
            'name' => 'PT Teknologi Jaya',
            'phone' => '08123456789',
            'email' => 'contact@teknologijaya.id',
        ]);

        $client2 = Client::create([
            'name' => 'Budi Santoso',
            'phone' => '082233445566',
            'email' => 'budi@example.com',
        ]);

        // Create Teams
        $team1 = Team::create(['name' => 'Andi', 'role' => 'Fullstack Developer', 'phone' => '08111222333']);
        $team2 = Team::create(['name' => 'Siti', 'role' => 'UI/UX Designer', 'phone' => '08444555666']);

        // Create Projects
        $project1 = Project::create([
            'client_id' => $client1->id,
            'name' => 'Website E-Commerce PT TJ',
            'description' => 'Pengembangan website e-commerce modern dengan integrasi payment gateway.',
            'client_name' => $client1->name,
            'client_phone' => $client1->phone,
            'client_email' => $client1->email,
            'total_amount' => 15000000,
            'dp_amount' => 5000000,
            'remaining_amount' => 10000000,
            'start_date' => Carbon::now()->subDays(10),
            'end_date' => Carbon::now()->addDays(20),
            'status' => 'development',
            'progress_percentage' => 30,
            'whatsapp_link' => 'https://wa.me/628123456789',
        ]);

        $project2 = Project::create([
            'client_id' => $client2->id,
            'name' => 'Mobile App Personal',
            'description' => 'Aplikasi mobile untuk manajemen keuangan pribadi.',
            'client_name' => $client2->name,
            'client_phone' => $client2->phone,
            'client_email' => $client2->email,
            'total_amount' => 8000000,
            'dp_amount' => 8000000,
            'remaining_amount' => 0,
            'start_date' => Carbon::now()->subDays(30),
            'end_date' => Carbon::now()->subDays(5),
            'status' => 'completed',
            'progress_percentage' => 100,
            'whatsapp_link' => 'https://wa.me/6282233445566',
        ]);

        // Attach Teams
        $project1->teams()->attach([$team1->id, $team2->id]);
        $project2->teams()->attach([$team1->id]);
    }
}

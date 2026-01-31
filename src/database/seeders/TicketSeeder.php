<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $teknisi = User::where('role', 'teknisi')->first();

        if ($users->isEmpty() || !$teknisi) {
            return;
        }

        // Get categories from database instead of hardcoding
        $categories = \App\Models\Category::where('is_active', true)->get();
        if ($categories->isEmpty()) {
            return; // No categories available
        }
        
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $statuses = ['open', 'in_progress', 'solved', 'closed'];

        foreach ($users as $user) {
            // Create 2-3 tickets per user
            for ($i = 0; $i < rand(2, 3); $i++) {
                $status = $statuses[array_rand($statuses)];
                $category = $categories->random();
                
                Ticket::create([
                    'ticket_number' => Ticket::generateTicketNumber(),
                    'user_id' => $user->id,
                    'assigned_to' => rand(0, 1) ? $teknisi->id : null,
                    'title' => 'Sample Ticket ' . ($i + 1),
                    'description' => 'This is a sample ticket description for testing purposes.',
                    'category_id' => $category->id,
                    'priority' => $priorities[array_rand($priorities)],
                    'status' => $status,
                    'solved_at' => $status === 'solved' || $status === 'closed' ? now() : null,
                    'closed_at' => $status === 'closed' ? now() : null,
                ]);
            }
        }
    }
}

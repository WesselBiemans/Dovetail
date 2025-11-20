<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Message;
use App\Models\Server;
use App\Models\ServerMember;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users
        $users = User::factory(10)->create();

        // Create servers with owners
        $servers = Server::factory(5)->create([
            'owner_id' => fn() => $users->random()->id,
        ]);

        // Add server members
        foreach ($servers as $server) {
            // Add the owner as an admin
            ServerMember::factory()->create([
                'user_id' => $server->owner_id,
                'server_id' => $server->id,
                'role' => 'admin',
            ]);

            // Add random members
            $randomUsers = $users->random(rand(3, 7));
            foreach ($randomUsers as $user) {
                // Skip if user is already a member (owner)
                if ($user->id === $server->owner_id) {
                    continue;
                }

                ServerMember::factory()->create([
                    'user_id' => $user->id,
                    'server_id' => $server->id,
                ]);
            }
        }

        // Create channels for each server
        foreach ($servers as $server) {
            $channels = Channel::factory(rand(3, 6))->create([
                'server_id' => $server->id,
            ]);

            // Create messages in each channel
            foreach ($channels as $channel) {
                $serverMembers = $server->members->pluck('user_id');

                Message::factory(rand(10, 30))->create([
                    'channel_id' => $channel->id,
                    'user_id' => fn() => $serverMembers->random(),
                ]);
            }
        }
    }
}


<?php

namespace Database\Seeders;

use App\Models\ReservationStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReservationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timestamp = ['created_at' => now(), 'updated_at' => now()];

        ReservationStatus::insert([
            array_merge($timestamp, ['id' => Str::uuid(), 'name' => 'Booked']),
            array_merge($timestamp, ['id' => Str::uuid(), 'name' => 'Cancel']),
            array_merge($timestamp, ['id' => Str::uuid(), 'name' => 'On Progress']),
            array_merge($timestamp, ['id' => Str::uuid(), 'name' => 'Done']),
        ]);
    }
}

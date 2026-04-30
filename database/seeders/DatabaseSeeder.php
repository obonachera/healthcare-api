<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Factories\PatientFactory;
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
        PatientFactory::new()->createMany(35);
    }
}

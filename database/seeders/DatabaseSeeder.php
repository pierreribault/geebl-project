<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Event;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->admin()->create(['email' => 'test@test.fr']);

        for ($i = 0; $i < 10; $i++) {
            Company::factory()
                ->has(Seller::factory()->for(User::factory())->owner())
                ->has(Seller::factory()->for(User::factory())->reviewer())
                ->has(Seller::factory()->for(User::factory())->consumer())
                ->has(
                    Seller::factory()
                        ->for(User::factory())
                        ->has(Event::factory()->count(3))
                        ->redactor()
                )
                ->create();
        }
    }
}

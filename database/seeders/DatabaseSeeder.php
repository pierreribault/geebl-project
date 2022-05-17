<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Product;
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
                ->has(User::factory()->owner())
                ->has(User::factory()->reviewer())
                ->has(User::factory()->consumer())
                ->has(
                    User::factory()
                        ->has(Event::factory()->count(3))
                        ->redactor()
                )
                ->create();
        }

        for ($i = 0; $i < 10; $i++) {
            Invoice::factory()
            ->for(User::factory())
            ->for(Product::factory()->for(User::factory()->admin()))
            ->for(Company::factory())
            ->create();
        }
    }
}

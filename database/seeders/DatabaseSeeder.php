<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\News;
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
            Invoice::factory()
            ->for(User::factory())
            ->for(Product::factory())
            ->for(
                Company::factory()
                    ->has(User::factory()->owner())
                    ->has(User::factory()->reviewer())
                    ->has(User::factory()->consumer())
                    ->has(
                        User::factory()->has(
                            Event::factory()
                                ->count(3)
                                ->for(
                                    City::factory()->for(Country::factory())
                                )
                        )->redactor()
                    )
            )
            ->create();
        }
    }
}

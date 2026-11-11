<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ShortUrl>
 */
class ShortUrlFactory extends Factory
{
    protected $model = ShortUrl::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'user_id' => User::factory(),
            'original_url' => fake()->url(),
            'short_code' => Str::random(6),
            'visits' => 0,
        ];
    }
}

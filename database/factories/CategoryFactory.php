<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Electronics',
            'Fashion',
            'Home & Garden',
            'Collectibles',
            'Sports & Outdoors',
            'Books',
            'Art',
            'Jewelry',
            'Vehicles',
            'Music Instruments',
            'Antiques',
            'Toys & Hobbies',
            'Business & Industrial',
            'Health & Beauty',
            'Real Estate',
            'Services',
        ];

        return [
            // This is the crucial part: define how 'category_name' is generated
            'category_name' => $this->faker->unique()->randomElement($categories),
            // Optional: if your 'categories' table has a 'description' column and it's fillable
            'description' => $this->faker->sentence(5), // Generates a sentence of 5 words
        
        ];
    }
}

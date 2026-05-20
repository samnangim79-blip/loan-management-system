<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
    protected $model = Group::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $groupTypes = [
            'Small Business Group',
            'Agriculture Cooperative Group',
            'Women Empowerment Group',
            'Youth Entrepreneur Group',
            'Rural Development Group',
            'Market Vendor Association',
            'Handicraft Producer Group',
            'Rice Farmer Collective',
            'Fishermen Association',
            'Textile Worker Group',
            'Community Development Group',
            'Microfinance Group'
        ];

        $groupSuffixes = ['A', 'B', 'C', '1', '2', '3', 'North', 'South', 'East', 'West'];

        return [
            'group_name' => fake()->randomElement($groupTypes) . ' ' . fake()->randomElement($groupSuffixes),
            'date_issue' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'added_by' => 1, // Default user
            'added_date' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'updated_by' => null,
            'updated_date' => null,
        ];
    }

    /**
     * Create a group with a specific issue date range
     */
    public function issuedBetween($startDate, $endDate): Factory
    {
        return $this->state(fn (array $attributes) => [
            'date_issue' => fake()->dateTimeBetween($startDate, $endDate)->format('Y-m-d'),
        ]);
    }

    /**
     * Create a recently issued group
     */
    public function recent(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'date_issue' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
        ]);
    }
}

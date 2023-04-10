<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Topics;

class TopicsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $topicsData = [
            [
                "topic" => "Handy-Ideen",
            ],
            [
                "topic" => "Geschäftsangelegenheiten: Vodafone",
            ],
            [
                "topic" => "Vodafone Company und ethische Grundsätze",
            ],
            [
                "topic" => "Eine Wirtschafts- und Geschäftsanalyse von Vodafone",
            ],
            [
                "topic" => "Geschichte von Vodafone Breitband",
            ],
        ];
        Topics::insert($topicsData);
    }
}

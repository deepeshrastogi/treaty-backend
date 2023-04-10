<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderTypeTopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orderTypeTopicsArr = [
            "Betriebskostenabrechnungs-Prüfung" => [
                "Handy-Ideen",
                "Geschäftsangelegenheiten: Vodafone",
                "Vodafone Company und ethische Grundsätze",
                "Eine Wirtschafts- und Geschäftsanalyse von Vodafone",
                "Geschichte von Vodafone Breitband",
            ],
            "Entsorgung" => [
                "Geschäftsangelegenheiten: Vodafone",
                "Vodafone Company und ethische Grundsätze",
            ],
            "Betriebskostenabrechnungs-Erstellung" => [
                "Handy-Ideen",
                "Geschäftsangelegenheiten: Vodafone",
                "Vodafone Company und ethische Grundsätze",
            ],
            "Dauermietrechnung / Indexanpassung prüfen" => [
                "Eine Wirtschafts- und Geschäftsanalyse von Vodafone",
                "Geschichte von Vodafone Breitband",
            ],
            "Standortprojekte" => [
                "Vodafone Company und ethische Grundsätze",
            ],
            "Versorgung" => [
                "Geschäftsangelegenheiten: Vodafone",
                "Vodafone Company und ethische Grundsätze",
            ],
            "Vertragsmanagement" => [
                "Geschäftsangelegenheiten: Vodafone",
                "Vodafone Company und ethische Grundsätze",
                "Eine Wirtschafts- und Geschäftsanalyse von Vodafone",
                "Geschichte von Vodafone Breitband",
            ],
            "Wartungsmanagement" => [
                "Handy-Ideen",
                "Geschäftsangelegenheiten: Vodafone",
            ],
            "Sonstiges" => [ 
                "Eine Wirtschafts- und Geschäftsanalyse von Vodafone",
                "Geschichte von Vodafone Breitband",
            ]
        ];

        foreach($orderTypeTopicsArr as $key => $orderType){
            if(!empty($orderType)){
                $oTypeId = \App\Models\OType::where(["label" => $key])->first();
                $topicIds = \App\Models\Topics::whereIn("topic",$orderType)->pluck('id')->toArray();
                if(!empty($topicIds)){
                    $oTypeId->topics()->attach($topicIds);
                }
            }
        }
    }
}

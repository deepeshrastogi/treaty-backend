<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OType;

class OtypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oTypeData = [
            [
                "label" => "Betriebskostenabrechnungs-Prüfung",
                "value" => "orderNewOperatingCostsCheck",
            ],
            [
                "label" => "Entsorgung",
                "value" => "orderNewDisposal",
            ],
            [
                "label" => "Reports",
                "value" => "orderNewReports",
            ],
            [
                "label" => "Betriebskostenabrechnungs-Erstellung",
                "value" => "orderNewOperatingCostAccountingCreation",
            ],
            [
                "label" => "Dauermietrechnung / Indexanpassung prüfen",
                "value" => "orderNewPermanentRentInvoiceIndexAdjustmentCheck",
            ],
            [
                "label" => "Standortprojekte",
                "value" => "orderNewSiteProjects",
            ],
            [
                "label" => "Versorgung",
                "value" => "orderNewSupply",
            ],
            [
                "label" => "Vertragsmanagement",
                "value" => "orderNewContractManagement",
            ],
            [
                "label" => "Wartungsmanagement",
                "value" => "orderNewMaintenanceManagement",
            ],
            [
                "label" => "Sonstiges",
                "value" => "orderNewOthers",
            ]
        ];
        OType::insert($oTypeData);
    }
}

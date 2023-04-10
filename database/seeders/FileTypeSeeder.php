<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FileTypes;

class FileTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fileTypesData = [
            ["file_type" => "Betriebskostenabrechnung (BKA)"],
            ["file_type" => "Heizkostenabrechnung (HKA) falls separat"],
            ["file_type" => "Korrespondenz"],
            ["file_type" => "Mietvertrag"],
            ["file_type" => "Sonstige"],
            ["file_type" => "Nachtrag"],
            ["file_type" => "Sonstige Mietanpassungen"],
            ["file_type" => "Belege"],
            ["file_type" => "Optionsziehung"],
            ["file_type" => "Übergabeprotokoll"],
            ["file_type" => "Kostenübersichten"],
            ["file_type" => "Sonstige Rechnungen"],
            ["file_type" => "Korrektur BKA"],
            ["file_type" => "Kündigung"],
            ["file_type" => "Sonstiges"],
            ["file_type" => "Tatsächlich gezahlte Vorauszahlungen"],
            ["file_type" => "Vorherige Dauermietrechnungen"],
            ["file_type" => "Flächen suchen"],
            ["file_type" => "Übergabeprotokoll"],
            ["file_type" => "Verhandlungen"],
            ["file_type" => "Kündigungsfristen"],
            ["file_type" => "Laufzeitende"],
            ["file_type" => "Auto. Laufzeitverlängerung"],
            ["file_type" => "Wartungsvertrag"],
            
        ];
        FileTypes::insert($fileTypesData);
    }
}

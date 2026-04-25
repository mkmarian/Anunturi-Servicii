<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CountyCitySeeder extends Seeder
{
    /**
     * Toate cele 41 de judete + Municipiul Bucuresti
     * cu orasele/municipiile/comunele de referinta din fiecare.
     */
    public function run(): void
    {
        // Format: 'Denumire Judet' => ['cod', ['Oras1', 'Oras2', ...]]
        $data = [
            'Alba'             => ['AB', ['Alba Iulia', 'Aiud', 'Blaj', 'Câmpeni', 'Cugir', 'Ocna Mureș', 'Sebeș', 'Zlatna', 'Abrud', 'Baia de Arieș', 'Teiuș']],
            'Arad'             => ['AR', ['Arad', 'Curtici', 'Ineu', 'Lipova', 'Nădlac', 'Pâncota', 'Pecica', 'Sebiș', 'Chișineu-Criș', 'Sântana']],
            'Argeș'            => ['AG', ['Pitești', 'Câmpulung', 'Curtea de Argeș', 'Mioveni', 'Costești', 'Topoloveni', 'Ștefănești', 'Călinești', 'Leordeni']],
            'Bacău'            => ['BC', ['Bacău', 'Onești', 'Moinești', 'Comănești', 'Buhuși', 'Dărmănești', 'Slănic Moldova', 'Târgu Ocna', 'Podu Turcului']],
            'Bihor'            => ['BH', ['Oradea', 'Salonta', 'Beiuș', 'Marghita', 'Aleșd', 'Ștei', 'Valea lui Mihai', 'Nucet', 'Săcueni']],
            'Bistrița-Năsăud'  => ['BN', ['Bistrița', 'Năsăud', 'Beclean', 'Sângeorz-Băi', 'Nușeni']],
            'Botoșani'         => ['BT', ['Botoșani', 'Dorohoi', 'Darabani', 'Săveni', 'Flămânzi', 'Ștefănești', 'Bucecea', 'Trușești']],
            'Brăila'           => ['BR', ['Brăila', 'Ianca', 'Însurăței', 'Făurei']],
            'Brașov'           => ['BV', ['Brașov', 'Codlea', 'Făgăraș', 'Ghimbav', 'Predeal', 'Râșnov', 'Rupea', 'Săcele', 'Zărnești', 'Victoria']],
            'București'        => ['B',  ['Sector 1', 'Sector 2', 'Sector 3', 'Sector 4', 'Sector 5', 'Sector 6']],
            'Buzău'            => ['BZ', ['Buzău', 'Râmnicu Sărat', 'Nehoiu', 'Pătârlagele', 'Pogoanele', 'Berca']],
            'Călărași'         => ['CL', ['Călărași', 'Oltenița', 'Budești', 'Lehliu Gară', 'Fundulea']],
            'Caraș-Severin'    => ['CS', ['Reșița', 'Caransebeș', 'Bocșa', 'Moldova Nouă', 'Oravița', 'Anina', 'Băile Herculane', 'Otelu Roșu']],
            'Cluj'             => ['CJ', ['Cluj-Napoca', 'Turda', 'Dej', 'Câmpia Turzii', 'Gherla', 'Huedin', 'Câțcău', 'Florești', 'Apahida', 'Baciu']],
            'Constanța'        => ['CT', ['Constanța', 'Mangalia', 'Medgidia', 'Năvodari', 'Cernavodă', 'Eforie', 'Hârșova', 'Murfatlar', 'Ovidiu', 'Techirghiol']],
            'Covasna'          => ['CV', ['Sfântu Gheorghe', 'Târgu Secuiesc', 'Covasna', 'Baraolt', 'Întorsura Buzăului']],
            'Dâmbovița'        => ['DB', ['Târgoviște', 'Moreni', 'Pucioasa', 'Găești', 'Fieni', 'Titu', 'Răcari', 'Voluntari']],
            'Dolj'             => ['DJ', ['Craiova', 'Băilești', 'Calafat', 'Dăbuleni', 'Filiaș', 'Segarcea', 'Bechet']],
            'Galați'           => ['GL', ['Galați', 'Tecuci', 'Târgu Bujor', 'Berești', 'Pechea', 'Tulucești']],
            'Giurgiu'          => ['GR', ['Giurgiu', 'Bolintin-Vale', 'Mihăilești']],
            'Gorj'             => ['GJ', ['Târgu Jiu', 'Motru', 'Rovinari', 'Bumbești-Jiu', 'Novaci', 'Tismana', 'Turceni']],
            'Harghita'         => ['HR', ['Miercurea Ciuc', 'Odorheiu Secuiesc', 'Toplița', 'Gheorgheni', 'Cristuru Secuiesc', 'Bălan', 'Borsec']],
            'Hunedoara'        => ['HD', ['Deva', 'Hunedoara', 'Brad', 'Lupeni', 'Petroșani', 'Orăștie', 'Petrila', 'Uricani', 'Vulcan', 'Simeria']],
            'Ialomița'         => ['IL', ['Slobozia', 'Fetești', 'Urziceni', 'Amara', 'Fierbinți-Târg', 'Căzănești']],
            'Iași'             => ['IS', ['Iași', 'Pașcani', 'Hârlău', 'Târgu Frumos', 'Popricani', 'Ungheni', 'Miroslava', 'Lețcani']],
            'Ilfov'            => ['IF', ['Voluntari', 'Buftea', 'Bragadiru', 'Chitila', 'Măgurele', 'Otopeni', 'Pantelimon', 'Popești-Leordeni']],
            'Maramureș'        => ['MM', ['Baia Mare', 'Sighetu Marmației', 'Borșa', 'Vișeu de Sus', 'Câmpulung la Tisa', 'Seini', 'Tăuții-Măgherăuș']],
            'Mehedinți'        => ['MH', ['Drobeta-Turnu Severin', 'Strehaia', 'Orșova', 'Baia de Aramă', 'Vânju Mare']],
            'Mureș'            => ['MS', ['Târgu Mureș', 'Reghin', 'Sighișoara', 'Târnăveni', 'Luduș', 'Sovata', 'Iernut', 'Ungheni']],
            'Neamț'            => ['NT', ['Piatra Neamț', 'Roman', 'Târgu Neamț', 'Bicaz', 'Roznov', 'Dulcești']],
            'Olt'              => ['OT', ['Slatina', 'Caracal', 'Balș', 'Corabia', 'Scornicești', 'Drăgănești-Olt', 'Piatra Olt']],
            'Prahova'          => ['PH', ['Ploiești', 'Câmpina', 'Azuga', 'Băicoi', 'Boldeșt-Scăeni', 'Breaza', 'Bușteni', 'Comarnic', 'Mizil', 'Sinaia', 'Slănic', 'Urlați', 'Vălenii de Munte']],
            'Sălaj'            => ['SJ', ['Zalău', 'Șimleu Silvaniei', 'Jibou', 'Cehu Silvaniei']],
            'Satu Mare'        => ['SM', ['Satu Mare', 'Carei', 'Negrești-Oaș', 'Ardud', 'Tăășnad']],
            'Sibiu'            => ['SB', ['Sibiu', 'Mediaș', 'Copșa Mică', 'Avrig', 'Agnita', 'Cisnădie', 'Dumbrăveni', 'Ocna Sibiului', 'Săliște', 'Tâlmaciu']],
            'Suceava'          => ['SV', ['Suceava', 'Fălticeni', 'Câmpulung Moldovenesc', 'Gura Humorului', 'Radăuți', 'Vatra Dornei', 'Siret', 'Solca', 'Cajvana']],
            'Teleorman'        => ['TR', ['Alexandria', 'Turnu Măgurele', 'Roșiorii de Vede', 'Zimnicea', 'Videle']],
            'Timiș'            => ['TM', ['Timișoara', 'Lugoj', 'Buziaș', 'Deta', 'Faget', 'Jimbolia', 'Recaș', 'Sânnicolau Mare', 'Giroc', 'Ghiroda']],
            'Tulcea'           => ['TL', ['Tulcea', 'Babadag', 'Isaccea', 'Măcin', 'Sulina']],
            'Vâlcea'           => ['VL', ['Râmnicu Vâlcea', 'Drăgășani', 'Băile Olănești', 'Băile Govora', 'Băbeni', 'Brezoi', 'Horezu', 'Ocnele Mari']],
            'Vaslui'           => ['VS', ['Vaslui', 'Bârlad', 'Huși', 'Negrești', 'Murgeni']],
            'Vrancea'          => ['VN', ['Focșani', 'Adjud', 'Mărășești', 'Panciu', 'Odobești']],
        ];

        foreach ($data as $countyName => [$code, $cities]) {
            $countySlug = Str::slug($countyName);
            $countyId = DB::table('counties')->insertGetId([
                'name'       => $countyName,
                'slug'       => $countySlug,
                'code'       => $code,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $cityRows = [];
            foreach ($cities as $cityName) {
                $citySlug = Str::slug($cityName);
                $cityRows[] = [
                    'county_id'  => $countyId,
                    'name'       => $cityName,
                    'slug'       => $citySlug,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('cities')->insert($cityRows);
        }
    }
}

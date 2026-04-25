<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Categorii principale cu subcategorii
        $categories = [
            ['name' => 'Constructii & Renovari', 'icon' => '🏗️', 'children' => [
                'Zidarie si structuri',
                'Amenajari interioare',
                'Amenajari exterioare',
                'Demolari',
                'Sape si pardoseli',
                'Tencuieli si glet',
                'Fatade',
            ]],
            ['name' => 'Instalatii electrice', 'icon' => '⚡', 'children' => [
                'Instalatii electrice rezidentiale',
                'Instalatii electrice industriale',
                'Tablouri electrice',
                'Sisteme de alarma',
                'Automatizari si BMS',
                'Retele de date si telecomunicatii',
            ]],
            ['name' => 'Instalatii sanitare & termice', 'icon' => '🔧', 'children' => [
                'Instalatii sanitare',
                'Centrale termice si incalzire',
                'Aer conditionat si HVAC',
                'Pompe de caldura',
                'Instalatii gaz',
                'Desfundari si igienizari',
            ]],
            ['name' => 'Tamplarie & Mobilier', 'icon' => '🚪', 'children' => [
                'Tamplarie PVC si aluminiu',
                'Tamplarie lemn',
                'Mobila la comanda',
                'Usi si ferestre',
                'Parchet si dusumele',
            ]],
            ['name' => 'Zugraveli & Vopsitorii', 'icon' => '🎨', 'children' => [
                'Zugraveli interioare',
                'Vopsitorii exterioare',
                'Decoratiuni murale',
                'Tapet',
                'Gresie si faianta',
            ]],
            ['name' => 'Gradinarit', 'icon' => '🌿', 'children' => [
                'Tuns gard viu si copaci',
                'Sisteme irigatii',
                'Amenajare gradina',
                'Gazon',
                'Plantat si intretinut verde',
            ]],
            ['name' => 'Alte servicii', 'icon' => '🔨', 'children' => [
                'Confectii metalice',
                'Sudura',
                'Sapaturi si terasamente',
                'Cosmetice si saloane',
                'Asistenta animale de companie',
            ]],
        ];

        $sort = 0;
        foreach ($categories as $cat) {
            $parentId = DB::table('service_categories')->insertGetId([
                'parent_id'  => null,
                'name'       => $cat['name'],
                'slug'       => Str::slug($cat['name']),
                'icon'       => $cat['icon'],
                'sort_order' => $sort++,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $childSort = 0;
            foreach ($cat['children'] as $childName) {
                DB::table('service_categories')->insert([
                    'parent_id'  => $parentId,
                    'name'       => $childName,
                    'slug'       => Str::slug($childName),
                    'icon'       => null,
                    'sort_order' => $childSort++,
                    'is_active'  => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

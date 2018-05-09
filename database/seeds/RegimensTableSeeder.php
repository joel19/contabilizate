<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegimensTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$regimens = [
    		[
            'code_rf' => 601,
            'description' => "General de Ley Personas Morales"
        ],[
            'code_rf' => 603,
            'description' => "Personas Morales con Fines no Lucrativos"
        ],[
            'code_rf' => 605,
            'description' => "Sueldos y Salarios e Ingresos Asimilados a Salarios"
        ],[
            'code_rf' => 606,
            'description' => "Arrendamiento"
        ],[
            'code_rf' => 608,
            'description' => "Demás ingresos"
        ]
    	];

        $con = [
            [
                'name' => 'Joel Cano Alarcon', 
                'rfc' => 'CAAJ92091423A', 
                'regimen_id' => '1', 
            ],[
                'name' => 'Jose Miguel Gonzalez', 
                'rfc' => 'GGMJ92091423A', 
                'regimen_id' => '3', 
            ],[
                'name' => 'Jose Juan Gonzalez', 
                'rfc' => 'HASJ92091423A', 
                'regimen_id' => '5', 
            ],[
                'name' => 'Cerlos Hernandez', 
                'rfc' => 'CASJ92091423A', 
                'regimen_id' => '2', 
            ]
        ];
        DB::table('contributors')->insert($con);
    }
}

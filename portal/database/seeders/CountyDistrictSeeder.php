<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{County, District};

class CountyDistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $avon = County::create([
        'code' => 'avon',
        'name' => "Avon"
        ]);

      $bsg = County::create([
        'code' => 'bsg',
        'name' => "Bristol and South Gloucestershire"
        ]);

      $sn = County::create([
        'code' => 'sn',
        'name' => 'Somerset North'
        ]);

      $districts = [
        # Avon
        ["Axe", $avon->id],
        ["Bath", $avon->id],
        ["Bristol South", $avon->id],
        ["Brunel", $avon->id],
        ["Cabot", $avon->id],
        ["Cotswold Edge", $avon->id],
        ["Gordano", $avon->id],
        ["Kingswood", $avon->id],
        ["Wansdyke", $avon->id],

        # Bristol & South Gloucestershire
        ["Bristol North East", $bsg->id],
        ["Bristol North West", $bsg->id],
        ["Bristol South", $bsg->id],
        ["Bristol South West", $bsg->id],
        ["Bristol West", $bsg->id],
        ["Concorde", $bsg->id],
        ["Frome Valley", $bsg->id],
        ["Kingswood North", $bsg->id],
        ["Kingswood South", $bsg->id],
        ["Severnvale", $bsg->id],
        ["South Cotswold", $bsg->id],

        # Somerset North
        ["Avon Valley South", $sn->id],
        ["Bath", $sn->id],
        ["Cam Valley", $sn->id],
        ["Portishead", $sn->id],
        ["Weston-super-Mare", $sn->id],
        ["Wraxhall", $sn->id],
        ["Yeo Vale", $sn->id],
      ];

      foreach($districts as $district)
      {
        District::create([
          'name' => $district[0],
          'county_id' => $district[1],
        ]);
      }
    }
}

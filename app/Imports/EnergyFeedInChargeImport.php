<?php

namespace App\Imports;

use App\Models\EnergyFeedInCharge;
use Maatwebsite\Excel\Concerns\ToModel;

class EnergyFeedInChargeImport implements ToModel
{
    public function model(array $row)
    {
        return new EnergyFeedInCharge([
            'range_from' => $row['Range From'], // Excel column 1
            'range_to' => $row['Range To'], // Excel column 2
            'cost_per_day' => $row['Cost Per Day'], // Excel column 3
            'cost_per_month' => $row['Cost Per Month'], // Excel column 4
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

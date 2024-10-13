<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use HasFactory;

    // Specify the table associated with the model (if not the plural form of the model name)
    protected $table = 'plants';

    // Define fillable attributes for mass assignment
    protected $fillable = [
        'name_of_plants',
    ];

    public function inventoryValuedCrops()
    {
        return $this->hasMany(InventoryValuedCrop::class);
    }
    public function varieties()
    {
        return $this->hasMany(PlantVariety::class);
    }
}

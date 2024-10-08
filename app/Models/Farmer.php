<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    use HasFactory;

    // Specify the table associated with the model (if not the plural form of the model name)
    protected $table = 'farmers'; 

    // Define fillable attributes for mass assignment
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'extension',
        'affiliation_id',
    ];

    // Define a relationship with the Affiliation model
    public function affiliation()
    {
        return $this->belongsTo(Affiliation::class, 'affiliation_id'); // Adjust the affiliation model path if needed
    }
    public function inventoryValuedCrops()
    {
        return $this->hasMany(InventoryValuedCrop::class);
    }

    public static function getUniquePlants()
    {
        return InventoryValuedCrop::with('plant')->pluck('plant.plant_id')->unique();
    }



}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;
    protected $table = 'sectors';
    protected $guarded = [];

    public function alquileres()
    {
        return $this->hasMany(Alquiler::class);
    }
}

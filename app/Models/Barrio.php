<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barrio extends Model
{
    protected $fillable = ['nombre'];

    public function socios()
    {
        return $this->hasMany(Socio::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calle extends Model
{
    protected $fillable = ['nombre', 'habilitado'];

    public function socios()
    {
        return $this->hasMany(Socio::class);
    }
}
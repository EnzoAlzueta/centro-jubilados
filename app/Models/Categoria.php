<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['nombre', 'habilitado'];
    protected $table = 'categoria';

    public function movimiento() {
        return $this->hasMany(Movimiento::class);
    }
}

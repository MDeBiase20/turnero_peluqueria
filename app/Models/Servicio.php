<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    // Define any relationships or attributes for the Servicio model here
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}

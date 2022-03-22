<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    public function cliente()
    {
        return $this->BelongsTo(Cliente::class);
    }

    public function trabajos_eventos()
    {
        return $this->hasMany(TrabajosEventos::class);
    }
}

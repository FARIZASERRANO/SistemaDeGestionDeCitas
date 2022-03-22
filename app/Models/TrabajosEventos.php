<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrabajosEventos extends Model
{
    use HasFactory;

    public function trabajo()
    {
        return $this->BelongsTo(Trabajo::class);
    }

}

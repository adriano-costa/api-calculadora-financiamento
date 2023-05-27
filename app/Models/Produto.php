<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;
    protected $table = 'produtos';
    protected $primaryKey = 'CO_PRODUTO';
    protected $guarded = ['CO_PRODUTO'];

    protected $casts = [
        'PC_TAXA_JUROS' => 'decimal:10',
        'VR_MINIMO' => 'decimal:18',
        'VR_MAXIMO' => 'decimal:18',
    ];
}

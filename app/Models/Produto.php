<?php

namespace App\Models;

use App\Casts\DinheiroCast;
use App\Casts\TaxaCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produto';

    protected $primaryKey = 'CO_PRODUTO';

    protected $guarded = ['CO_PRODUTO'];

    protected $casts = [
        'PC_TAXA_JUROS' => TaxaCast::class,
        'VR_MINIMO' => DinheiroCast::class,
        'VR_MAXIMO' => DinheiroCast::class,
    ];
}

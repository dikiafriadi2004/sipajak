<?php

namespace App\Models\Pajak;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PajakHotel extends Model
{
    use HasFactory;
    protected $table = 'pajak_hotel';
    protected $guarded = [];
}

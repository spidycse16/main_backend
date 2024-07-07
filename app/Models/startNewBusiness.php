<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class startNewBusiness extends Model
{
    use HasFactory;


    protected $fillable=[
        'ShopName',
        'ShopLocation',
        'ShopType',
        'PhoneNumber',
        'Image'

    ];
}

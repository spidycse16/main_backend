<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class MarketPlace extends Model
{
    use HasFactory;


    protected $fillable=[
        'ItemName',
        'ItemPrice',
        'Image',
        'ProductInformation',
        'DeliveryInformation'

    ];
}

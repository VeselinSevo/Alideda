<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function Symfony\Component\String\s;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    // php artisan make:modelCountry -mfc  
// ovo je protekcija, kada se salje request samo ce ova polja dozvoliti da se upisu u bazu, ostala ce biti ignorisana


    // php artisan migrate:fresh  dropovace se sve tabele, ponovo ce laravael proci kroz migrations folder, redom kroz svaki fajl i kreirati tabele, medjutim jer nema --seed opcije nece biti nikaqkviuh podataka u toj tabeli(bazi tj svim tim tabelama).
    protected $fillable = [
        'name',
        'description',
        'price',
        'store_id',
    ];


    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function images()
    {
        return $this->hasMany(\App\Models\ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(\App\Models\ProductImage::class)->where('is_primary', true);
    }
}

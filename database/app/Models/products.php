<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    use HasFactory;
    protected $table="products";

    public function type_products(){
        return $this->belongsTo("App\type_products");
    }
    public function bill_details(){
        return $this->hasMany("App\bill_detail");
    }
    public function comments(){
        return $this->hasMany("App\comments");
    }
  
}

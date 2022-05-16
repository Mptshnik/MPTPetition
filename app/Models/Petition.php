<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petition extends Model
{
    use HasFactory;


    public $timestamps = false;
    protected $fillable = [
        'name',
        'description',
        'image'
    ];

    public function signatures()
    {
        return $this->hasMany(Signature::class);
    }
}

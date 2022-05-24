<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petition extends Model
{
    use HasFactory;


    //public $timestamps = false;
    protected $fillable = [
        'name',
        'description',
        'image'
    ];

    public function votedUsers(){
        return $this->belongsToMany(User::class, 'signatures')->select('email');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function signatures()
    {
        return $this->hasMany(Signature::class);
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];
}

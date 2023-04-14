<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supporter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        "uuid",
        "name",
        "email",
        "data"
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public $casts = [
        "data" => "array"
    ];
}

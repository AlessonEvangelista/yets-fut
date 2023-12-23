<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoccerGinasium extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'nethborhood',
        'city',
    ];

    /**
     * Includes for search in API ( Not Implement ON MVP. But Set on Model for fuctures features).
     */
    public $allowedIncludes = [];
}

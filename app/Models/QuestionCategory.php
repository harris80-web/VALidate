<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', 
        'type',
        'description'
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function questions(): HasMany {
        return $this->hasMany(Question::class, 'category_id');
    }
}

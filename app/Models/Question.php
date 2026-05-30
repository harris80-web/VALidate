<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'category_id', 
        'name', 
        'type'
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function category(): BelongsTo {
        return $this->belongsTo(QuestionCategory::class, 'category_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class, 'question_id');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    /**
     * Get all questions of a given type with their category.
     *
     * @param int|string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllQuestionsByCategoryType($type)
    {
        return self::with(['category', 'options'])
                ->whereHas('category', function ($query) use ($type) {
                    $query->where('type', $type);
                })
                ->get();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Respondent extends Model
{
    public function region(): BelongsTo {
        return $this->belongsTo(Region::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'region_id',
        'type',
        'email',
        'age',
        'gender',
        'service',
        'suggestion',
        'submitted_date'
    ];

    public static function totalCount(): int
    {
        return self::count();
    }

    public static function totalSuggestions(): int
    {
        return self::whereNotNull('suggestion')->count();
    }
}

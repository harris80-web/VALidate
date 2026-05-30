<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Response extends Model
{
    public function respondent(): BelongsTo
    {
        return $this->belongsTo(Respondent::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class);
    }

    protected $fillable = [
        'respondent_id',
        'question_id',
        'question_option_id'
    ];
}

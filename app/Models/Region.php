<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    public function respondents(): HasMany {
        return $this->hasMany(Respondent::class);
    }

     /**
     * Get region
     *
     * @param int|string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllRegion()
    {
        return self::all();
    }
}

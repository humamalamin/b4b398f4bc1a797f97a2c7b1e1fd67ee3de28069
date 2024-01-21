<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TableModel extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'table_number',
        'capacity',
        'is_available'
    ];

    public function reservation()
    {
        return $this->hasMany(Reservation::class);
    }

    public function getIsAvailableAttribute($value)
    {
        if ($value) {
            return 'AVAILABLE';
        }

        return 'NOT AVAILABLE';
    }
}

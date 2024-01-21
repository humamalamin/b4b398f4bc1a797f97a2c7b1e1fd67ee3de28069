<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'table_id',
        'user_id',
        'reservation_date',
        'reservation_time',
        'status_id'
    ];

    protected $casts = [
        'reservation_date' => 'date:Y-m-d',
        'reservation_time' => 'datetime:H:i:m'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function table()
    {
        return $this->belongsTo(TableModel::class, 'table_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(ReservationStatus::class, 'status_id', 'id');
    }
}

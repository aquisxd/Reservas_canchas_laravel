<?php
// app/Models/Reservation.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'court_id',
        'reservation_date',
        'start_time',
        'end_time',
        'total_amount',
        'status',
        'notes'
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'total_amount' => 'decimal:2'
    ];

    // Para Laravel 5.15, usar accessors/mutators tradicionales
    public function getStartTimeAttribute($value)
    {
        return Carbon::createFromFormat('H:i:s', $value)->format('H:i');
    }

    public function getEndTimeAttribute($value)
    {
        return Carbon::createFromFormat('H:i:s', $value)->format('H:i');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('reservation_date', '>=', Carbon::today());
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}

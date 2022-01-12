<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashoutSlotDate extends Model
{
    protected $fillable = [
        'id',
        'selected_day',
        'slot_id',
        'created_at'
    ];
    use HasFactory;

    public function Slot()
    {
        return $this->belongsTo(CashoutSlot::class);
    }
}

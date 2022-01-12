<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashoutSlot extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'location_id',
        'start_date',
        'end_date',
        'created_at'
    ];
    public function location(){
        return $this->belongsTo('App\Models\CashoutLocation','location_id','id');
    }

    /**
     * Get all of the comments for the CashoutSlot
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dates()
    {
        return $this->hasMany(CashoutSlotDate::class, 'slot_id');
    }
}

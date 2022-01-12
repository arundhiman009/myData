<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashoutLocation extends Model
{
    use HasFactory;
    //use HasFactory;
    // public $table = 'cashout_locations';

    protected $fillable = [
        'id',
        'name',
        'city',
        'state',
        'pincode',
		'status',
        'address',
        'created_at'
    ];

    /**
     * Get the user associated with the CashoutLocation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cashoutSlot()
    {
        return $this->hasOne(CashoutSlot::class,'location_id');
    }

}

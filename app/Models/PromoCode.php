<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'status',
        'limit',
        'discount_type',
        'amount',
        'expiry_date'
    ];

    /**
     * The roles that belong to the PromoCode
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'promo_user', 'promo_id', 'user_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'username',
        'password',
        'referred_by',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * The roles that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function promos()
    {
        return $this->belongsToMany(PromoCode::class, 'promo_user', 'user_id', 'promo_id');
    }

    /**
     * Get all of the comments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function numberOfPayments()
    {
        return $this->payments()->whereNotNull('txn_id')->where('status','completed');
    }
   
    public function transactions()
    {
        return $this->hasMany(PaymentInfo::class);
    }
  
    public function cashouts()
    {
        return $this->hasMany(CashoutRequest::class)->orderBy('created_at','desc');
    }
    
    public function cashier_cashouts()
    {
        return $this->hasMany(CashoutRequest::class,'to_id','id')->orderBy('created_at','desc');
    }
    
    public function cashier_info() 
    {
        return $this->belongsTo(User::class,'cashier_id');
    } 

    public function chats()
    {
        return $this->hasMany(chat::class,'Sender_id','id');
    }

    public function referred_info()
    {
        return $this->belongsTo(User::class,'referred_by');
    }
    public function paymentsInfo(){

    return $this->hasMany(PaymentInfo::class,'to_id');

    }
    protected function defaultProfilePhotoUrl()
    {
        if($this->name != NULL || $this->lastname != NULL){
            return 'https://ui-avatars.com/api/?name='.urlencode($this->name.' '.$this->lastname).'&color=fff&background=60AEFF';
        }
        else{
            return 'https://ui-avatars.com/api/?name='.urlencode($this->username).'&color=fff&background=60AEFF';
        }
    }
}

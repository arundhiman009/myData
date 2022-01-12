<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $guarded = ['sender_id','receiver_id','message','is_last','is_read','is_group','group_identifier'];

    public function toMessage(){
	    return $this->belongsTo(User::class,'receiver_id');
    }

    public function fromMessage(){
	    return $this->belongsTo(User::class,'sender_id');
    }
}
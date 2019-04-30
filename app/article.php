<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class article extends Model

{

	 protected $fillable = [
        'title', 'user_id', 'body',
    ];

      public function user(){
    	return $this->belongsTo(User::class);
    }
}

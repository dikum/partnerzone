<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
	    'title',
	    'notification',
	    'read',
	    'user_id',
	    'job_id',
    ];
}

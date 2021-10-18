<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginSessionLog extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'id','username','domain_id','user_id','country','region_name','city','ip',
  ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','domain_name','company_name','reseller_id','user_role_id','reseller_role_id','is_active','owner_id',
    ];

    
}

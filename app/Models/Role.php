<?php 
namespace App\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','slug','display_name','description',
    ];
    
}
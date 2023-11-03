<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ModuleActionPermission extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'modules_actions_permissions';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'module_id',
        'module_action_id',
        'name',
        'description',
        'link',
        'active',
    ];
}

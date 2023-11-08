<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserGroupHasAbilities extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user_group_has_abilities';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_group_id',
        'ability_id',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property-read int $id
 * @property string $username
 * @property string $password
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $hidden = ['password'];

    protected $table = 'users';
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class Team extends Model
{
    public static function boot()
    {
        parent::boot();

        // here assign this team to a global user with global default role
        self::created(function ($model) {
            // temporary: get session team_id for restore at end
            $session_team_id = getPermissionsTeamId();
            // set actual new team_id to package instance
            setPermissionsTeamId($model);
            // get the admin user and assign roles/permissions on new team model
            User::find('your_user_id')->assignRole('Super Admin');
            // restore session team_id to package instance using temporary value stored above
            setPermissionsTeamId($session_team_id);
        });
    }
}

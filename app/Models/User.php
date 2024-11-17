<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail

{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'phone_number',
        'is_agent',
        'is_active',
        'agent_application'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function isUser()
    {
        return $this->role === 'user';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isAgent()
    {
        return $this->role === 'agent';
    }

    public function isAgentVerified()
    {
        return $this->isAgent() && $this->is_agent === 1;
    }

    public function isAgentNotVerified()
    {
        return $this->isAgent() && $this->is_agent === 0;
    }

    public function isAgentApplicationSubmitted()
    {
        return $this->isUser() && $this->agent_application === 1;
    }

    public function isAgentApplicationNotSubmitted()
    {
        return $this->isUser() && $this->agent_application === 0;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'user') {
            return $this->isUser() || $this->isAgent();
        } elseif ($panel->getId() === 'admin') {
            return $this->isAdmin();
        } else {
            return false;
        }
    }

    public function customer()
    {
        return $this->hasMany(Customer::class, 'user_id', 'id');
    }

    public function filesuser()
    {
        return $this->hasOne(FilesUser::class, 'user_id', 'id');
    }
    public function manifest()
    {
        return $this->hasOne(Manifest::class, 'by_id', 'id');
    }
}

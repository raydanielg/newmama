<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'is_admin', 'is_active', 'mother_id', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get the mother associated with this user.
     */
    public function mother()
    {
        return $this->belongsTo(Mother::class);
    }

    /**
     * Check if user is a mother
     */
    public function getIsMotherAttribute(): bool
    {
        return $this->role === 'mother' || $this->mother_id !== null;
    }

    /**
     * Check if user is an admin
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->role === 'admin' || $this->attributes['is_admin'] ?? false;
    }

    /**
     * Redirect user based on role after login
     */
    public function getRedirectRoute(): string
    {
        if ($this->is_admin) {
            return 'admin.dashboard';
        }
        if ($this->is_mother) {
            return 'mother.dashboard';
        }
        return 'home';
    }
}

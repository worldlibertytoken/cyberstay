<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['tenant_id', 'name', 'email', 'password', 'role', 'phone', 'is_active'])]
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
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function canManageHotel(): bool
    {
        return in_array($this->role, ['super_admin', 'owner', 'manager'], true);
    }

    public function isReceptionist(): bool
    {
        return $this->role === 'receptionist';
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            'super_admin' => 'Super Admin',
            'owner' => 'Owner',
            'manager' => 'Manager',
            default => 'Receptionist',
        };
    }

    public function initials(): string
    {
        $parts = explode(' ', trim($this->name));

        return strtoupper(
            (strlen($parts[0]) > 0 ? substr($parts[0], 0, 1) : '')
            .(strlen($parts[1] ?? '') > 0 ? substr($parts[1], 0, 1) : '')
        );
    }
}

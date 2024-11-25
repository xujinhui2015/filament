<?php

namespace App\Models;

use App\Support\Traits\FormatModelDateTrait;
use Exception;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string|null $phone 手机号
 * @property string|null $avatar_url 用户头像
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|User query()
 */
class User extends Authenticatable implements HasAvatar, FilamentUser
{
    use HasFactory, HasRoles, Notifiable, FormatModelDateTrait;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : null;
    }

    /**
     * @throws Exception
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() == 'admin') {
            return true;
        }
        return config('extend.custom.' . $panel->getId().'.enabled');
    }

    public function isAdmin(): bool
    {
        return $this->id == 1;
    }
}

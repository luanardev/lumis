<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property mixed $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property mixed $email_verified_at
 * @property string $status
 * @property string $staff_id
 * @property string $campus_id
 */
class User extends Authenticatable
{
    use HasApiTokens,
        HasUuids,
        HasFactory,
        Notifiable,
        Loggable,
        HasRoles;

    /**
     * Default admin
     *
     */
    const ADMIN = 'Superuser';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The primary key associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'password', 'status', 'staff_id', 'campus_id'];

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
    ];

    /**
     * Search Scope for Laravel Livewire DataTable
     * @var string $term
     * @return Builder
     */
    public static function search(string $term): Builder
    {
        return static::where('name', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%");
    }

    /**
     * Create default Admin
     *
     * @return static
     */
    public static function createAdmin(): static
    {
        return static::firstOrNew([
            'name' => static::ADMIN,
            'email' => 'superuser@lumis.com',
            'email_verified_at' => now(),
            'password' => bcrypt('superuser'),
            'status' => 'Active',
            'staff_id' => NULL,
            'campus_id' => NULL
        ]);
    }

    /**
     * @param string $email
     * @return static|null
     */
    public static function getByEmail(string $email): static|null
    {
        return static::where('email', $email)->first();
    }

    /**
     * @param string $staffId
     * @return static|null
     */
    public static function getByStaff(string $staffId): static|null
    {
        return static::where('staff_id', $staffId)->first();
    }

    /**
     * @param string $campusId
     * @return Collection
     */
    public static function getByCampus(string $campusId): Collection
    {
        return static::where('campus_id', $campusId)->get();
    }

    /**
     * Always encrypt the password when it is updated.
     *
     * @param string $password
     * @return void
     */
    public function setPassword(string $password): void
    {
        $hashed = bcrypt($password);
        $this->setAttribute('password', $hashed);
    }

    /**
     * @param string $campusId
     * @return void
     */
    public function setCampus(string $campusId): void
    {
        $this->setAttribute('campus_id', $campusId);
    }

    /**
     * @param string $staffId
     * @return void
     */
    public function setStaff(string $staffId): void
    {
        $this->setAttribute('staff_id', $staffId);
    }

    /**
     * Deactivate user account
     *
     * @return void
     */
    public function deactivate(): void
    {
        $this->setAttribute('status', 'Inactive');
        $this->save();
    }

    /**
     * Activate user account
     *
     * @return void
     */
    public function activate(): void
    {
        $this->setAttribute('status', 'Active');
        $this->save();
    }

    /**
     * Check Whether User is Administrator
     * @return boolean
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(static::ADMIN);
    }

    /**
     * Search Scope for Laravel Livewire DataTable
     *
     *
     * @var Builder $query
     * @var string $term
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(
            fn($query) => $query->where($this->table.'.name', 'like', "%{$term}%")
                ->orWhere($this->table.'.email', 'like', "%{$term}%")
        );
    }

    /**
     * Check whether User email exists
     *
     * @return boolean
     */
    public function emailTaken(): bool
    {
        $exists = static::where('email', $this->email)->exists();
        return (bool)$exists;
    }

    /**
     * Check whether User is deactivated
     *
     * @return boolean
     */
    public function deactivated(): bool
    {
        return strtolower($this->status) == strtolower('inactive');
    }

    /**
     * Get User Assigned Roles
     *
     * @return string
     */
    public function getRoles(): string
    {
        $roles = $this->roles()->pluck('name')->toArray();
        return implode(',', $roles);
    }


    /**
     * Check whether User is activated
     *
     * @return boolean
     */
    public function activated(): bool
    {
        return strtolower($this->status) == strtolower('active');
    }


    /**
     * Get admin Apps
     *
     * @return Collection
     */
    private function getAdminApps(): Collection
    {
        $modules = $this->getAllPermissions()
            ->groupBy('app_id')
            ->keys()
            ->toArray();

        return App::whereIn('id', $modules)
            ->where('hide', 'No')
            ->where('status', 'Enabled')
            ->where('group', App::GROUP_ADMIN)
            ->orderBy('priority')
            ->get();
    }

    /**
     * Check whether User has Apps allocated
     * @return boolean
     */
    public function hasApps(): bool
    {
        $apps = $this->getApps();
        return count($apps) > 0;
    }

    /**
     * Get Apps allocated to User
     *
     * @return array|Collection
     */
    public function getApps(): array|Collection
    {
        if ($this->hasRole(static::ADMIN)) {
            return App::getDefault();
        } else {
            return $this->getAdminApps();
        }
    }

    /**
     * Check whether User has an App allocated
     *
     * @param string $name Application name
     * @return boolean
     */
    public function hasApp(string $name): bool
    {
        $apps = $this->getAdminApps();

        foreach ($apps as $app) {
            if (strtolower($app->name) == strtolower($name)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return string
     */
    public function statusBadge(): string
    {
        return $this->activated()?
            "<span class='badge badge-success'>{$this->status}</span>":
            "<span class='badge badge-danger'>{$this->status}</span>";
    }

    /**
     * Date of creation
     *
     * @return mixed
     */
    public function createdDate(): mixed
    {
        return (isset($this->created_at))? $this->created_at->format('d-M-Y'):null;
    }


}

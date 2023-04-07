<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $id
 * @property string $name
 * @property string $alias
 * @property string $display_name
 * @property string $group
 * @property string $url
 * @property string $hide
 * @property integer $priority
 * @property string $status
 * @property Collection $permissions
 */
class App extends Model
{
    use HasFactory, HasUuids;

    const DEFAULT = 'controlpanel';
    const GROUP_ADMIN = 'admin';
    const GROUP_CLIENT = 'client';
    const GROUP_SYSTEM = 'system';
    const PRIORITY = 0;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'apps_installed';

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
    protected $fillable = ['name', 'alias', 'display_name', 'group', 'url', 'icon', 'priority', 'status', 'hide'];

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = ['created_at', 'updated_at'];

    /**
     * Search for app
     * @return Collection
     * @var string $term
     */
    public static function search(string $term): Collection
    {
        return static::withoutDefault()->where('name', 'like', "%{$term}%")
            ->orWhere('display_name', 'like', "%{$term}%")
            ->get();
    }

    /**
     * Get all Apps except Default
     *
     * @return Builder
     */
    public static function withoutDefault(): Builder
    {
        return static::where('alias','<>', static::DEFAULT)
            ->where('group', '<>', static::GROUP_SYSTEM)
            ->orderBy('priority');
    }

    /**
     * Check whether App is installed
     *
     * @param string $appName
     * @return boolean
     */
    public static function installed(string $appName): bool
    {
        $app = static::findByName($appName);
        return !empty($app);
    }

    /**
     * Find record by name
     * @param string $name
     * @return null|App
     */
    public static function findByName(string $name): ?App
    {
        return static::where('name', $name)->orWhere('alias', $name)->first();
    }


    /**
     * Get default Apps
     *
     * @return Collection
     */
    public static function getDefault(): Collection
    {
        return static::where('alias', static::DEFAULT)
            ->orderBy('priority')
            ->get();
    }

    /**
     * Get client Apps
     *
     * @return Collection
     */
    public static function getClient(): Collection
    {
        return static::where('group', static::GROUP_CLIENT)
            ->where('hide', 'No')
            ->where('status', 'Enabled')
            ->orderBy('priority')
            ->get();
    }

    /**
     *
     * @return  Collection
     */
    public static function getWithoutDefault(): Collection
    {
        return static::withoutDefault()->get();
    }

    /**
     *
     * @return  Collection
     */
    public static function getTemporaryHidden(): Collection
    {
        return static::where('hide', 'Yes')->orderBy('priority')->get();
    }

    /**
     *
     * @return  Collection
     */
    public static function getEnabled(): Collection
    {
        return static::where('status', 'Enabled')->orderBy('priority')->get();
    }

    /**
     *
     * @return  Collection
     */
    public static function getDisabled(): Collection
    {
        return static::where('status', 'Disabled')->orderBy('priority')->get();
    }

    /**
     *
     * @return  Collection
     */
    public static function getPriority(): Collection
    {
        return static::orderBy('priority')->get();
    }

    /**
     *
     * @return  Collection
     */
    public static function getAdminGroup(): Collection
    {
        return static::where('group', static::GROUP_ADMIN)
            ->orderBy('priority')
            ->get();
    }

    /**
     * @return HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class, 'app_id');
    }

    /**
     * Check Whether system has permissions
     *
     * @return boolean
     */
    public function hasPermissions(): bool
    {
        return !empty($this->permissions);
    }

    /**
     * Get roles assigned this app;
     *
     * @return array
     */
    public function getAssignedRoles(): array
    {
        $roles = Role::all();
        $assigned = [];
        foreach ($roles as $role) {
            if ($role->hasApp($this)) {
                $assigned[] = $role;
            }
        }
        return $assigned;
    }

    /**
     * Get roles not assigned this app;
     *
     * @return array
     */
    public function getUnassignedRoles(): array
    {
        $roles = Role::all();
        $unassigned = [];
        foreach ($roles as $role) {
            if (!$role->hasApp($this) && !$role->isAdmin()) {
                $unassigned[] = $role;
            }
        }
        return $unassigned;
    }

    /**
     * Search Scope for Laravel Livewire DataTable
     * @var Builder $query
     * @var string $term
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(
            fn($query) => $query->where('name', 'like', "%{$term}%")
        );
    }

    /**
     * Check whether module is not default
     *
     * @return boolean
     */
    public function isNotDefault(): bool
    {
        return !$this->isDefault();
    }

    /**
     * Check whether module is default
     *
     * @return boolean
     */
    public function isDefault(): bool
    {
        return ($this->alias == static::DEFAULT);
    }

    /**
     * Enable module
     *
     * @return void
     */
    public function enable(): void
    {
        $this->setAttribute('status', 'Enabled');
        $this->save();
    }

    /**
     * Disable module
     *
     * @return void
     */
    public function disable(): void
    {
        $this->setAttribute('status', 'Disabled');
        $this->save();
    }

    /**
     * Hide module
     *
     * @return void
     */
    public function hide(): void
    {
        $this->setAttribute('hide', 'Yes');
        $this->save();
    }

    /**
     * Unhide module
     *
     * @return void
     */
    public function unhide(): void
    {
        $this->setAttribute('hide', 'No');
        $this->save();
    }

    /**
     * Check whether app is disabled
     *
     * @return boolean
     */
    public function disabled(): bool
    {
        return strtolower($this->status) == strtolower('disabled');
    }

    /**
     * Check whether app is hidden
     *
     * @return boolean
     */
    public function isHidden(): bool
    {
        return strtolower($this->hide) == strtolower('yes');
    }

    /**
     * Check whether app is hidden
     *
     * @return boolean
     */
    public function isNotHidden(): bool
    {
        return strtolower($this->hide) == strtolower('no');
    }

    /**
     * Check whether app is removable
     *
     * @return boolean
     */
    public function removable(): bool
    {
        return strtolower($this->alias) != strtolower(static::DEFAULT);
    }

    /**
     * Get status badge
     *
     * @return string
     */
    public function statusBadge(): string
    {
        return $this->enabled() ?
            "<span class='badge badge-success'>{$this->status}</span>" :
            "<span class='badge badge-danger'>{$this->status}</span>";
    }

    /**
     * Check whether app is enabled
     *
     * @return boolean
     */
    public function enabled(): bool
    {
        return strtolower($this->status) == strtolower('enabled');
    }

}

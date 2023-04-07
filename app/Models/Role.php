<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /**
     * Default admin
     *
     */
    const ADMIN = 'Superuser';

    /**
     * Create default Admin
     *
     * @return Model|Builder
     */
    public static function createAdmin(): Builder|Model
    {
        return static::create([
            'name' => static::ADMIN,
            'guard_name' => 'web'
        ]);
    }

    /**
     * Get all users except admin
     *
     * @return  Collection
     */
    public static function getWithoutAdmin(): Collection
    {
        return static::withoutAdmin()->get();
    }

    /**
     * Get all users except admin
     *
     * @return Builder
     */
    public static function withoutAdmin(): Builder
    {
        return static::where('name', '<>', static::ADMIN);
    }

    /**
     * Check Whether Role is Administrator
     * @return boolean
     */
    public function isAdmin(): bool
    {
        return strtolower($this->name) == strtolower(static::ADMIN);
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
            fn($query) => $query->where('name', '<>', static::ADMIN)
                ->where('name', 'like', "%{$term}%")
        );
    }

    /**
     * Check whether Role has an App allocated
     * @param $app
     * @return bool
     */
    public function hasApp($app): bool
    {
        if ($app instanceof App) {
            $app = $app->getKey();
        }
        $apps = $this->apps();
        foreach ($apps as $obj) {
            if ($obj->getKey() == $app) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get Apps allocated to Role
     *
     * @return array
     */
    public function apps(): array
    {
        $modules = $this->permissions()->groupBy('app_id')->pluck('app_id')->toArray();
        $apps = array();
        foreach ($modules as $module) {
            $app = App::find($module);
            if (!empty($app)) {
                $apps[] = $app;
            }
        }
        return $apps;
    }

    /**
     * Check whether User has Apps allocated
     * @return boolean
     */
    public function hasApps(): bool
    {
        $apps = $this->apps();
        return count($apps) > 0;
    }


}

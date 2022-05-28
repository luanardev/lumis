<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class System extends Model
{
    use HasFactory;

    const DEFAULT = 'controlpanel';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'systems';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name','alias','display_name','url','icon','status'];

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class, 'system_id');
    }

    /**
     * Check Whether system has permissions
     *
     * @return boolean
     */
    public function hasPermissions()
    {
        return empty($this->permissions)?false:true;
    }

    /**
     * Get roles assigned this app;
     *
     * @param mixed $app
     * @return array
     */
    public function assignedRoles()
    {
        $roles = Role::all();
        $assigned = [];
        foreach($roles as $role){
            if($role->hasApp($this)){
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
    public function unassignedRoles()
    {
        $roles = Role::all();
        $unassigned = [];
        foreach($roles as $role){
            if(!$role->hasApp($this)){
                $unassigned[] = $role;
            }
        }
        return $unassigned;
    }

    /**
     * Search Scope for Laravel Livewire DataTable
     * @var Illuminate\Database\Eloquent\Builder $query
     * @var string $term
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(
            fn ($query) => $query->where('name', 'like', "%{$term}%")
        );
    }

    /**
     * Check whether module is default
     *
     * @return boolean
     */
    public function isDefault()
    {
       return (strtolower($this->alias) == strtolower(static::DEFAULT))? true:false;
    }

    /**
     * Enable module
     *
     * @return void
     */
    public function enable()
    {
        $this->setAttribute('status', 'enabled');
        $this->save();
    }

    /**
     * Disable module
     *
     * @return void
     */
    public function disable()
    {
        $this->setAttribute('status', 'disabled');
        $this->save();
    }

    /**
     * Check whether app is enabled
     *
     * @return boolean
     */
    public function enabled()
    {
        return ( strtolower($this->status ) == strtolower('enabled'))? true:false;
    }

    /**
     * Check whether app is disabled
     *
     * @return boolean
     */
    public function disabled()
    {
        return ( strtolower($this->status ) == strtolower('disabled'))? true:false;
    }

    /**
     * Check whether app is removable
     *
     * @return boolean
     */
    public function removable()
    {
        return ( strtolower($this->alias ) != strtolower(static::DEFAULT))? true:false;
    }

    /**
     * Get status badge
     *
     * @return string
     */
    public function statusBadge()
    {
        return $this->enabled()?
            "<span class='badge badge-success'>{$this->status}</span>":
            "<span class='badge badge-danger'>{$this->status}</span>";
    }

    /**
     * Search for app
     * @var string $term
     * @return null|Illuminate\Database\Eloquent\Builder
     */
    public static function search($term)
    {
       return static::withoutDefault()->where('name','like', "%{$term}%")
                    ->orWhere('display_name', 'like', "%{$term}%")
                    ->get();
    }

    /**
     * Find record by name
     * @param mixed $name
     * @return self
     */
    public static function findByName($name)
    {
        return static::where('name', $name)->orWhere('alias', $name)->first();
    }

    /**
     * Check whether App is installed
     *
     * @param string $appName
     * @return boolean
     */
    public static function installed($appName)
    {
        $app = static::findByName($appName);
        return !empty($app) ? true: false;
    }

    /**
     * Get all Apps except Default
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public static function withoutDefault()
    {
        return static::where('alias', '<>', static::DEFAULT);
    }

    /**
     * Get all users except admin
     *
     * @return  Illuminate\Database\Eloquent\Collection 
     */
    public static function getWithoutDefault()
    {
        return static::withoutDefault()->get();
    }


}

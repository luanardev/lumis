<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /**
     * Default admin
     *
     */
    const ADMIN = 'admin';

    /**
     * Search Scope for Laravel Livewire DataTable
     * @var Illuminate\Database\Eloquent\Builder $query
     * @var string $term
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(
            fn ($query) => $query->where('alias', '<>', self::DEAFULT)
                                ->where('name', 'like', "%{$term}%")
        );
    }
    
    /**
     * Get Apps allocated to Role
     *
     * @return array
     */
    public function apps()
    {
        $modules = $this->permissions()->groupBy('system_id')->pluck('system_id')->toArray();
        $apps = array();
        foreach($modules as $module){
            $app = System::find($module);
            if(!empty($app)){
                $apps[] = $app;
            }
        }
        return $apps;     
    }

    /**
     * Check whether Role has an App allocated
     * @return boolean
     */
    public function hasApp($app)
    {
        if($app instanceof System){
            $app = $app->getKey();
        }
        $apps = $this->apps();
        foreach($apps as $obj){
            if($obj->getKey() == $app){
                return true;
            }
        }
        return false;
    }

    /**
     * Check whether User has Apps allocated
     * @return boolean
     */
    public function hasApps()
    {
        $apps = $this->apps();
        return ( count($apps) > 0) ? true:false;
    }

    /**
     * Create default Admin
     *
     * @return static
     */
    public static function createAdmin()
    {
        return static::create([
            'name' => 'admin', 
            'guard_name' => 'web'
        ]);
    }

    /**
     * Get all users except admin
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public static function withoutAdmin()
    {
        return static::where('name', '<>', static::ADMIN);
    }

    /**
     * Get all users except admin
     *
     * @return  Illuminate\Database\Eloquent\Collection 
     */
    public static function getWithoutAdmin()
    {
        return static::withoutAdmin()->get();
    }
    

}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Luanardev\Modules\Employees\Concerns\WithEmployee;
use Luanardev\Modules\Institution\Concerns\HasCampus;
use Luanardev\Modules\Institution\Concerns\CampusPicker;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        HasCampus,
        WithEmployee,
        CampusPicker,
        Notifiable,
        Loggable,
        HasRoles;

    /**
     * Default admin
     *
     */
    const ADMIN = 'admin';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name','email','password','campus','status'];

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
     * Always encrypt the password when it is updated.
     *
     * @param $value
     * @return string
     */
    public function setPassword($value)
    {
        $value = bcrypt($value);
        $this->setAttribute('password',  $value);
    }

    /**
     * Deactivate user account
     *
     * @return void
     */
    public function deactivate()
    {
        $this->setAttribute('status', 'Inactive');
        $this->save();
    }

    /**
     * Activate user account
     *
     * @return void
     */
    public function activate()
    {
        $this->setAttribute('status', 'Active');
        $this->save();
    }

    /**
     * Check Whether User is Administrator
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->hasRole(static::ADMIN) ? true : false;
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
                ->orWhere('email', 'like', "%{$term}%")
        );
    }

    /**
     * Get Apps allocated to User
     *
     * @return array
     */
    public function getApps()
    {
        if($this->hasRole(static::ADMIN)){
            return System::all();
        }
        else{
            return $this->assigned();   
        }       
    }

    /**
     * Check whether User has Apps allocated
     * @return boolean
     */
    public function hasApps()
    {
        $apps = $this->getApps();
        return ( count($apps) > 0) ? true:false;
    }

    /**
     * Check whether User has an App allocated
     *
     * @param string $name Application name
     * @return boolean
     */
    public function hasApp($name)
    {
        if($this->isAdmin()){
            return true;
        }

        $apps = $this->assigned();
        
        foreach($apps as $app){
            if( strtolower($app->name) == strtolower($name) ){
                return true;                  
            }
        }  
        return false;    
    }

    /**
     * Check whether User email exists
     *
     * @return boolean
     */
    public function emailTaken()
    {
        $exists = User::where('email', $this->email)->exists();
        return ($exists)?true:false;
    }

    /**
     * Check whether User is activated
     *
     * @return boolean
     */
    public function activated()
    {
        return ( strtolower($this->status ) == strtolower('active'))? true:false;
    }

    /**
     * Check whether User is deactivated
     *
     * @return boolean
     */
    public function deactivated()
    {
        return ( strtolower($this->status ) == strtolower('inactive'))? true:false;
    }

    /**
     * Get User Assigned Roles
     *
     * @return string
     */
    public function getRoles()
    {
        $roles = $this->roles()->pluck('name')->toArray();
        return implode(',', $roles);
    }

    /**
     * Get status badge
     *
     * @return string
     */
    public function statusBadge()
    {
        return $this->activated()?
            "<span class='badge badge-success'>{$this->status}</span>":
            "<span class='badge badge-danger'>{$this->status}</span>";
    }

    /**
     * Date of creation
     *
     * @return string
     */
    public function createdDate()
    {
        return (isset($this->created_at))? $this->created_at->format('d-M-Y'):null;
    }

    /**
     * Get assigned apps
     *
     * @return array
     */
    private function assigned()
    {
        $modules = $this->getAllPermissions()->groupBy('system_id')->keys()->toArray();
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
     * Search Scope for Laravel Livewire DataTable
     * @var string $term
     * @return Illuminate\Database\Eloquent\Builder
     */
    public static function search($term)
    {
        return static::where('name', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%");
    }

    /**
     * Create default Admin
     *
     * @return static
     */
    public static function createAdmin()
    {
        return static::firstOrNew([
            'id' => 10000,
            'name'=>'Admin', 
            'email'=>'admin@lumis.com', 
            'email_verified_at'=> now(),    
            'password'=> bcrypt('password'),
            'campus' =>  NULL,
            'status' => 'Active'
        ]);
    }

    /**
     * Get all users except admin
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public static function withoutAdmin()
    {
        return static::getByCampus()
            ->where('name', '<>', static::ADMIN);
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

     /**
     * Get Employees By Authenticated User Campus
     * @return Illuminate\Database\Eloquent\Builder
     */
    public static function getByCampus()
    {      
        $campus = static::getUserCampus();
        if(empty($campus)){
            return static::query();        
        }else{
            return static::findByCampus($campus->code);
        }        
    }

    /**
     * Get Employees By Campus Code
     * @return Illuminate\Database\Eloquent\Builder
     */
    public static function findByCampus($code)
    {
        return static::where('campus', $code);
    }
    
}

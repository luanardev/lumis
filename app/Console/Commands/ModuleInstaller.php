<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;
use Nwidart\Modules\Json;
use App\Exceptions\ModuleSetupException;
use App\Models\Permission;
use App\Models\System;
use Artisan;
use Module;

class ModuleInstaller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:install {module=null}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Module';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $moduleName = $this->argument('module');
        if($moduleName == 'null'){
            $this->installModules();
        }else{
            $this->installModule($moduleName);
        }
    }

    /**
     * Install all modules
     *
     * @return void
     */
    private function installModules()
    {
        $modules = Module::all();
        $moduleCount = 0;
        foreach($modules as $module){
            $moduleName = $module->getLowerName();
            $this->installModule($moduleName);
            $moduleCount++;          
        }

        $this->info($moduleCount. ' modules were installed');
        
    }

    /**
     * Install single module
     *
     * @param string $moduleName
     * @return void
     */
    private function installModule($moduleName)
    {
        if(!System::installed($moduleName)){ 

            $this->register($moduleName);

            $this->migrate($moduleName);

            $this->info($moduleName. ' installed successfully');  
        }
    }

   
    /**
     * Run migrations for the Module
     *
     * @param string $moduleName
     * @return void
     */
    private function migrate($moduleName)
    {
        // reset migrations
        Artisan::call("module:migrate-reset {$moduleName}");

        // install migrations
        Artisan::call("module:migrate {$moduleName}");

        // seed default data
        Artisan::call("module:seed {$moduleName}");  

    }

    /**
     * Register the Module into the database
     *
     * @param string $moduleName
     * @return void
     */
    private function register($moduleName)
    {    
    
        // find module in the filesystem
        $module = Module::find($moduleName);
        if(empty($module)){
            return false;
        }

        // module configurations
        $config = $this->getSetup($module);

        if(empty($config)){
            return false;
        }

        // module display name
        $displayName = $config['name'];

        // module permissions
        $permissions = $config['permissions'];

        // create system in the database
        $system = System::firstOrCreate([
            'name'  => $module->getName(),
            'alias'  => $module->getLowerName(),
            'display_name'  => $displayName,
            'url'   => '/'.$module->getLowerName()
        ]);
    
        // check whether permissions exist
        if(!empty($permissions) && count($permissions) > 0 ){

            // reset permission cache
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            // create and associate permissions with the module
            foreach($permissions as $permission){
                $permission = Str::slug($permission, '_');            
                $label = Str::headline($permission);
                Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => 'web',
                    'display_name' => $label,
                    'system_id' => $system->id
                ]);
            }
        }           
    }

    /**
     * Get Module Setup Configurations
     *
     * @param Module $module
     * @return array
     */
    private function getSetup($module)
    {
        $modulePath = $module->getPath();
        $setupFile = $modulePath.DIRECTORY_SEPARATOR.'setup.json';
        if(file_exists($setupFile)){
            $json = new Json($setupFile);
            return $json->getAttributes();
        }
        throw new ModuleSetupException($module->getName());    
    }
}

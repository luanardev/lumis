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

class ModuleUninstaller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:uninstall {module=null}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Un Install Module';

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
            $this->uninstallModules();
        }else{
            $this->uninstallModule($moduleName);
        }
    }

    /**
     * Install all modules
     *
     * @return void
     */
    private function uninstallModules()
    {
        $modules = System::all();
        $moduleCount = 0;
        foreach($modules as $module){
            $moduleName = $module->alias;
            $this->uninstallModule($moduleName);
            $moduleCount++;          
        }

        $this->info($moduleCount. ' modules were removed');
        
    }

    /**
     * Install single module
     *
     * @param string $moduleName
     * @return void
     */
    private function uninstallModule($moduleName)
    {
        if(System::installed($moduleName)){ 

            $module = System::findByName($moduleName);
            $module->delete();

            Artisan::call("module:migrate-reset {$moduleName}");

            $this->info($moduleName. ' removed successfully');  
        }
    }

}

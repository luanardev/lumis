<?php

namespace App\Console\Commands;

use App\Exceptions\ModuleSetupException;
use App\Models\App;
use App\Models\Permission;
use Artisan;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Module;
use Nwidart\Modules\Json;
use Spatie\Permission\PermissionRegistrar;
use Throwable;

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
    protected $description = 'Install App';

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
        if ($moduleName == 'null') {
            $this->installModules();
        } else {
            $this->installModule($moduleName);
        }
    }

    /**
     * Install all apps
     *
     * @return void
     */
    private function installModules()
    {
        $modules = Module::getOrdered();
        $moduleCount = 0;
        foreach ($modules as $module) {
            $moduleName = $module->getLowerName();
            $this->installModule($moduleName);
            $moduleCount++;
        }

        $this->info($moduleCount . ' apps were installed');

    }

    /**
     * Install single module
     *
     * @param string $moduleName
     * @return void
     */
    private function installModule($moduleName)
    {
        try {
            $this->register($moduleName);

            $this->migrate($moduleName);

            $this->info($moduleName . ' installed successfully');
        } catch (Exception $ex) {
            $this->error($ex->getMessage());
        }

    }

    /**
     * Register the App into the database
     *
     * @param string $moduleName
     * @return void
     * @throws Throwable
     */
    private function register($moduleName)
    {
        // find module in the filesystem
        $module = Module::find($moduleName);
        if (empty($module)) {
            return false;
        }

        // module configurations
        $config = $this->getSetup($module);

        if (empty($config)) {
            return false;
        }

        // create module in the database

        $app = new App();
        $app->name = $module->getName();
        $app->alias = $module->getLowerName();
        $app->priority = $module->getPriority();
        $app->url = array_key_exists('url',$config)? '/'.$config['url'] : '/'.$module->getLowerName();
        $app->display_name = $config['name'];
        $app->group = strtolower($config['group']) ?? 'system';
        $app->icon = $config['icon']?? null;
        $app->color = $config['color']?? null;
        $app->background = $config['background'] ?? null;
        $app->saveOrFail();
		
		// module permissions
		$permissions = $config['permissions'] ?? [];

        // check whether permissions exist
        if (!empty($permissions) && count($permissions) > 0) {

			$permissions = array_merge(["{$module->getLowerName()}-home"], $permissions);

            // reset permission cache
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            // create and associate permissions with the module
            foreach ($permissions as $permission) {
                $name = Str::slug($permission, '-');
                $displayName = Str::headline($permission);

                $permissionObject = new Permission();
                $permissionObject->name = $name;
                $permissionObject->display_name = $displayName;
                $permissionObject->guard_name = 'web';
                $permissionObject->app()->associate($app);
                $permissionObject->saveOrFail();
            }
        }
    }

    /**
     * Get App Setup Configurations
     *
     * @param App $module
     * @return array
     * @throws Exception
     */
    private function getSetup($module): array
    {
        $modulePath = $module->getPath();
        $setupFile = $modulePath . DIRECTORY_SEPARATOR . 'setup.json';
        if (file_exists($setupFile)) {
            $json = new Json($setupFile);
            return $json->getAttributes();
        }
        throw new ModuleSetupException($module->getName());
    }

    /**
     * Run migrations for the App
     *
     * @param string $moduleName
     * @return void
     */
    private function migrate($moduleName)
    {
        // reset migrations
        Artisan::call("module:migrate-rollback {$moduleName}");

        // install migrations
        Artisan::call("module:migrate {$moduleName}");

        // seed default data
        Artisan::call("module:seed {$moduleName}");

    }
}

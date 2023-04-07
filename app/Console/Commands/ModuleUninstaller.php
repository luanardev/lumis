<?php

namespace App\Console\Commands;

use App\Models\App;
use Artisan;
use Illuminate\Console\Command;
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
    protected $description = 'Un Install App';

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
            $this->uninstallModules();
        } else {
            $this->uninstallModule($moduleName);
        }
    }

    /**
     * Install all apps
     *
     * @return void
     */
    private function uninstallModules()
    {
        $modules = App::all();
        $moduleCount = 0;
        foreach ($modules as $module) {
            $moduleName = $module->alias;
            $this->uninstallModule($moduleName);
            $moduleCount++;
        }

        $this->info($moduleCount . ' apps were removed');

    }

    /**
     * Install single module
     *
     * @param string $moduleName
     * @return void
     */
    private function uninstallModule($moduleName)
    {
        if (App::installed($moduleName)) {

            $module = App::findByName($moduleName);
            $module->delete();

            Artisan::call("module:migrate-reset {$moduleName}");

            $this->info($moduleName . ' removed successfully');
        }
    }

}

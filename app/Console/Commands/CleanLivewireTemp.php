<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanLivewireTemp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'livewire-temp:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean Temporary Storage Directory for File Uploads';

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
     * @return void
     */
    public function handle()
    {
        $directory = 'livewire-tmp';

        Storage::deleteDirectory($directory);

        Storage::makeDirectory($directory, 0777);

        $this->info('Livewire temporary storage cleaned successfully');
    }
}

<?php

namespace Railken\Laravel\Manager\Commands;

use Illuminate\Console\Command;
use Railken\Laravel\Manager\Generator;

class Generate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'railken:make:manager {path} {namespace}'; // e.g. "php artisan railken:make:manager src Core\User"

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all basics files';

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
     * @return mixed
     */
    public function handle()
    {
        $generator = new Generator();
        $generator->generate(base_path($this->argument('path')), $this->argument('namespace'));
        $this->info("{$this->argument('namespace')} generated.");
    }
}

<?php

namespace Railken\Lem\Console\Commands;

use Illuminate\Console\Command;
use Railken\Lem\Generator;

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
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getAbsolutePathByParameter($path)
    {
        return getcwd().'/'.$path;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!is_string($this->argument('namespace'))) {
            throw new \Exception('Wut?');
        }

        $generator = new Generator();
        $generator->generate(
            $path = $this->getAbsolutePathByParameter($this->argument('path')),
            $this->argument('namespace')
        );
        $this->info("Manager {$this->argument('namespace')} in {$path} generated.");
    }
}

<?php

namespace Railken\Lem\Console\Commands;

use Illuminate\Console\Command;
use Railken\Lem\Generator;

class GenerateCommand extends Command
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
            $path = getcwd().'/'.strval($this->argument('path')),
            $this->argument('namespace')
        );
        $this->info("Manager {$this->argument('namespace')} in {$path} generated.");
    }
}

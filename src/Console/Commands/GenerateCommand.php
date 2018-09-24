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
    protected $signature = 'make:manager {path} {namespace} {name}';

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
        $generator = new Generator();
        $generator->generate(
            $path = getcwd().'/'.strval($this->argument('path')),
            strval($this->argument('namespace')),
            strval($this->argument('name'))
        );

        $this->info('Generated');
    }
}

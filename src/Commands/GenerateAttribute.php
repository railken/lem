<?php

namespace Railken\Laravel\Manager\Commands;

use Illuminate\Console\Command;
use Railken\Laravel\Manager\Generator;

class GenerateAttribute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'railken:make:manager-attribute {path} {namespace} {attribute}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate attribute for model';

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
        $generator->generateAttribute(base_path($this->argument('path')), $this->argument('namespace'), $this->argument('attribute'));
        $this->info("{$this->argument('namespace')} generated. Remember to add the attribute to the migration. $fillable already populated.");
        $this->info('Run php-cs-fixer to fix missing lines');
    }
}

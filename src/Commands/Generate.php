<?php

namespace Railken\Laravel\Manager\Commands;

use Illuminate\Console\Command;

use Railken\Laravel\Manager\Generator;

class Generate extends Command{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'railken:make:manager {base_path} {path} {name}';

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

        $name = ucfirst($this->argument('name'));
        
        $path = $this->argument('path');
        $namespace = str_replace("/", "\\", $path);

        $namespace .= "\\".$name;

        $path = base_path($this->argument('base_path')."/".$path."/");


        try {

            $gn = new Generator($path);

        } catch(\Exception $e) {

            $this->error($e->getMessage());
            return;
        }

        $v = $this->laravel->make('src.version')."/";

        $vars = [
            'NAMESPACE' => $namespace,
            'NAME' => $name,
            'LOW:NAME' => strtolower($name),
        ];

        $gn->put("$v/Model.php", "{$name}/{$name}.php", $vars);
        $gn->put("$v/ModelManager.php", "{$name}/{$name}Manager.php", $vars);
        $gn->put("$v/ModelRepository.php", "{$name}/{$name}Repository.php", $vars);


        $this->info("\n".$name." generated");
    }
}

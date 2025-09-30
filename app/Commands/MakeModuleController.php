<?php 

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class MakeModuleController extends BaseCommand
{
    protected $group       = 'Generators';
    protected $name        = 'make:modulecontroller';
    protected $description = 'Make a new Controller inside Modules folder';

    public function run(array $params)
    {
        $module = $params[0] ?? CLI::prompt('Module name');
        $name   = $params[1] ?? CLI::prompt('Controller name');

        $path = APPPATH . "Modules/{$module}/Controllers/{$name}.php";

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $template = "<?php\n\nnamespace App\\Modules\\{$module}\\Controllers;\n\nuse App\Controllers\BaseController;\n\nclass {$name} extends \App\Controllers\BaseController\n{\n    public function index()\n    {\n        return view('Modules/{$module}/" . strtolower($name) . "View');\n    }\n}\n";

        file_put_contents($path, $template);

        CLI::write("Controller created at: {$path}", 'green');
    }
}

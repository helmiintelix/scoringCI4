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
        
        $module = ucwords($module);
        $name = ucwords($name);

        $path = APPPATH . "Modules/{$module}/Controllers/{$name}.php";
        echo 'creating Controller..... '.$path;
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
            
$template = 
"<?php
namespace App\\Modules\\{$module}\\Controllers;
use App\\Controllers\\BaseController;
use App\\Modules\\{$module}\\Models\\{$name}Model;

class {$name} extends \\App\\Controllers\\BaseController
{
    function __construct()
    {
        \$this->{$name}Model = new {$name}Model();
    }
    public function index()
    {
        return view('App\\Modules\\{$module}\\" . strtolower($name) . "View');
    }
}
?>";
                file_put_contents($path, $template);
                CLI::write("Controller created at: {$path}", 'green');
        }else{
            echo "Controller already exist";
            return false;
        }

        $path = APPPATH . "Modules/{$module}/Models/{$name}Model.php";
        echo 'creating Model..... '.$path;
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
            $template = "<?php\n\nnamespace App\\Modules\\{$module}\\Models;\n\nuse CodeIgniter\Model;\n\nclass {$name}Model extends Model\n{\n  \n}\n";
            file_put_contents($path, $template);
            CLI::write("Model created at: {$path}", 'green');

        }else{
            echo "Model already exist";
            return false;
        }

        $path = APPPATH . "Modules/{$module}/Views/{$name}View.php";
        echo 'creating View..... '.$path;
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
            $template = 
'<div class="card">
    <div class="card-header">
        '.$name.'
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGrid" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
';
            file_put_contents($path, $template);
            CLI::write("View created at: {$path}", 'green');

        }else{
            echo "View already exist";
            return false;
        }
    }
}

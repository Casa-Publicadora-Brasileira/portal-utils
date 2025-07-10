<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeQueryDomain extends Command
{
    protected $signature = 'make:query-domain {name} {--m|model= : Nome da model (ex: User ou App/Models/User)}';
    protected $description = 'Cria uma classe QueryDomain em app/Domains';

    public function handle()
    {
        $input = trim($this->argument('name'), '/');
        $model = $this->option('model');
        $classPath = str_replace('/', '\\', $input);
        $className = class_basename($classPath);
        $relativePath = str_replace('\\', '/', $classPath) . 'Domain.php';
        $path = app_path("Domains/{$relativePath}");
        $segments = explode('/', $input);
        array_pop($segments);
        $namespaceSuffix = implode('\\', $segments);

        $namespace = 'App\\Domains' . ($namespaceSuffix ? "\\{$namespaceSuffix}" : '');

        if (File::exists($path)) {
            $this->error("A classe {$className}QueryDomain já existe.");

            return 1;
        }

        File::ensureDirectoryExists(dirname($path));

        $modelLine = '';
        $modelImportLine = '';

        if ($model) {
            $modelClass = str_replace('/', '\\', trim($model, '/'));
            $modelClassName = class_basename($modelClass);
            $modelImportLine = "use App\Models\\{$modelClass};";
            $modelLine = "    protected static string \$model = {$modelClassName}::class;";
        }

        $stub = <<<PHP
<?php

namespace {$namespace};

{$modelImportLine}
use CasaPublicadoraBrasileira\PortalUtils\Domains\QueryDomain;

class {$className}QueryDomain extends QueryDomain
{
{$modelLine}
}

PHP;

        File::put($path, $stub);

        $this->info("Classe {$className}QueryDomain criada com sucesso em {$path}.");

        return 0;
    }
}

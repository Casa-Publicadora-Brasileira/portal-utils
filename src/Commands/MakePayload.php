<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakePayload extends Command
{
    protected $signature = 'make:payload {name}';
    protected $description = 'Cria uma classe Payload em app/Payloads';

    public function handle()
    {
        $input = trim($this->argument('name'), '/');
        $classPath = str_replace('/', '\\', $input);
        $className = class_basename($classPath);
        $relativePath = str_replace('\\', '/', $classPath) . 'Payload.php';
        $path = app_path("Payloads/{$relativePath}");
        $segments = explode('/', $input);
        array_pop($segments);
        $namespaceSuffix = implode('\\', $segments);

        $namespace = 'App\\Payloads' . ($namespaceSuffix ? "\\{$namespaceSuffix}" : '');

        if (File::exists($path)) {
            $this->error("A classe {$className}Payload já existe.");

            return 1;
        }

        File::ensureDirectoryExists(dirname($path));

        $stub = <<<PHP
<?php

namespace {$namespace};

use CasaPublicadoraBrasileira\PortalUtils\Payloads\Payload;

class {$className}Payload extends Payload
{
    public function rules(): array
    {
        return [];
    }
}

PHP;

        File::put($path, $stub);

        $this->info("Classe {$className}Payload criada com sucesso.");

        return 0;
    }
}

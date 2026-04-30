<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeArchitectureCommand extends Command
{
    // Penggunaan: php artisan make:arch NamaModel
    protected $signature = 'make:arch {name}';
    protected $description = 'Generate DTO, Service, and Repository for a model';

    public function handle()
    {
        $name = $this->argument('name');

        $this->generateFile($name, 'DTO', 'app/DTO');
        $this->generateFile($name, 'Service', 'app/Services');
        $this->generateFile($name, 'Repository', 'app/Repositories');

        $this->info("🚀 Arsitektur untuk {$name} berhasil dibuat!");
    }

    protected function generateFile($name, $suffix, $path)
    {
        $className = "{$name}{$suffix}";
        $directory = base_path($path);

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filePath = "{$directory}/{$className}.php";

        if (File::exists($filePath)) {
            $this->error("❌ {$className} sudah ada!");
            return;
        }

        // Logika pengisian konten bisa menggunakan stub sederhana
        $content = $this->getStubContent($name, $suffix);
        File::put($filePath, $content);
        $this->line("✅ Created: {$path}/{$className}.php");
    }

    protected function getStubContent($name, $suffix)
    {
        if ($suffix === 'Repository') {
            return "<?php\n\nnamespace App\Repositories;\n\nuse App\Models\\{$name};\n\nclass {$name}Repository extends BaseRepository\n{\n    public function __construct({$name} \$model)\n    {\n        parent::__construct(\$model);\n    }\n}\n";
        }

        if ($suffix === 'Service') {
            return "<?php\n\nnamespace App\Services;\n\nuse App\Repositories\\{$name}Repository;\n\nclass {$name}Service extends BaseService\n{\n    public function __construct({$name}Repository \$repository)\n    {\n        parent::__construct(\$repository);\n    }\n}\n";
        }
        
        $namespace = "App\\" . Str::plural($suffix);
        if ($suffix === 'DTO')
            $namespace = "App\\DTO";

        return "<?php\n\nnamespace {$namespace};\n\nclass {$name}{$suffix}\n{\n    // Logic untuk {$name}{$suffix}\n}\n";
    }
}
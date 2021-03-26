<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Console\Commands\ImportAll;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private const IMPORT = 'Import';
    private const SEEDER = 'Seeder';

    private ImportAll $importer;

    public function __construct(ImportAll $importer)
    {
        $this->importer = $importer;
    }

    public function run(): void
    {
        $this->call(PassportSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(StructureSeeder::class);
        $this->call(PermissionSeeder::class);

        $choice = $this->command->choice(
            'Use Seeder or Import for: Tags, Themes, People, Products, Projects, Parties',
            [self::SEEDER, self::IMPORT],
            0
        );

        if ($choice === self::SEEDER) {
            $this->call(TagSeeder::class);
            $this->call(PartySeeder::class);
            $this->call(PersonSeeder::class);
            $this->call(ProductSeeder::class);
            $this->call(ProductContentSeeder::class);
            $this->call(ProjectSeeder::class);
        } else {
            $startTime = microtime(true);

            $this->command->line('<fg=yellow>Importing: <fg=default>App\Console\Commands\ImportAll');

            $this->importer->setOutput($this->command->getOutput());

            $this->importer->handle();

            $runTime = number_format((microtime(true) - $startTime) * 1000, 2);

            $this->command->line('<fg=green>Imported: <fg=default>App\Console\Commands\ImportAll (' . $runTime . 'ms)');
        }
    }
}

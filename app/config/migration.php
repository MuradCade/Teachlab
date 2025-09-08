<?php

require __DIR__ . '/../../vendor/autoload.php';
$config = require __DIR__ . '/../../env.php';

use App\Config\Eloquent;
use Illuminate\Database\Capsule\Manager as Capsule;

Eloquent::setup($config);

// Optional: Drop one  table or all tables via CLI
if (PHP_SAPI === 'cli' && isset($argv[1])) {

    // Show help if no args or --help passed
    if (!isset($argv[1]) || $argv[1] === '--help') {
        echo <<<EOD
            Migration CLI commands:

            --help
                Show this help message.

            --drop table_name
                Drop a single table by name and remove its migration record.

            --drop-all
                Drop all tables except the migrations table and truncate migrations table.

            --make MigrationName
                Create a new migration file with the specified MigrationName.

            --drop-file filename_suffix
                Delete migration files ending with the specified filename suffix (extension optional)
                and remove their migration records.

            Examples:

            php migration.php --drop users
            php migration.php --drop-all
            php migration.php --make create_users_table
            php migration.php --drop-file users.php
            php migration.php --drop-file users

            EOD;
        exit(0);
    }

    // drop single table
    if ($argv[1] === '--drop') {
        if (count($argv) < 3) {
            echo "❌ Usage: php migration.php --drop table_name\n";
            exit(1);
        }

        $table = $argv[2];  // Only the first specified table

        try {
            Capsule::statement('SET FOREIGN_KEY_CHECKS=0');

            if (Capsule::schema()->hasTable($table)) {
                Capsule::schema()->drop($table);
                echo "🗑️ Dropped table: {$table}\n";
            } else {
                echo "⚠️ Table does not exist: {$table}\n";
            }

            // Delete migration record exactly matching this table's migration filename pattern
            // Assuming migrations are named like: "NNN_create_table_table.php"
            $deletedRows = Capsule::table('migrations')
                ->where('migration', 'like', "%create_table_{$table}.php")
                ->delete();

            if ($deletedRows > 0) {
                echo "✅ Deleted {$deletedRows} migration record(s) for {$table}\n";
            } else {
                echo "⚠️ No migration record found for {$table}\n";
            }
        } catch (Exception $e) {
            echo "❌ Failed to drop table '{$table}': {$e->getMessage()}\n";
        } finally {
            Capsule::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        exit;
    }

    // drop all tables
    if ($argv[1] === '--drop-all') {
        try {
            Capsule::statement('SET FOREIGN_KEY_CHECKS=0');

            // Truncate the migrations table explicitly to empty it
            if (Capsule::schema()->hasTable('migrations')) {
                Capsule::table('migrations')->truncate();
                echo "✅ Emptied 'migrations' table\n";
            }

            // Get all tables except 'migrations'
            $tables = Capsule::select('SHOW TABLES');
            $dbName = Capsule::getDatabaseName();
            $tableKey = "Tables_in_{$dbName}";

            foreach ($tables as $row) {
                $table = $row->$tableKey;

                if ($table === 'migrations') {
                    continue; // skip migrations table since already truncated
                }

                if (Capsule::schema()->hasTable($table)) {
                    // Empty the table (truncate)
                    Capsule::table($table)->truncate();

                    // Drop the table
                    Capsule::schema()->drop($table);

                    echo "🗑️ Dropped table: {$table}\n";
                }
            }
        } catch (Exception $e) {
            echo "❌ Failed to drop all tables: {$e->getMessage()}\n";
        } finally {
            Capsule::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        exit;
    }
    // create new migration file
    if ($argv[1] === '--make') {
        if (count($argv) < 3) {
            echo "❌ Usage: php migration.php --make MigrationName\n";
            exit(1);
        }

        $migrationName = preg_replace('/[^A-Za-z0-9_]/', '', $argv[2]); // sanitize name
        $migrationDir = __DIR__ . '/../migrations';

        if (!is_dir($migrationDir)) {
            mkdir($migrationDir, 0777, true);
        }

        // Get the highest existing migration prefix
        $files = glob($migrationDir . '/*.php');
        $maxPrefix = 0;
        foreach ($files as $file) {
            $basename = basename($file);
            if (preg_match('/^(\d+)_/', $basename, $matches)) {
                $maxPrefix = max($maxPrefix, (int)$matches[1]);
            }
        }

        $newPrefix = str_pad($maxPrefix + 1, 3, '0', STR_PAD_LEFT); // 001, 002, etc.
        $filename = "{$newPrefix}_create_table_{$migrationName}.php";
        $filepath = $migrationDir . '/' . $filename;

        // Default template
        $template = <<<PHP
                <?php

                use Illuminate\\Database\\Capsule\\Manager as Capsule;

                Capsule::schema()->create('your_table_name', function (\$table) {
                    \$table->increments('id');
                    // Add your columns here
                });

                PHP;

        if (file_put_contents($filepath, $template) !== false) {
            echo "✅ Created migration: {$filename}\n";
        } else {
            echo "❌ Failed to create migration file.\n";
        }

        exit;
    }


    // delete actual migration file
    if ($argv[1] === '--drop-file') {
        if (count($argv) < 3) {
            echo "❌ Usage: php migration.php --drop-file filename_suffix\n";
            exit(1);
        }

        $fileSuffix = $argv[2];

        // Append '.php' extension if not present
        if (substr($fileSuffix, -4) !== '.php') {
            $fileSuffix .= '.php';
        }

        $migrationDir = __DIR__ . '/../migrations';

        // Find migration files that end with the specified suffix
        $matchedFiles = array_filter(glob($migrationDir . '/*.php'), function ($file) use ($fileSuffix) {
            return substr(basename($file), -strlen($fileSuffix)) === $fileSuffix;
        });

        if (empty($matchedFiles)) {
            echo "⚠️ No migration files found ending with '{$fileSuffix}'\n";
            exit;
        }

        foreach ($matchedFiles as $filePath) {
            $migrationFile = basename($filePath);

            try {
                if (file_exists($filePath)) {
                    unlink($filePath);
                    echo "🗑️ Deleted migration file: {$migrationFile}\n";
                }

                $deletedRows = Capsule::table('migrations')
                    ->where('migration', $migrationFile)
                    ->delete();

                if ($deletedRows > 0) {
                    echo "✅ Deleted migration record for: {$migrationFile}\n";
                } else {
                    echo "⚠️ No migration record found for: {$migrationFile}\n";
                }
            } catch (Exception $e) {
                echo "❌ Failed to delete migration file or record '{$migrationFile}': {$e->getMessage()}\n";
            }
        }

        exit;
    }


    echo "❌ Unknown command.\n";
    exit(1);
}



// Create the migrations table if it doesn't exist
if (!Capsule::schema()->hasTable('migrations')) {
    Capsule::schema()->create('migrations', function ($table) {
        $table->increments('id');
        $table->string('migration')->unique();
        $table->timestamp('executed_at')->default(Capsule::raw('CURRENT_TIMESTAMP'));
    });
}

// Load migration files
$files = glob(__DIR__ . '/../migrations/*.php');

// Get previously run migrations
$runMigrations = Capsule::table('migrations')->pluck('migration')->toArray();

foreach ($files as $file) {
    $migrationName = basename($file);

    if (!in_array($migrationName, $runMigrations)) {
        require $file;

        Capsule::table('migrations')->insert([
            'migration' => $migrationName,
            'executed_at' => date('Y-m-d H:i:s'),
        ]);

        echo "✅ Run migration: {$migrationName}\n";
    } else {
        echo "⏭️ Skipped (already run): {$migrationName}\n";
    }
}

<?php
// Usage:
// php manage_model.php create ModelName
// php manage_model.php delete ModelName
// php manage_model.php help

$action = strtolower($argv[1] ?? 'help');

// Show help first
if ($action === 'help') {
    echo "Model commands are the following:-\n";
    echo "====================================\n";
    echo "create ModelName  = Creates a model file in app/Models\n";
    echo "delete ModelName  = Deletes an existing model file from app/Models\n";
    echo "help              = Shows this help message\n";
    exit(0);
}

// For create/delete, model name must be provided
if ($argc < 3) {
    echo "❌ Error: You must provide a model name.\n";
    echo "Usage: php manage_model.php create|delete ModelName\n";
    exit(1);
}

$modelName = $argv[2];

// Correct Laravel models path
$directory = __DIR__ . '../../models';
$filePath = $directory . '/' . $modelName . '.php';

// Ensure Models directory exists
if (!is_dir($directory)) {
    mkdir($directory, 0755, true);
}

if ($action === 'create') {
    if (file_exists($filePath)) {
        echo "⚠️  Model $modelName already exists at $filePath.\n";
        exit(1);
    }

    $content = "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class $modelName extends Model
{
    // Add fillable properties or relationships here
}
";

    file_put_contents($filePath, $content);
    echo "✅ Model $modelName created successfully at $filePath.\n";
} elseif ($action === 'delete') {
    if (!file_exists($filePath)) {
        echo "⚠️  Model $modelName does not exist at $filePath.\n";
        exit(1);
    }

    unlink($filePath);
    echo "🗑️  Model $modelName deleted successfully from $filePath.\n";
} else {
    echo "❌ Invalid action '$action'. Use 'create', 'delete', or 'help'.\n";
    exit(1);
}

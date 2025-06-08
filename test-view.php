<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VIEW DEBUG ===\n";

echo "1. View paths from config:\n";
$viewPaths = config('view.paths');
foreach ($viewPaths as $path) {
    echo "   - $path\n";
}

echo "\n2. Resource path: " . resource_path('views') . "\n";

echo "3. Views directory exists: " . (is_dir(resource_path('views')) ? 'Yes ✅' : 'No ❌') . "\n";

echo "4. Welcome file exists: " . (file_exists(resource_path('views/welcome.blade.php')) ? 'Yes ✅' : 'No ❌') . "\n";

echo "\n5. View finder paths:\n";
try {
    $finder = app('view.finder');
    $finderPaths = $finder->getPaths();
    foreach ($finderPaths as $path) {
        echo "   - $path (exists: " . (is_dir($path) ? 'Yes' : 'No') . ")\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n6. Files in views directory:\n";
$viewsDir = resource_path('views');
if (is_dir($viewsDir)) {
    $files = scandir($viewsDir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "   - $file\n";
        }
    }
} else {
    echo "   Directory does not exist!\n";
}

echo "\n7. Test view resolution:\n";
try {
    $viewFactory = app('view');
    echo "   - View factory available: Yes ✅\n";

    $exists = $viewFactory->exists('welcome');
    echo "   - Welcome view exists: " . ($exists ? 'Yes ✅' : 'No ❌') . "\n";

    if (!$exists) {
        // Try to find where it's looking
        echo "   - Searching in paths:\n";
        foreach ($viewPaths as $path) {
            $fullPath = $path . '/welcome.blade.php';
            echo "     * $fullPath (exists: " . (file_exists($fullPath) ? 'Yes' : 'No') . ")\n";
        }
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n=== END DEBUG ===\n";

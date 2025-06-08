<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUG SWEETSTORE ===\n";

// Test 1: Basic PHP
echo "1. PHP works: OK\n";

// Test 2: Config
echo "2. App debug: " . (config('app.debug') ? 'true' : 'false') . "\n";
echo "3. App env: " . config('app.env') . "\n";

// Test 3: Database
try {
    $pdo = DB::connection()->getPdo();
    echo "4. Database: Connected ✅\n";
} catch (Exception $e) {
    echo "4. Database: Error - " . $e->getMessage() . "\n";
}

// Test 4: View exists
echo "5. Welcome view exists: " . (view()->exists('welcome') ? 'Yes ✅' : 'No ❌') . "\n";

// Test 5: Routes
try {
    $routes = Route::getRoutes();
    echo "6. Total routes: " . count($routes) . "\n";
} catch (Exception $e) {
    echo "6. Routes error: " . $e->getMessage() . "\n";
}

// Test 6: View render
try {
    $content = view('welcome')->render();
    echo "7. View render: Success ✅\n";
    echo "   Content length: " . strlen($content) . " chars\n";
} catch (Exception $e) {
    echo "7. View render error: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . "\n";
    echo "   Line: " . $e->getLine() . "\n";
}

// Test 7: Full request simulation
try {
    $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
    $request = \Illuminate\Http\Request::create('/', 'GET');
    $response = $kernel->handle($request);
    echo "8. Request simulation: Status " . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() !== 200) {
        echo "   Response content: " . substr($response->getContent(), 0, 200) . "...\n";
    }
} catch (Exception $e) {
    echo "8. Request error: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . "\n";
    echo "   Line: " . $e->getLine() . "\n";
}

echo "\n=== END DEBUG ===\n";

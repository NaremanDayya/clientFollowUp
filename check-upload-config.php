<?php
/**
 * Server Upload Diagnostics Script
 * Upload this file to your server and access it via browser
 */

echo "<h1>Server Upload Configuration Check</h1>";
echo "<style>body{font-family:sans-serif;padding:20px;} .ok{color:green;} .error{color:red;} .warning{color:orange;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #ddd;padding:8px;text-align:left;}</style>";

// 1. PHP Upload Settings
echo "<h2>1. PHP Upload Settings</h2>";
echo "<table>";
echo "<tr><th>Setting</th><th>Value</th><th>Status</th></tr>";

$uploadSettings = [
    'file_uploads' => ini_get('file_uploads'),
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'post_max_size' => ini_get('post_max_size'),
    'max_file_uploads' => ini_get('max_file_uploads'),
    'upload_tmp_dir' => ini_get('upload_tmp_dir') ?: sys_get_temp_dir(),
];

foreach ($uploadSettings as $key => $value) {
    $status = '<span class="ok">✓</span>';
    if ($key === 'file_uploads' && $value != '1') {
        $status = '<span class="error">✗ Disabled</span>';
    }
    echo "<tr><td>{$key}</td><td>{$value}</td><td>{$status}</td></tr>";
}
echo "</table>";

// 2. Directory Permissions
echo "<h2>2. Storage Directory Permissions</h2>";
echo "<table>";
echo "<tr><th>Directory</th><th>Exists</th><th>Writable</th><th>Permissions</th></tr>";

$directories = [
    'storage/app/private/livewire-tmp',
    'storage/app/public/client-logos',
    'storage/app/public/avatars',
    'storage/logs',
    'bootstrap/cache',
];

foreach ($directories as $dir) {
    $fullPath = __DIR__ . '/' . $dir;
    $exists = is_dir($fullPath);
    $writable = $exists && is_writable($fullPath);
    $perms = $exists ? substr(sprintf('%o', fileperms($fullPath)), -4) : 'N/A';
    
    $existsStatus = $exists ? '<span class="ok">✓</span>' : '<span class="error">✗</span>';
    $writableStatus = $writable ? '<span class="ok">✓</span>' : '<span class="error">✗</span>';
    
    echo "<tr><td>{$dir}</td><td>{$existsStatus}</td><td>{$writableStatus}</td><td>{$perms}</td></tr>";
}
echo "</table>";

// 3. Test File Write
echo "<h2>3. File Write Test</h2>";
$testDirs = [
    'storage/app/private/livewire-tmp',
    'storage/app/public/client-logos',
];

foreach ($testDirs as $dir) {
    $fullPath = __DIR__ . '/' . $dir;
    $testFile = $fullPath . '/test-' . time() . '.txt';
    
    if (is_dir($fullPath)) {
        $result = @file_put_contents($testFile, 'test');
        if ($result !== false) {
            @unlink($testFile);
            echo "<p class='ok'>✓ {$dir} - Write successful</p>";
        } else {
            echo "<p class='error'>✗ {$dir} - Write failed (Permission denied)</p>";
        }
    } else {
        echo "<p class='error'>✗ {$dir} - Directory does not exist</p>";
    }
}

// 4. Disk Space
echo "<h2>4. Disk Space</h2>";
$freeSpace = disk_free_space(__DIR__);
$totalSpace = disk_total_space(__DIR__);
$usedSpace = $totalSpace - $freeSpace;
$freeSpaceMB = round($freeSpace / 1024 / 1024, 2);
$totalSpaceMB = round($totalSpace / 1024 / 1024, 2);

echo "<p>Free Space: <strong>{$freeSpaceMB} MB</strong> / {$totalSpaceMB} MB</p>";
if ($freeSpaceMB < 100) {
    echo "<p class='error'>⚠ Low disk space!</p>";
}

// 5. Laravel Storage Link
echo "<h2>5. Storage Symlink</h2>";
$publicStorage = __DIR__ . '/public/storage';
if (is_link($publicStorage)) {
    $target = readlink($publicStorage);
    echo "<p class='ok'>✓ Symlink exists: {$publicStorage} → {$target}</p>";
} else if (is_dir($publicStorage)) {
    echo "<p class='warning'>⚠ Directory exists but is not a symlink</p>";
} else {
    echo "<p class='error'>✗ Storage link does not exist. Run: php artisan storage:link</p>";
}

// 6. Environment Info
echo "<h2>6. Environment Info</h2>";
echo "<table>";
echo "<tr><td>PHP Version</td><td>" . phpversion() . "</td></tr>";
echo "<tr><td>Server Software</td><td>" . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</td></tr>";
echo "<tr><td>Document Root</td><td>" . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</td></tr>";
echo "<tr><td>Script Path</td><td>" . __DIR__ . "</td></tr>";
echo "</table>";

// 7. Livewire Upload Route Test
echo "<h2>7. Livewire Upload Route</h2>";
$uploadUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}/livewire/upload-file";
echo "<p>Upload endpoint: <code>{$uploadUrl}</code></p>";
echo "<p>Test this URL in browser - should return 405 Method Not Allowed (normal for GET request)</p>";

echo "<hr><p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Fix any <span class='error'>red errors</span> above</li>";
echo "<li>Ensure all storage directories have 755 or 775 permissions</li>";
echo "<li>Run <code>php artisan storage:link</code> on server if symlink missing</li>";
echo "<li>Check server error logs at <code>storage/logs/laravel.log</code></li>";
echo "<li>Delete this file after diagnosis for security</li>";
echo "</ol>";

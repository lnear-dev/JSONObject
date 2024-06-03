<?php
const kUsage = 'Usage: php skelly.php <package_name> <package_description> <package_namespace>';
/**
 *  replaces the following placeholders (respectively) with arguments passed to the command line(all files in directory recursively):
 *   `:package_name` 
 *   `:package_description` 
 *   `:package_namespace` 
 */
function replacePlaceholders(string $name, string $description = "", string $namespace = "")
{
    if (empty($name)) {
        echo 'Package name is required';
        exit(1);
    }
    if (empty($description))
        $description = "This is the {$name} package";
    $name_parts    = explode('-', str_replace(['-', '_'], ' ', $name));
    $uc_name_parts = array_map('ucfirst', $name_parts);
    if (empty($namespace))
        $namespace = 'Lame\\' . implode('\\', $uc_name_parts);
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__));
    foreach ($files as $file) {
        if ($file->isDir())
            continue;
        file_put_contents(
            str_replace('.stub', '', $file),
            str_replace([
                ':package_name',
                ':package_description',
                ':package_namespace',
            ], [
                $name,
                $description,
                $namespace,
            ], file_get_contents($file)),
        );
    }
}

if (count($argv) < 2) {
    echo kUsage;
    exit(1);
}
replacePlaceholders(...array_slice($argv, 1));
echo 'Done';
unlink(__FILE__);

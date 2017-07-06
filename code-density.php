<?php

$dir = $argv[1];
$threshold = 25;
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS));

foreach ($files as $file) {
    if ('js' === pathinfo($file, PATHINFO_EXTENSION)) {

        $density = density($file);
        if ($density < $threshold) {
            continue;
        }
        echo $file . ' => ' . $density . '%' . PHP_EOL;
    }
}

function density($file)
{
    $handler = fopen($file, 'rb');
    if (!$handler) {
        throw new \RuntimeException('Cannot read file: ' . $file);
    }
    $symbolsCount = 0;
    $linesCount = 0;
    while (($line = fgets($handler)) !== false) {
        $line = trim($line);
        if (0 === stripos($line, 'use ')) {
            continue;
        }
        $symbolsCount += mb_strlen(str_replace(array(' ', "\t"), '', $line));
        $linesCount++;
    }
    fclose($handler);

    $longestAllowedLine = 120;
    return round(100 * min(1, $symbolsCount / ($linesCount * $longestAllowedLine)));
}
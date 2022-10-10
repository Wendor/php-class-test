<?php

$num = $argv[1] ?? 450;

if (!file_exists('generated')) {
    mkdir('generated', 0777, true);
}

$classTemplate = file_get_contents('templates/class.template.tpl');
$methodTemplate = file_get_contents('templates/method.template.tpl');

# one class
$methods = [];
$content = str_replace('%NAME%', 'Features', $classTemplate);
for($i = 0; $i < $num; $i++) {
    $methods[] = str_replace('%NAME%', 'feature' . $i, $methodTemplate);
}
$content = str_replace('%METHODS%', implode(PHP_EOL, $methods), $content);
file_put_contents('generated/Features.php', $content);


# many classes
for($i = 0; $i < $num; $i++) {
    $content = str_replace('%NAME%', 'Feature' . $i, $classTemplate);
    $method = str_replace('%NAME%', 'check', $methodTemplate);
    $content = str_replace('%METHODS%', $method, $content);
    file_put_contents('generated/Feature' . $i . '.php', $content);
}

function loadClass($classname) {
    $filename = 'generated/'. $classname .'.php';
    require_once($filename);
}
spl_autoload_register('loadClass');

# test all in one
$time_start = microtime(true);
$features = new Features();
for ($i = 0; $i < $num; $i++) {
    $method = 'feature' . $i;
    $features->$method();
}

$time_end = microtime(true);
$execution_time = $time_end - $time_start;
echo "Test one big classs: " . number_format($execution_time, 10) . PHP_EOL;


# test separated classes
$time_start = microtime(true);
for ($i = 0; $i < $num; $i++) {
    $classname = 'Feature' . $i;
    $feature = new $classname();
    $feature->check();
}

$time_end = microtime(true);
$execution_time = $time_end - $time_start;
echo "Test separate classes: " . number_format($execution_time, 10) . PHP_EOL;


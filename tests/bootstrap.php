<?php
/**
 * Error reporting
 */
error_reporting( E_ALL | E_STRICT );

/**
 * Paths
 */
$root  = realpath(dirname(dirname(__FILE__)));
$lib   = "$root/library";
$tests = "$root/tests";

$path = array(
    $lib,
    $tests,
    get_include_path()
);
set_include_path(implode(PATH_SEPARATOR, $path));

require_once 'Mockery.php';

/**
 * Autoloading
 */
function SFTestAutoloader($class)
{
    $class = ltrim($class, '\\');

    if (!preg_match('#^(SF(Test)?|PHPUnit|Zend|Mockery)(\\\\|_)#', $class)) {
        return false;
    }

    // ns'd
    $segments = explode('\\', $class); // preg_split('#\\\\|_#', $class);//
    $ns       = array_shift($segments);

    if ('' !== $ns) {
        $file = implode('/', $segments) . '.php';
        return include_once $ns . '/' . $file;
    }

    // old ns
    $segments = explode('_', $class);
    $ns       = array_shift($segments);

    $file = implode('/', $segments) . '.php';
    if (file_exists($file)) {
        return include_once $file;
    }

    return false;
}
spl_autoload_register('SFTestAutoloader', true, true);

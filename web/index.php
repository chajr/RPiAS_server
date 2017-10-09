<?php
/**
 * 
 * This file is part of Aura for PHP.
 * 
 * @package Aura.Framework_Project
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */

$path = dirname(__DIR__);

require "$path/vendor/autoload.php";

/** @var Aura\Web_Kernel\WebKernel $kernel */
$kernel = (new \Aura\Project_Kernel\Factory)->newKernel(
    $path,
    Aura\Web_Kernel\WebKernel::class
);

$kernel();

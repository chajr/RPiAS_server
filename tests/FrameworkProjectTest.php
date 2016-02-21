<?php
namespace Aura\Framework_Project;

class FrameworkProjectTest extends \PHPUnit_Framework_TestCase
{
    public function testCli()
    {
        $console = dirname(__DIR__) . '/cli/console.php';
        $actual = shell_exec("php {$console} hello");
        $expect = 'Hello World!' . PHP_EOL;
        $this->assertSame($expect, $actual);
    }

    public function testWeb()
    {
        $url = "http://rpias.tt/";
        $actual = file_get_contents($url);
        $expect = 'Hello World!';
        $this->assertSame($expect, $actual);
    }
}

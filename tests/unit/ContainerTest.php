<?php

use App\Container;

class ContainerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @testdox El contenedor debe ser singleton
     */
    public function test1()
    {
        $container = Container::getInstance();
        $container2 = Container::getInstance();

        $this->assertSame($container, $container2);
    }

    /**
     * @test
     * @testdox Se deben poder atar closures
     */
    public function test2()
    {
        $container = Container::getInstance();

        $container->bind('key', function () {
            return "closure";
        });

        $this->assertEquals("closure", $container->make('key'));
    }

    /**
     * @test
     * @testdox Se debe poder construir una clase
     */
    public function test3() {
        $container = Container::getInstance();

        $container->bind('foo', Foo::class);

        $this->assertInstanceOf(Foo::class, $container->make('foo'));
    }


}

class Foo {
    public function __construct(Bar $bar)
    {
    }
}

class Bar {
    public function __construct(FooBar $fooBar, BarFoo $barFoo)
    {
    }
}

class FooBar {

}

class BarFoo {}
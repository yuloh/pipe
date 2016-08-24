<?php

namespace Yuloh\Pipe\Tests;

use Yuloh\Pipe\Pipeline;

class PipelineTest extends \PHPUnit_Framework_TestCase
{
    public function testPipelineOrderIsCorrect()
    {
        $stages = [
            function ($req, $next) {
                $res = $next($req);
                $res .= 'first';
                return $res;
            },
            function ($req, $next) {
                $res = $next($req);
                $res .= 'second';
                return $res;
            }
        ];

        $last = function ($value) {
            return $value;
        };

        $result = (new Pipeline($stages, $last))('');

        $this->assertSame('firstsecond', $result);
    }

    public function testPipelineSupportsManyArgs()
    {
        $stages = [
            function ($a, $b, $next) {
                $a = 'a';
                $b = 'b'; 
                return $next($a, $b);
            }
        ];

        $last = function ($a, $b) {
            return $a . $b;
        };

        $result = (new Pipeline($stages, $last))('', '');

        $this->assertSame('ab', $result);
    }

    public function testPipelineSupportsResolvers()
    {
        $resolver = function ($name) {
            return new $name;
        };

        $stages = [ExampleStage::class];
        $last = function ($payload) { return $payload; };

        $result = (new Pipeline($stages, $last, $resolver))('hello');

        $this->assertSame('HELLO', $result);
    }
}

class ExampleStage
{
    public function __invoke($payload)
    {
        return strtoupper($payload);
    }
}

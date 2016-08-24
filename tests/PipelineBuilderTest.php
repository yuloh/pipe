<?php

namespace Yuloh\Pipe\Tests;

use Yuloh\Pipe\PipelineBuilder;
use Yuloh\Pipe\Pipeline;

class PipelineBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testBuilder()
    {
        $pipe = PipelineBuilder::create()
            ->withStack([
                function ($payload, $next) {
                    $payload = $next($payload);
                    return strtoupper($payload);
                },
                function ($payload, $next) {
                    $payload = $next($payload);
                    return strrev($payload);
                }
        ])
            ->then(function ($payload) {
                return $payload;
            });

        $this->assertSame('OLLEH', $pipe('hello'));
    }

    public function testPipe()
    {
        $pipe = PipelineBuilder::create()
            ->pipe(function ($payload, $next) {
                return strtoupper($next($payload));
            })
            ->pipe(function ($payload, $next) {
                return strrev($next($payload));
            });

        $this->assertSame('OLLEH', $pipe('hello'));
    }
}

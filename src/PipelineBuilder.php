<?php

namespace Yuloh\Pipe;

class PipelineBuilder
{
    /**
     * @var Resolver
     */
    private $resolver;

    /**
     * @var callable[]
     */
    private $stack = [];

    /**
     * @var callable
     */
    private $lastStage;

    public static function create()
    {
        return new static;
    }

    public function withResolver(callable $resolver)
    {
        $this->resolver = $resolver;

        return $this;
    }

    public function withStack($stack)
    {
        $this->stack = $stack;

        return $this;
    }

    public function pipe($stage)
    {
        $this->stack[] = $stage;

        return $this;
    }

    public function then($lastStage)
    {
        $this->lastStage = $lastStage;

        return $this;
    }

    public function build()
    {
        $lastStage = $this->lastStage ?: function ($result) {
            return $result;
        };
        return new Pipeline($this->stack, $lastStage, $this->resolver);
    }

    public function __invoke(...$args)
    {
        return $this->build()->__invoke(...$args);
    }
}

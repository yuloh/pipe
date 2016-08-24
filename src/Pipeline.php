<?php

namespace Yuloh\Pipe;

class Pipeline
{
    /**
     * The stack.
     *
     * @var callable[]
     */
    private $stack = [];

    /**
     * The resolver used to resolve the stack.
     *
     * @var callable
     */
    private $resolver;

    /**
     * @var callable
     */
    private $lastStage;

    /**
     * @param callable[] $stack
     * @param callable   $lastStage
     * @param callable   $resolver
     */
    public function __construct(array $stack, callable $lastStage, callable $resolver = null)
    {
        $this->stack     = array_reverse($stack);
        $this->lastStage = $lastStage;
        $this->resolver  = $resolver;
    }

    public function __invoke(...$args)
    {
        $stack      = $this->stack;
        $middleware = array_shift($stack);
        $middleware = $this->resolve($middleware);
        $args[]     = $this->withStack($stack);

        return $middleware(...$args);
    }

    /**
     * Returns a new instance with the given middleware stack.
     *
     * @param callable[] $stack
     *
     * @return $this
     */
    private function withStack(array $stack)
    {
        return new self($stack, $this->lastStage(), $this->resolver);
    }

    /**
     * Resolves the given middleware.
     *
     * @param mixed $middleware
     *
     * @return callable
     */
    private function resolve($middleware)
    {
        if (!$middleware) {
            return $this->lastStage();
        }

        return $this->resolver ? call_user_func($this->resolver, $middleware) : $middleware;
    }

    /**
     * Returns the final stage of the pipeline stack.
     *
     * @return \Closure
     */
    private function lastStage()
    {
        return $this->lastStage;
    }
}

<?php

namespace Yuloh\Pipe;

use Interop\Container\ContainerInterface;

class ContainerResolver implements Resolver
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ContainerResolver constructor.
     *
     * @param \Interop\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $id
     *
     * @return callable
     */
    public function __invoke($id)
    {
        if (!is_string($id)) {
            return $id;
        }

        return $this->container->get($id);
    }
}

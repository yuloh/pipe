<?php

namespace Yuloh\Pipe;

interface Resolver
{
    /**
     * @param mixed $id
     *
     * @return callable
     */
    public function __invoke($id);
}

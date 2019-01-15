<?php

namespace App\Controllers;

use App\Controllers\Controller;
use Psr\Container\ContainerInterface;

abstract class Controller
{
    /**
     * The container instance.
     *
     * @var \Interop\Container\ContainerInterface
     */
    protected $c;

    /**
     * Set up controllers to have access to the container.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->c = $container;
    }
}

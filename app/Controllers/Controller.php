<?php declare(strict_types=1);

namespace App\Controllers;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;

abstract class Controller implements ContainerAwareInterface
{
	use ContainerAwareTrait;
}

<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
	
	public function getCacheDir(): string
    {
		if (is_file(getenv("USERPROFILE").'/symfony/vendor/autoload_runtime.php')) {
			return getenv("USERPROFILE").'/symfony/var/'.$this->environment.'/cache';
		}
		else {
			return dirname(__DIR__).'/var/'.$this->environment.'/cache';
		}
    }
    public function getLogDir(): string
    {
		if (is_file(getenv("USERPROFILE").'/symfony/vendor/autoload_runtime.php')) {
			return getenv("USERPROFILE").'/symfony/var/'.$this->environment.'/log';
		}
		else {
			return dirname(__DIR__).'/var/'.$this->environment.'/log';
        }
    }
}

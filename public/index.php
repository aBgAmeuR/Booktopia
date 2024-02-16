<?php

use App\Kernel;

if( ! file_exists(getenv("USERPROFILE").'/symfony/'))
	require_once dirname(__DIR__).'/vendor/autoload_runtime.php';
else {
	require_once getenv("USERPROFILE").'/symfony/vendor/autoload_runtime.php';
	$_ENV['APP_RUNTIME_OPTIONS']['project_dir'] = dirname(__DIR__) ;
}

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};

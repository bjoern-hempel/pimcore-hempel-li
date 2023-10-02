<?php

// We want to use the global bootstrap, but customize the setup a bit to run tests with a larger scope.
require_once dirname(__DIR__) . '/bootstrap.php';

/* disable deprecated warnings */
putenv('SYMFONY_DEPRECATIONS_HELPER=weak');

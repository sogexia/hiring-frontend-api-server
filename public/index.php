<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    $mandatoryApplicationEnv = ['API_TOKEN'];
    foreach ($mandatoryApplicationEnv as $envName) {
        if (!isset($context[$envName]) || empty($context[$envName])) {
            throw new RuntimeException('You have missed configured your application, please read the README.md');
        }
    }
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};

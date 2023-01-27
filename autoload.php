<?php

use Controller\log\Logger;

include __DIR__ . '/../localhost/vendor/autoload.php';

spl_autoload_register(
/**
 * @throws Exception
 */
    function ($className) {
        $base = __DIR__ . '/../localhost/';

        $way = str_replace('\\', '/', $className);
        $file = $base . $way . '.php';

        if (file_exists($file)) {
            require $file;
        } else {
            var_dump($file);
            $log = new Logger();
            $log->log(new Exception(message: ' в доступе отказано'));
            throw new Exception(message: ' в доступе отказано');
        }
    }
);
#!/bin/bash

if [ ! -f ./phpunit ]; then
    curl https://phar.phpunit.de/phpunit-9.0.1.phar -o phpunit
    chmod +x phpunit
fi

./phpunit ConvertTest.php

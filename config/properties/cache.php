<?php

/*
 * This file is part of Swoft.
 * (c) Swoft <group@swoft.org>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    'redis' => [
        'name'        => 'redis',
        'uri'         => [
            'tcp://127.0.0.1:6379',
            'tcp://127.0.0.1:6379',
        ],
        'minActive'   => 8,
        'maxActive'   => 8,
        'maxWait'     => 8,
        'maxWaitTime' => 3,
        'maxIdleTime' => 60,
        'timeout'     => 8,
        'db'          => 1,
        'serialize'   => 0,
    ],
    'demoRedis' => [
        'db'     => 1,
        'prefix' => '',
    ],
    'zhRedis' => [
        'db'     => 1,
        'prefix' => '',
    ],
];
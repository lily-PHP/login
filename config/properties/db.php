<?php

/*
 * This file is part of Swoft.
 * (c) Swoft <group@swoft.org>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    //DB_URI=10.10.30.202:3306/zhshop_mall?user=root&password=x7l0Nyr0lTczl4Ta&charset=utf8,10.10.30.202:3306/test?user=root&password=x7l0Nyr0lTczl4Ta&charset=utf8
    'master' => [
        'name'        => 'master',
        'uri'         => [
//            '10.10.30.202:3306/zhshop_mall?user=root&password=x7l0Nyr0lTczl4Ta&charset=utf8',
        '47.74.214.40:3306/zhshop_mall?user=root&password=@x7l0Nyr0lTczl4Ta&charset=utf8',
        ],
        'minActive'   => 8,
        'maxActive'   => 8,
        'maxWait'     => 8,
        'timeout'     => 8,
        'maxIdleTime' => 60,
        'maxWaitTime' => 3,
    ],

//    'slave' => [
//        'name'        => 'slave',
//        'uri'         => [
//            '127.0.0.1:3306/test?user=root&password=123456&charset=utf8',
//            '127.0.0.1:3306/test?user=root&password=123456&charset=utf8',
//        ],
//        'minActive'   => 8,
//        'maxActive'   => 8,
//        'maxWait'     => 8,
//        'timeout'     => 8,
//        'maxIdleTime' => 60,
//        'maxWaitTime' => 3,
//    ],
];
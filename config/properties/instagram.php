<?php

return [
    //https://api.instagram.com/oauth/authorize/?client_id=d12fa53b86bd4ab698451ab2f2bd495b&redirect_uri=http://www.swoft.com:8888/user/getCode&response_type=code
    //https://api.instagram.com/oauth/authorize/?client_id=d12fa53b86bd4ab698451ab2f2bd495b&redirect_uri=http://www.swoft.com:8888/user/insloginok&response_type=token
    'instConfig' => [
        'client_id' => 'd12fa53b86bd4ab698451ab2f2bd495b',
        'client_secret' => '7e1b82d75e1c4c2c9365c832d880954e',
        'grant_type' => 'authorization_code',
        'redirect_uri' => 'http://www.swoft.com:8888/user/insloginok',
        'code_url' => 'https://api.instagram.com/oauth/authorize/',
        'access_token_url' => 'https://api.instagram.com/oauth/access_token'
    ],
];
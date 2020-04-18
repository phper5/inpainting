<?php
/**
 * Created by PhpStorm.
 * User: white
 * Date: 8/13/18
 * Time: 7:26 PM
 */
return [
    'callback_url' => env('OSS_INPUT_CALLBACK_URL',"http://recovery.diandi.org/api/callback/inputoss"),
    'access_key' => env('OSS_ACCESS_KEY',""),
    'secret_key' => env('OSS_SECRET_KEY',""),
];

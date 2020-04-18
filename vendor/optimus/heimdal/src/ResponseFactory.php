<?php

namespace Optimus\Heimdal;

use Exception;
use Illuminate\Http\JsonResponse;

class ResponseFactory
{
    public static function make(\Throwable $e)
    {
        return new JsonResponse([
            'status' => 'error'
        ]);
    }
}

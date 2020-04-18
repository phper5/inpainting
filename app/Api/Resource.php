<?php


namespace App\Api;


use Illuminate\Http\Request;

class Resource extends Api
{
    public function getOne(Request $request,$id) {
        $resource = \App\Resource::find($id);
        return (new Response())->setData($resource->toResponse())->Json();
    }
}

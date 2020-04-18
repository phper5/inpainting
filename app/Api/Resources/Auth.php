<?php


namespace App\Api\Resources;


use App\Api\Response;
use App\Exceptions\ApiException;
use App\Oss;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Auth
{
    public function get(Request $request)
    {
        $user = $request->user();
//        throw new HttpException(400, 'dfdfd');
        $data = (new Oss())->getUoloadSts();
        $data['bucket'] = "coder5-ai";
        $data['endpoint'] = "oss-cn-hongkong.aliyuncs.com";
        $data['path'] = date('Ymd').'/repair/';
        $params = [
            'user_id'=>$user->id
        ];
        $data['callback'] =  (new Oss())->buildCallbackUrl($params);
        return (new Response())->setData($data)->Json();
    }
}

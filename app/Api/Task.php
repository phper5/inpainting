<?php


namespace App\Api;


use App\Exceptions\ApiException;
use App\Resource;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;

class Task
{

    public function  getOne(Request $request,$id)
    {
        $i = 0;
//        if ($task = \App\Task::find($id))
//        {
//            return (new Response())->setData($task->toResponse())->setHeaders(['Cache-Control'=>'no-cache'])->Json();
//        }
//
//        return [];
        $user = $request->user('api');
        if ($task = \App\Task::find($id))
        {
            if ($task->user_id!=$user->getKey())
            {
                throw new ApiException(ApiException::INVAILD_ACCESS);
            }
            return (new Response())->setData($task->toResponse())->setHeaders(['Cache-Control'=>'no-cache'])->Json();

        }
        throw new ApiException(ApiException::NOT_FOUND);
    }

    public function post(Request $request)
    {
        $user = $request->user('api');
        $uid = $user->getAuthIdentifier();
        $task = new \App\Task();
        $task->user_id = $uid;
        $task->id = Uuid::uuid();
        $task->service ='repair';
        $task->status =\App\Task::STATUS_QUEUE;
        $task->progress =0;
        $task->service ='repair';
        $task->args =json_encode(
            ['mask'=>$request->get('mask')]
        );
        $task->source_file = $request->get('resource_id');
        $task->save();
        return (new Response())->setData($task->toResponse())->setHeaders(['Cache-Control'=>'no-cache'])->Json();
    }
    public function putOne(Request $request,$task_id)
    {

    }
}

<?php


namespace App\Api;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class Token
{
    public function get(Request $request)
    {
        $token = Str::random(36);
        $user = $request->user();
        if (!$user) {
            $user = new User();
            $user->id = '-'.substr(\Faker\Provider\Uuid::uuid(),0,-1);
            $user->name ='guest';
            $user->email = $user->id.'@diandi.org';
            $user->save();
        }
        $user->forceFill([
            'api_token' => $token,//hash('sha256', $token),
        ])->save();
        return (new Response())->setData(['token' => $token])->Json();
    }
}

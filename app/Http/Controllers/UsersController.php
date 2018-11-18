<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{
    public function __construct()
    {
        //限制未登录用户更改
        $this->middleware('auth',['except' => ['show']]);
    }

    public function show(User $user){
    	return view('users.show',compact('user'));
    }

    public function edit(User $user){
        //限制已登录的userId=1的用户去修改userId=2的用户数据,这个需要在策略policy中配置
        $this->authorize('update', $user);

    	return view('users.edit',compact('user'));
    }

    public function update(UserRequest $request,ImageUploadHandler $uploader,User $user){
        $this->authorize('update', $user);

        $data = $request->all();
        if($request->avatar){
            $result = $uploader->save($request->avatar,'avatars',$user->id,362);
            if($result){
                $data['avatar'] = $result['path'];
            }
        }

    	$user->update($data);
    	return redirect()->route('users.show',$user->id)->with('success','个人资料更新成功!');
    }
}

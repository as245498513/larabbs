<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @author CMJ
     * @Date   2018-11-18
     * @param    User          当前登录用户实例(调用时，默认情况下，我们 不需要 传递当前登录用户至该方法内，因为框架会自动加载当前登录用户)
     * @param    User          要进行授权的用户实例
     * @return   true/false    是否同一个人
     */
    public function update(User $currentUser,User $user)
    {
        return $currentUser->id === $user->id;
    }
}

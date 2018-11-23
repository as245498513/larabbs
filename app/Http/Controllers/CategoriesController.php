<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Topic;
use App\Models\Category;
use App\Models\User;

class CategoriesController extends Controller
{

    public function show(Category $category, Request $request, Topic $topic, User $user){
    	//读取分类ID关联的话题,并按每20条分页
    	//$topics = Topic::where('category_id',$category->id)->paginate(20);
    	//延迟加载解决N+1问题
    	//$topics->load(['user', 'category']);
    	$topics = Topic::with('user', 'category')->where('category_id', $category->id)->paginate(20);

    	//活跃用户列表
    	$active_users = $user->getActiveUsers();

    	//传参变量话题和分类到模板中
    	return view('topics.index',compact('category','topics','active_users'));
    }
}

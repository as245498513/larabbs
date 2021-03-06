<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1',[
	'namespace' => 'App\Http\Controllers\Api',
	'middleware' => ['serializer:array', 'bindings']
],function($api){
	$api->group([

		'middleware' => 'api.throttle',
		'limit' => config('api.rate_limits.sign.limit'),
		'expires' => config('api.rate_limits.sign.expires'),

	],function($api){
		//短信验证码
		$api->post('verificationCodes','VerificationCodesController@store')->name('api.verificationCodes.store');
		//用户注册
		$api->post('users','UsersController@store')->name('api.users.store');
		//图片验证码
		$api->post('captchas','CaptchasController@store')->name('api.captchas.store');

		//第三方登录
		$api->post('socials/{social_type}/authorizations','AuthorizationsController@socialStore')->name('api.socials.authorizations.store');

		//正常登录
		$api->post('authorizations','AuthorizationsController@store')->name('api.authorizations.store');

        // 小程序登录
        $api->post('weapp/authorizations', 'AuthorizationsController@weappStore')
            ->name('api.weapp.authorizations.store');

		// 刷新token
		$api->put('authorizations/current', 'AuthorizationsController@update')
		    ->name('api.authorizations.update');
		// 删除token
		$api->delete('authorizations/current', 'AuthorizationsController@destroy')
		    ->name('api.authorizations.destroy');

	    //分类列表(游客)
    	$api->get('categories', 'CategoriesController@index')
    	    ->name('api.categories.index');

    	//话题列表接口(游客)
		$api->get('topics', 'TopicsController@index')
		    ->name('api.topics.index');

    	//查看某个用户发布的话题接口(游客)
		$api->get('users/{user}/topics', 'TopicsController@userIndex')
		    ->name('api.users.topics.index');

		//话题详情接口(游客)
		$api->get('topics/{topic}', 'TopicsController@show')
		    ->name('api.topics.show');

        // 需要 token 验证的接口
        $api->group(['middleware' => 'api.auth'], function($api) {

            // 当前登录用户信息
            $api->get('user', 'UsersController@me')
                ->name('api.user.show');

		    // 图片资源
		    $api->post('images', 'ImagesController@store')
		        ->name('api.images.store');
		        
			// 编辑登录用户信息
			$api->patch('user', 'UsersController@update')
			    ->name('api.user.update');

			// 发布话题
			$api->post('topics', 'TopicsController@store')
			    ->name('api.topics.store');

			//修改话题
			$api->patch('topics/{topic}', 'TopicsController@update')
			    ->name('api.topics.update');

			//删除话题
			$api->delete('topics/{topic}', 'TopicsController@destroy')
			    ->name('api.topics.destroy');
        });

	});

});

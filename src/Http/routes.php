<?php
//add api router for dingo api
Route::group(['prefix' => 'api'], function () {
	$api = app('Dingo\Api\Routing\Router');
	$api->version('v1', ['middleware' => 'api.auth'], function ($api) {
		$api->get('authenticated_user', 'Groovel\Restapi\Http\Controllers\api\auth\AuthenticateController@authenticatedUser');
			
	});

		$api->version('v1',['middleware'=>'groovel.filter','groovel.userrules'], function ($api) {
			$api->get('/', function() {
				return ['Test' => 'Hello test!'];
			});

				//$api->post('authenticate', 'Groovel\Cmsgroovel\Http\Controllers\api\AuthenticateController@authenticate');
				//$api->get('getMessages', 'Groovel\Cmsgroovel\Http\Controllers\api\MessageController@getMessages');

				//$api->post('logout', 'Groovel\Cmsgroovel\Http\Controllers\api\AuthenticateController@logout');

				//$api->get('token', 'Groovel\Cmsgroovel\Http\Controllers\api\AuthenticateController@getToken');
		});

});
<?php

namespace Groovel\Restapi\Http\Controllers\api\auth;

use JWTAuth;
use App\Http\Requests;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Routing\Controller;
use Groovel\Cmsgroovel\models\User;

class AuthenticateController extends Controller
{


    /**
     *  API Login, on success return JWT Auth token
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
    	   $credentials = $request->only('username', 'password');
        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        \Log::info(response()->json(compact('token')));
        // all good so return the token
        return response()->json(compact('token'));
    }


    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request)
    {
        /*$this->validate($request, [
            'token' => 'required'
        ]);*/

        JWTAuth::invalidate($request->input('token'));
    }


    /**
     * Returns the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }


    /**
     * Refresh the token
     *
     * @return mixed
     */
    public function getToken()
    {
        $token = JWTAuth::getToken();

        if (!$token) {
            return response()->json('Token not provided');
        }

        try {
            $refreshedToken = JWTAuth::refresh($token);
        } catch (JWTException $e) {
            return  response()->errorInternal('Not able to refresh Token');
        }

        return response()->json(['token' => $refreshedToken]);
    }
    
    public function signup(Request $request)
    {
    	
    	\Log::info($request);
    	$userData = $request->only('username','email','password');
     	$user = new User();
   		$user->username=$userData['username'];
    	if(!empty($userData['password'])){
    		$user->password=\Hash::make($userData['password']);
    	}
    	$user->pseudo=$userData['username'];
    	$user->email=$userData['email'];
    	$user->activate=true;
      	$user->save();
    	if(!$user->id) {
    		return response()->error('could_not_create_user', 500);
    	}
    
    /*	if($hasToReleaseToken) {
    		return $this->login($request);
    	}*/
    
    	return  response()->json('account created!');
    }
    

}
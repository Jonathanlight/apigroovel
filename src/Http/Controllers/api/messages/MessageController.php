<?php

namespace Groovel\Restapi\Http\Controllers\api\messages;

use JWTAuth;
use App\Http\Requests;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Routing\Controller;

use Dingo\Api\Routing\Helpers;
use Groovel\Restapi\Http\Controllers\api\messages\MessageTransformer;
use Groovel\Cmsgroovel\models\Messages;

class MessageController extends Controller
{
	use Helpers;

    /**
     *  send message
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        try {
        $messages=array();
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            } else{//sendmess
        	
            	$mess = $request->only('subject', 'recipient','author','body');
            	$message=new Messages();
            	$message->subject=$mess['subject'];
            	$message->recipient=$mess['recipient'];
            	$message->author=$mess['author'];
            	$message->body=$mess['body'];
            	$message->save();
        	
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
     *  get all messages for a specific user
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessages(Request $request)
    {
    	try {
    		$messages=array();
    	if (!$user = JWTAuth::parseToken()->authenticate()) {
    			return response()->json(['user_not_found'], 404);
    		} else{///list all messages and returns
    			$user = app('Dingo\Api\Auth\Auth')->user();
    			$messages =  Messages::where('recipient','=',$user['pseudo'])->get();//Messages::all();
    			
    			// return $this->response->array(['data' => $fruits], 200);
    			return $this->collection($messages, new MessageTransformer);
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
    
}
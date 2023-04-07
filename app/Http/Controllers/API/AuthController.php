<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class AuthController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('Lumis')->accessToken;
        $success['name'] =  $user->name;
        $success['email'] =  $user->email;

        return $this->sendResponse($success, 'User register successfully . ');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('Lumis')-> accessToken;
            $success['name'] =  $user->name;
			$success['email'] =  $user->email;

            return $this->sendResponse($success, 'User login successfully . ');
        }
        else{
            return $this->sendError('Unauthorised . ', ['error'=>'Unauthorised']);
        }
    }

	public function account(Request $request)
	{
		$email = $request->email;

		$user = User::where('email', $email)->first();
		if($user){
			$success['token'] =  $user->createToken('Lumis')-> accessToken;
			$success['user'] =  $user;
			return $this->sendResponse($success, 'Account Exists');
		}else{
			return $this->sendError('Not Found', ['error'=>'NotFound']);
		}
	}
}

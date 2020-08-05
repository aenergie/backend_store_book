<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

	protected function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users',
            'password' => 'required|string'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Unauthorized', 422, $validator->errors()->toArray());
        }

		$credentials = request(['email', 'password']);

        if(!Auth::guard('web')->attempt($credentials))
            return $this->sendError( 'Unauthorized', 401, ["password" => ["The password does not match"]] );

        $user = Auth::guard('web')->user();

		$tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me){
			$token->expires_at = Carbon::now()->addWeeks(1);
		}

		$token->save();

        return $this->sendResponse([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse( $tokenResult->token->expires_at )->toDateTimeString()
		], "User logged !!");

    }
    protected function logout( Request $request )
    {

		//auth()->user()->authAcessToken()->delete();
		$request->user()->token()->revoke();
        return $this->sendResponse([], 'Successfully logged out');
    }

    protected function me(){
        try {
            return $this->sendResponse( auth()->user(), "Retrieved user logged!!");
        } catch (TokenExpiredException $e) {
            return $this->sendError('token_expired', $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return $this->sendError('token_invalid', $e->getStatusCode());
        } catch (JWTException $e) {
            return $this->sendError('token_absent', $e->getStatusCode());
        }catch (\Exception $e) {
            return $this->sendError('user_not_found', 404);
        }
    }

}

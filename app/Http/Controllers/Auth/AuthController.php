<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
class AuthController extends BaseController
{
    public function login(Request $request){

        if ($request->isJson()) {
            $input = $request->json()->all();
        } else {
            $input = $request->all();
        }

        $validator = Validator::make($input, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string', //admin pass dependency
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->sendError('Validation Error.', $errors);
        }

        if (Auth::attempt(["email" => $input['email'], "password" => $input['password']])) {
            $user = Auth::user();
            $user["token"] = $user->createToken('accessToken')->accessToken;
            $user["roles_names"] = $user->getRoleNames();
            $user["permissions"] = $user->getPermissionsViaRoles()->pluck("name");
            return $this->sendResponse($user, 'Login successfully.');
        }
        else {
            $error['message'] = "You Credential is incorrect.";
            $error['code'] = "AUTHENTICATION_ERROR";
            return $this->sendError('Logical Error.', $error);
        }

    }

    public function logout()
    {
        $token = Auth::user()->token()->revoke();
        return $this->sendResponse('', 'Logged out.');
    }
}

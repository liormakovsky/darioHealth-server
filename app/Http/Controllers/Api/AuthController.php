<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends BaseController
{
    public function signin(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $request->session()->regenerate();
            $authUser = Auth::user(); ;
            $success['token'] =  $authUser->createToken($authUser->email.'_Token')->plainTextToken; 
            $success['usr_name'] = $authUser->usr_name;
            $success['email'] = $authUser->email;
  
            return $this->sendResponse($success, 'User signed in');
        } 
        else{ 
            return $this->sendError('Wrong name or password', ['error'=>'Unauthorized'],403);
        } 
    }

    public function signup(Request $request)
    {
   
        $validator = Validator::make($request->all(), [
            'usr_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors(),403);       
        }

        $input = $request->all();

        $input['password'] = bcrypt($input['password']);
        $input['usr_created'] = Carbon::now();

        $user = User::create($input);

        if(!$user){
            return $this->sendError('Failed to Create User', [],500);    
        }

        $user->createToken($user->email.'_Token');

        if(Auth::attempt(['email' => $user->email, 'password' => $request->password])){ 
        $request->session()->regenerate();

        $success['usr_name']= $user->usr_name;
        $success['email'] = $user->email;
        $success['token'] =  $user->createToken($user->email.'_Token')->plainTextToken;
 

        return $this->sendResponse($success, 'User created successfully.');
        }else{
            return $this->sendError('Failed to Create User', [],500); 
        }
    }
    
    public function updateUser(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'usr_name' => 'required',
            'email' => 'required|email'
        ]);
   
        if($validator->fails()){
            return $this->sendError("Error", $validator->errors(),403);       
        }

        $input = $request->all();
        $updateUser = [
           'usr_name'=>$input['usr_name'],
           'email'=>$input['email'],
        ];

        $user = auth()->user()->update($updateUser);

        $user = User::find(Auth::id());

        return $this->sendResponse($user, 'User updated');

    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return $this->sendResponse('success', 'User logout successfully.');
    }


}
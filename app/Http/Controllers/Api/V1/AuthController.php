<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\User;


class AuthController extends Controller
{
    // public function __construct(){
    //     $this->middleware('auth:Api',['except' => ['register','login']]);
    // }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'profile' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:2028', 
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
    
        $data = $validator->validated();
    
        
        if ($request->hasFile('profile')) {
            $profileImage = $request->file('profile');
            $profilePath = $profileImage->store('profile_images', 'public'); 
            $data['profile'] = $profilePath;
        }
    
        
        $user = User::create(array_merge($data, ['password' => bcrypt($request->password)]));
    
        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function login(Request $request){
         $validator = Validator::make($request->all(),[             
                  'email' => 'required|email',
                  'password' => 'required|string|min:6',
         ]);

         if($validator->fails()){
            return response()->json($validator->errors()->toJson(),422);
         }

         if(!$token=auth()->attempt($validator->validated())){
            return response()->json(['error'=>'UnAuthorized'],401);
         }

         return $this->createNewToken($token);

    }
     public function createNewToken($token){
          
            $refreshToken = Str::random(60);
            return response()->json([
                 'access_token' => $token,
                 'refresh_token' => $refreshToken,
                 'token_type' => 'bearer',
                 'expires_in' => auth()->factory()->getTTL()*60,
                 'auth' => auth()->user(),
            ]);
    }

    public function refreshToken(Request $request){
        $validator = Validator::make($request->all(),[
               'refresh_token' => 'required|string',
        ]);

        if($validator->fails()){
             return response()->json($validator->errors()->toJson(),422);
        }

        $refreshToken = $request->input('refresh_token');

        $user = auth()->user();

        $newAccessToken = auth()->login($user);

        return $this->createNewToken($newAccessToken);
    }

    public function logout(){
        auth()->logout();
        return response()->json(['message' => 'user loged out successefully']);
    }
}

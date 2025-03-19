<?php


namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Auth;

class UserController extends Controller
{
    
    public function profile()
    {
        return response()->json(auth()->user());
    }

  
    public function update(Request $request)
    {
        $user = auth()->user(); 

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'profile' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:2028',  
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        
        if ($request->hasFile('profile')) {
           
            if ($user->profile && \Storage::disk('public')->exists($user->profile)) {
                \Storage::disk('public')->delete($user->profile);
            }

            
            $profileImage = $request->file('profile');
            $profilePath = $profileImage->store('profile_images', 'public');
            $user->profile = $profilePath;
        }

       
        $user->name = $request->input('name');
        $user->save();

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }


    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }


    public function store(Request $request)
    {
       
    }
}

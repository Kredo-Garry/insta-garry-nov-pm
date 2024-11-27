<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    /**
     * Search the details of specific user base on user id
     */
    public function show($user_id){
        $user = $this->user->findOrFail($user_id);
        //Same as: "SELECT FROM users WHERE id = $user_id";

        return view('users.profile.show')->with('user', $user);
    }

    /**
     * Search the details of specific user base on user id
     * Note: This method is use only to open the edit page
     */
    public function edit(){
        $user = $this->user->findOrFail(Auth::user()->id);
        //Same as: "SELECT FROM users WHERE id = Auth::user->id";


        return view('users.profile.edit')->with('user', $user);
    }

    /**
     * This is the methoid that is doing the actual action of updating the user details
     */
    public function update(Request $request){
        #1. Validate the data from the form first
        $request->validate([
            'name' => 'required|min:1|max:50',
            'email' => 'required|email|max:50|unique:users,email,' . Auth::user()->id,
            'avatar' => 'mimes:jpeg,jpg,gif,png|max:1048'
        ]);

        #2. Update the user details
        $user = $this->user->findOrFail(Auth::user()->id); //Same as: "SELECT * FROM users WHERE id = Auth::user()->id";
        $user->name = $request->name;
        $user->email = $request->email;
        $user->introduction = $request->introduction;

        /**
         * Check if an avatar/image is uploaded
         */
        if ($request->avatar) { //true
            $user->avatar = 'data:image/' . $request->avatar->extension() . ';base64,' . base64_encode(file_get_contents($request->avatar));
        }
        #save
        $user->save();
        #Same as: UPDATE users SET name = '$request->name', email = '$request->email', introduction = '$request->introduction', avatar = '$request->avatar' WHERE id = Auth::user()->id;

        #Redirect
        return redirect()->route('profile.show', Auth::user()->id);
    }

    public function followers($user_id){
        $user = $this->user->findOrFail($user_id);
        return view('users.profile.followers')->with('user', $user);
    }

    public function following($user_id){
        $user = $this->user->findOrFail($user_id);
        return view('users.profile.following')->with('user', $user);
    }


}

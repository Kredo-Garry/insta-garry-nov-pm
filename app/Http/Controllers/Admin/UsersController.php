<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; //this represents users table

class UsersController extends Controller
{
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function index(){
        $all_users = $this->user->withTrashed()->latest()->paginate(5);
        # Note: The withTrashed() will retrieved the users that have been soft deleted.

        return view('admin.users.index')->with('all_users', $all_users);
    }

    /**
     * This method will deactivate a user
     */
    public function deactivate($user_id){
        $this->user->destroy($user_id);
        //Same as: "DELETE FROM users WHERE id = $user_id";
        //forceDelete() --> will totally removed the account/user from the table

        return redirect()->back();
    }

    /**
     * This method will activate a user
     */
    public function activate($user_id){
        $this->user->onlyTrashed()->findOrFail($user_id)->restore();
        return redirect()->back();
        /**
         * Note: onlyTrashed() -- will retrieved all users that
         * have been soft deleted and include it in the query result.
         * 
         * restore() -- will "un-delete" the sot deleted model, ande set the "deleted_at" column to null.
         */
    }
}

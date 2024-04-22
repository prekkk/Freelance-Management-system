<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        $users = User::orderBy('created_at','DESC')->paginate(4);
        return view('admin.users.list',[
            'users' => $users
        ]);
    }

    public function edit($id) {
        $user = User::findOrFail($id);

        if (!$user) {
            // User not found, handle gracefully
            session()->flash('error', 'User not found');
            return redirect()->route('admin.users.index');
        }
        
        return view('admin.users.edit',[
            'user' => $user
        ]);
    }

    public function update($id, Request $request) {
        
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:5|max:20',
            'email' => 'required|email|unique:users,email,'.$id.',id'
        ]);


        if ($validator->passes()) {

            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->designation = $request->designation;
            $user->save();

            session()->flash('success','User information updated successfully.');

            return redirect('admin/usersedit/'  . $user->id);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy(Request $request){
        $id = $request->id;

        $user = User::find($id);

        if ($user == null) {
            session()->flash('error','User not found');
            return response()->json([
                'status' => false,
            ]);
        }

        $user->delete();
        session()->flash('success','User deleted successfully');
        return response()->json([
            'status' => true,
        ]);
    }
}

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
            'email' => 'required|email|unique:users,email,'.$id.',id',
            'mobile' => 'required|digits:10|unique:users,mobile,'.$id.',id' 
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
            // If mobile number validation fails, redirect back with an error message
            if ($validator->errors()->has('mobile')) {
                session()->flash('error', 'Invalid input for mobile number.');
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            // If other validations fail, set a generic error message
            session()->flash('error', 'Invalid inputs.');    
        }
    }

    public function destroy(Request $request)
{
    $id = $request->id;

    $user = User::find($id);

    if ($user == null) {
        session()->flash('error', 'User not found');
        return response()->json(['status' => false]);
    }

    $user->delete();
    if ($request->ajax()) {
        return response()->json(['status' => true]);
    } else {
        session()->flash('success', 'User deleted successfully');
        return redirect()->back();
    }
}
}
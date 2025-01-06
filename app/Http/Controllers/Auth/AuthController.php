<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserPermission;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('Auth.login');
    }

    public function userDashboard()
    {
        return view('Auth.user_dashboard');
    }

    public function authenticate(Request $request)
    {

        $credentails = $request->validate([
            'user_name' => 'required|max:30',
            'password' => 'required'
        ]);
        if (Auth::attempt($credentails)) {
            if (!Auth::user()->logged_in) {
                return redirect()->route('first_login_change_password');
            }
            return redirect()->route('high_scale_dashboard');
        }

        return redirect()->route('login')->with('login_error', 'نام کربر و یا رمز اشتباه است');
    }

    // public function userRegister(Request $request)
    // {

    //     $credentails = $request->validate([
    //         'username' => 'required|max:30',
    //         'password' => 'required'
    //     ]);
    //     Auth::loginUsingId(1);
    //     return redirect()->route('high_scale_dashboard');
    //     if (Auth::attempt($credentails)) {
    //         return redirect()->route('high_scale_dashboard');
    //     }

    //     return redirect()->route('login')->with('login_error', 'نام کربر و یا رمز اشتباه است');
    // }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    }

    // public function updatePermissions(Request $request, $id){

    //     UserPermission::where('user_id', $id)->where('system_id',2)->delete();

    //     if(!is_null($request->permission_id)){
    //         foreach($request->permission_id as $permission){
    //             UserPermission::create([
    //                 'permission_id' => $permission,
    //                 'user_id' => $id,
    //                 'system_id' => 2,
    //             ]);
    //         }
    //     }


    //     return redirect()->back()->with('success','صلاحیت ها موفقانه ثبت گردید.');
    // }

    // public function changepassword(){
    //     // dd(auth()->user()->id);
    //     return view('Auth.changepassword');
    // }

    // public function updatepassword(Request $request){
    //     // dd($request->all());
    //     $request->validate([
    //         'password' => 'required|confirmed',
    //     ]);

    //     $hashed_password = Hash::make($request->password);

    //     $user = User::find(auth()->user()->id);
    //     $user->password = $hashed_password;
    //     $user->save();
    //     return back()->with('success','رمز موفقانه تبدیل گردید');
    // }
}

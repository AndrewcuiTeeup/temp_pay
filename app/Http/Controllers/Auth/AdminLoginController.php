<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    //
    public function showLoginForm()
    {
        // set default language is English in the cookie
        $lang=\Cookie::get('locale');
        if(empty($lang)){
            \Cookie::queue(\Cookie::forever('locale', 'en'));
        }
        return view('auth.admin-login');
    }

    public function refreshCaptcha()
    {
        return response()->json(['captcha'=> captcha_img()]);
    }
    public function login(Request $request)
    {
       // validate
        $rule=['email'=>'required|email','password'=>'required|min:6', 'captcha' => 'required|captcha'];
        $this->validate($request,$rule,[
            'captcha.required' => __('login.captcha_required'),
            'captcha.captcha' =>  __('login.captcha_error'),
        ]);

        // attempt  to log the user in
       if(Auth::guard('admin')->attempt(['email'=>$request->email,'password'=>$request->password],$request->remember))
       {
           $type=Auth::guard('admin')->user()->type;
          // 1 -管理员 2- 财务 3- sales
   /*        switch ($type)
           {
               case 1:{
                       Auth::guard('admin')->user()->assignRole('admin');
                       break;
                   }
               case 2:{
                   Auth::guard('admin')->user()->assignRole('finance');
                   break;
               }
               case 3:{
                   Auth::guard('admin')->user()->assignRole('sales');
                   break;
               }
           }*/
           return redirect()->intended(route('admin.dashboard'));
       }

       return redirect()->back()->withInput($request->only('email','remember'));
    }

    public function logout(Request $request)
    {

        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        return redirect(route('admin.login'));
    }

}

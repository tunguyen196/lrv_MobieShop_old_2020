<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\Models\User;
use Auth;
use Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function redirectProvider($social)
    {
        # code...
        return Socialite::driver($social)->redirect();
    }
    public function handleProviderCallback($social)
    {
        # code...
        $user = Socialite::driver($social)->user();
        $authUser = $this->findOrCreateUser($user);
        Auth::login($authUser);
        return redirect('/');
    }
    public function updatePassClient(){

    }

    private function findOrCreateUser($user){
        $authUser = User::where('social_id',$user->id)->first();
        if($authUser){
            return $authUser;
        }else{
            return User::create([
                'name' => $user->name,
                'email' => $user->email,
                'password' => '',
                'social_id' => $user->id,
                'ruler' => 0,
                'status' => 0,
                'avatar' => $user->avatar,
            ]);
        }

    }
    public function logoutClient(){
        if(Auth::check()){
            Auth::logout();
            return redirect('/');
        }
    }
    public function registerClient(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:1|max:255',
            'email' => 'required|email|min:1|max:255|unique:users',
            'password' => 'required',
            're_password' => 'required|same:password'
        ],
        [
            'min' => ':attribute ph???i t??? 1 ?????n 255 k?? t???',
            'max' => ':attribute ph???i t??? 1 ?????n 255 k?? t???',
            'unique' => ':attribute ???? ???????c s??? d???ng',
            'required' => ':attribute kh??ng ???????c ????? tr???ng',
            'email' => ':attribute ph???i ????ng ?????nh d???ng email',
            're_password.same' => 'Nh???p l???i kh??ng tr??ng m???t kh???u'
        ],
        [
             'name' => 'T??n ????ng nh???p',   
             'email'=>'Email',
             'password' => 'M???t kh???u',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator, 'register')->withInput();
        }
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);
        Auth::login($user);
        return back();
    }
    public function loginClient(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|min:1|max:255',
            'password' => 'required',
        ],
        [
            'min' => ':attribute ph???i t??? 1 ?????n 255 k?? t???',
            'max' => ':attribute ph???i t??? 1 ?????n 255 k?? t???',
            'required' => ':attribute kh??ng ???????c ????? tr???ng',
            'email' => ':attribute ph???i ????ng ?????nh d???ng email',
        ],
        [ 
             'email'=>'Email',
             'password' => 'M???t kh???u',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator, 'login')->withInput();
        }

        $user = $request->only('email', 'password');
        if (Auth::attempt($user,$request->has('remember'))) {
            // Authentication passed...
            return back()->with(['ctSuccess' => 1,'ctMessage' => '????ng nh???p th??nh c??ng']);
        }else{
            return back()->with(['ctErrorrs' => 1,'ctMessage' => '????ng nh???p th???t b???i']);
        }
    }
    public function loginAdmin(Request $request){
        $user = $request->only('email', 'password');
        if (Auth::attempt($user,$request->has('remember'))) {
            // Authentication passed...
            if(Auth::user()->ruler === 1){
                return redirect('/admin')->with(['ctSuccess' => 1,'ctMessage' => '????ng nh???p th??nh c??ng']);
            }else if(Auth::user()->ruler === 2){
                return redirect()->route('product.index');
            }else if(Auth::user()->ruler === 3){
                return redirect()->route('order.index');
            }else{
                return redirect()->route('admin.login')->with(['ctErrorrs' => 1,'ctMessage' => 'B???n ch??a ???????c ph??n quy???n']);
            }
        }else{
            return back()->with(['ctErrorrs' => 1,'ctMessage' => '????ng nh???p th???t b???i']);
        }
    }
}

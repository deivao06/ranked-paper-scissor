<?php

namespace App\Http\Controllers;

use App\Http\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    private $userRepository;

    public function __construct(){
        $this->userRepository = new UserRepository();
    }

    public function index(){
        if(Auth::check()){
            return Redirect::route('master');
        }else{
            return view('login');
        }
    }

    public function login(Request $request){
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $remember = $request->get('remember') != null ? true : false;

        if(Auth::attempt(['email' => $email, 'password' => $password], $remember)){
            return Redirect::route('master');
        }else{
            return Redirect::back()
                ->with(['error' => 'Incorrect email or password'])
                ->withInput(['email' => $request->get('email')]);
        }
    }

    public function logout(){
        if(Auth::check()){
            Auth::logout();
        }

        return Redirect::route('login');
    }

    public function registerIndex(){
        return view('register');
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
 
        if ($validator->fails()) {
            return Redirect::back()->with(["error" => "Fill in all fields"]);
        }

        $response = $this->userRepository->store($request);

        if(!$response['error']){
            return Redirect::route('login')->with(["message" => $response["message"], 'email' => $request->get('email')]);
        }else{
            return Redirect::back()->with(["error" => $response["message"]]);
        }
    }
}

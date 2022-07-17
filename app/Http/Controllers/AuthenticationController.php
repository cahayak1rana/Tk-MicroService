<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\RedirectResponse;


class AuthenticationController extends Controller
{
    public $errors = array('has' => array());
    //
    /**
     * Serve login view.
     *
     * @return response()
     */
    public function login()
    {
        return view('authentication.login');
    }  
      
    /**
     * Serve registration view. 
     *
     * @return response()
     */
    public function registration()
    {
        return view('authentication.registration');
    }

    /**
     * Serve registration view. 
     *
     * @return response()
     */
    public function reset()
    {
        return view('authentication.reset');
    }

      
    /**
     * Handle login post.
     *
     * @return response()
     */
    public function postLogin(Request $request)
    {
        // $this->validate($request, [
        //     'email' => 'required|email',
        //     'password' => 'required',
        // ]);
   
        $credentials = $request->only('email', 'password');
        // if (Auth::attempt($credentials)) {
        //     return redirect('dashboard')
        //                 ->withSuccess('You have Successfully logged in');
        // // }
  
        // return redirect("login")->withSuccess('Invalid credential detected');
    }
      
    /**
     * Handle registration. 
     *
     * @return response()
     */
    public function postRegistration(Request $request)
    {  
        // $this->validate($request, [
        //     'name' => 'required',
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required|min:6',
        // ]);
           
        $data = $request->all();
        $check = $this->create($data);

        $response = Http::withHeaders([])->post('http://lumen:8000/api/sendmail', [
            "subject" => "Welcome to our app",
            "message" => "<b>You are registered in our system.</b>",
            "from_email" => "gsk.player.12@gmail.com",
            "send_to" => [
                    array(
                        "send_to_email" => $data['email'],
                        "send_to_name" => "GSK"
                    )
                ],
            "driver_type" => "sendgrid",
            "queue"       =>  "false",
            "mail_type"   => "html"
        ]);

        echo json_encode(array('status' => 'User registered'));
         
        //return redirect("dashboard")->withSuccess('You have succesfully logged in.');
    }

    /**
     * Handle registration. 
     *
     * @return response()
     */
    public function postReset(Request $request)
    {  
        // $this->validate($request, [
        //     'email' => 'required|email|unique:users',
        // ]);

        $data = $request->all();
           
        $response = Http::withHeaders([])->post('http://lumen:8000/api/sendmail', [
            "subject" => "Reset password email",
            "message" => "<b>This is your token to reset password</b>",
            "from_email" => "gsk.player.12@gmail.com",
            "send_to" => [
                    array(
                        "send_to_email" => $data['email'],
                        "send_to_name" => "GSK"
                    )
                ],
            "driver_type" => "sendgrid",
            "queue"       =>  "false",
            "mail_type"   => "html"
        ]);

        echo json_encode(array('status' => 'Email sent'));
         
        //return redirect("login")->withSuccess('Password reset sent.');
    }
    
    /**
     * Show dashboard page. 
     *
     * @return response()
     */
    public function dashboard()
    {
        // if(Auth::check()){
        //     return view('authentication.dashboard');
        // }
  
      //  return redirect("login")->withSuccess('You do not have access to this page.');
    }
    
    /**
     *  Create user and insert data to the database.
     *
     * @return response()
     */
    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }
    
    /**
     * Logout of the ssytem.
     *
     * @return response()
     */
    public function logout() {
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }
}

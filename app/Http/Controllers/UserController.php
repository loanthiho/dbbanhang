<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

    public function tinhtong(Request $request)
    {
        $sum = $request->soA +  $request->soB;
        return view('sum', compact('sum'));
    }
    function computeArea(Request $request)
    {
        $a = $request->input('a');
        $b = $request->input('b');
        $e1 = $request->input('e1');
        $e2 = $request->input('e2');
        $e3 = $request->input('e3');
        $e4 = $request->input('e4');
        return view('areaOfShape')->with(['areaTriangle' => ($a + $b) / 2, 'areaQuadrangle' => ($e1 + $e2 + $e3 + $e4)]);
    }
    public function getData()
    {
        $client = new Client();
        $response = $client->request('GET', 'http://localhost:8000/api-data');
        $data = json_decode($response->getBody(), true);

        return view('api-data', ['data' => $data]);
    }

    public function Register(Request $request)
       {
          $input = $request->validate([
              'name' => 'required|string',
              'email' => 'required|email|unique:users',
              'password' => 'required',
              'c_password' => 'required|same:password'
          ]);
          
          // Tạo một bản ghi mới trong cơ sở dữ liệu để đăng ký người dùng
         $input['password'] = bcrypt($input['password']);
         User::create($input);
         echo '
         <script>
             alert("Đăng ký thành công. Vui lòng đăng nhập.");
             window.location.assign("login");
         </script> 
         ';
    }
    public function Login(Request $request)
    {
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('pass')
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            Session::put('user', $user);
            return redirect()->route('trangchu')->with('success', 'Đăng nhập thành công.');
        } else {
            return redirect()->route('login')->with('error', 'Đăng nhập thất bại.');
        }
    }
    public function Logout()
    {
        Session::forget('User');
        Session::forget('carts');
        return redirect('/trangchu');
    }
}

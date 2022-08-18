<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;

class FetchApiController extends Controller
{
    public function index()
    {

        $data = $this->fetch('https://60e1b5fc5a5596001730f1d6.mockapi.io/api/v1/users/users_1');

        foreach ($data as $d) {
            DB::table('users')->insert(['firstName' => $d->firstName, 'lastName' => $d->lastName , 'email' => $d->email , 'password' => bcrypt('1234') , 'avatar' => $d->avatar]);
        }

        $data = $this->fetch('https://60e1b5fc5a5596001730f1d6.mockapi.io/api/v1/users/user_2');

        foreach ($data as $d) {
            DB::table('users')->insert(['firstName' => $d->fName, 'lastName' => $d->lName , 'email' => $d->email , 'password' => bcrypt('1234') , 'avatar' => $d->picture]);
        }

        return 'true';

    }

    public function show()
    {
        $users = User::paginate(10);

        return response()->json($users);
    }

    public function search(Request $request)
    {
        $users = User::whereFirstname($request->search)->orWhere('lastname', $request->search)->orWhere('email', $request->search)->get();

        return response()->json($users);
    }


    public function fetch($url)
    {
        //  Initiate curl
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $result=curl_exec($ch);
        // Closing
        curl_close($ch);

        return json_decode($result);
    }
}

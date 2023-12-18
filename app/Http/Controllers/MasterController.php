<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MasterController extends Controller
{
    function toObject($array) {
        return json_decode(json_encode($array), false);
    }

    function current() {
        return $this->toObject([
            "nama" => Session::get("nama")[0],
            "username" => Session::get("username")[0]
        ]);
    }

    public function login() {
        return view("login");
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only("username", "password");
        $users = DB::table("users")
                    ->where("username", $credentials["username"])
                    ->where("password", $credentials["password"])
                    ->get();
        if (count($users) === 0) {
            Session::flush();
            return back();
        } else {
            Session::push("isLogin", true);
            Session::push("nama", $users[0]->nama);
            Session::push("username", $users[0]->username);
            return redirect()->route("dashboard");
        }
    }

    public function logout() {
        Session::flush();
        return redirect()->route("login");
    }

    public function dashboard() {
        $current = $this->current();
        return view("dashboard", compact("current"));
    }
}

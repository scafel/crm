<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index(){
        $view['user']  =   DB::table('user')->orderBy('addtime','desc')->limit(5)->get();
        return view("home.index",$view);
    }
}

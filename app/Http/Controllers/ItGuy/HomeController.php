<?php

namespace App\Http\Controllers\ItGuy;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class HomeController extends Controller
{


 

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {




        $menuItems = $request['menuItems'];


        // dd($menuItems);
        //    $lang =  App::getLocale();
        //    dd($lang);
        return view('home.index2', compact('menuItems'));
      
    }


}

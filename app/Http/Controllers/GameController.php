<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function queue($type){
        if($type == 'normal'){
            return view('game.normal');
        }

        if($type == 'ranked'){
            //return view('game.ranked');
        }
    }
}

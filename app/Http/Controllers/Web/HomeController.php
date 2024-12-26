<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function success(){
        return view('success');
    }

    public function cancel(){
        return [''];
    }
}

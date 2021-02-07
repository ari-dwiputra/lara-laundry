<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Transaction;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index');
    }

    public function show(){
    	$month = date('m');
        $year  = date('Y');
		$data['amount_transactions'] = Transaction::whereMonth('start_date', $month)->whereYear('start_date', $year)->sum('total');
		$data['transactions']        = Transaction::whereMonth('start_date', $month)->whereYear('start_date', $year)->count();
		$data['customers']           = Customer::count();

        $response = [
            'status'=> true,
            'code'=> '',
            'message'=> '',
            'data'=> $data
        ];
        return $response;
    }
}

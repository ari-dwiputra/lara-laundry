<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;


class ReportController extends Controller
{
    public function index()
    {
    	if (auth()->user()->can('Laporan')){
        	return view('report.index');
        }else{
            return view('errors.403');
        }
    }

    function getAllMonths(){

		$month_array = array();
		$transactions_dates = Transaction::orderBy( 'created_at', 'ASC' )->pluck( 'created_at' );
		$transactions_dates = json_decode( $transactions_dates );

		if ( ! empty( $transactions_dates ) ) {
			foreach ( $transactions_dates as $unformatted_date ) {
				// dd($unformatted_date);
				$date = new \DateTime( $unformatted_date );
				$month_no = $date->format( 'm' );
				$month_name = $date->format( 'M' );
				$month_array[ $month_no ] = $month_name;
			}
		}
		return $month_array;
	}

	function getMonthlyTransactionCount( $month, $year) {
		$monthly_transaction_count = Transaction::whereMonth( 'created_at', $month )->whereYear( 'created_at', $year )->get()->count();
		return $monthly_transaction_count;
	}

	function getMonthlyTransactionData(Request $request) {
		$year = $request['year'];
		$monthly_transaction_count_array = array();
		$month_array = $this->getAllMonths();
		$month_name_array = array();
		if ( ! empty( $month_array ) ) {
			foreach ( $month_array as $month_no => $month_name ){
				$monthly_transaction_count = $this->getMonthlyTransactionCount( $month_no, $year);
				array_push( $monthly_transaction_count_array, $monthly_transaction_count );
				array_push( $month_name_array, $month_name );
			}
		}

		$max_no = max( $monthly_transaction_count_array );
		$max = round(( $max_no + 10/2 ) / 10 ) * 10;
		$monthly_transaction_data_array = array(
			'months' => $month_name_array,
			'transaction_count_data' => $monthly_transaction_count_array,
			'max' => $max,
		);

		return $monthly_transaction_data_array;

    }
}

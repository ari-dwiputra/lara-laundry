<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\TransactionDetail;
use App\TransactionStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;

class TransactionController extends Controller
{
    public function index()
    {   
        if (auth()->user()->can('Transaksi')){
            return view('transaction.index');
        }else{
            return view('errors.403');
        }
    }

    public function getData(Request $request){
        $keyword = $request['searchkey'];

        $transactions = Transaction::with(['customer', 'lastTransactionStatuses'])
            ->offset($request['start'])
            ->limit(($request['length'] == -1) ? Transaction::count() : $request['length'])
            ->when($keyword, function ($query, $keyword) {
            return $query->where('no_nota', 'like', '%'. $keyword . '%');
            })
            ->latest()
            ->get();

        $transactionsCounter = Transaction::when($keyword, function ($query, $keyword) {
            return $query->where('no_nota', 'like', '%'. $keyword . '%');
        })
        ->count();
        $response = [
            'status' => true,
            'code' => '',
            'message' => '',
            'draw' => $request['draw'],
            'recordsTotal' => Transaction::count(),
            'recordsFiltered' => $transactionsCounter,
            'data' => $transactions,
        ];
        return $response;
    }

    public function create()
    {
        if (auth()->user()->can('Tambah Transaksi')){
            return view('transaction.create');
        }else{
            return view('errors.403');
        }
    }

    public function store(Request $request)
    {
        $today           = date("Y-m-d");
        $permitted_chars = '0123456789';
        $no_nota         = "TR".date("dmY").substr(str_shuffle($permitted_chars), 0, 5);
        try {
            DB::beginTransaction();
            $data = ['status' => false, 'code' => 'EC001', 'message' => 'Transaksi gagal ditambah'];
            $validator = Validator::make($request->all(), [
                'product_id.*' => 'required',
                'qty.*' => 'required',
            ]);
            if ($validator->fails()) {
                $data = ['status' => false, 'code' => 'EC002', 'message' => 'Produk dan berat wajib diisi'];
            } else {
                $createTransaction = Transaction::create([
                    'customer_id' => $request['customer_id'],
                    'no_nota'     => $no_nota,
                    'start_date'  => $today,
                    'end_date'    => $request['end_date'],
                    'total'       => $request['total'],
                    'note'        => $request['note'],
                    'created_by'  => auth()->user()->id,
                ]);
            if($createTransaction){
                $createTransactionStatus = TransactionStatus::create([
                    'transaction_id' => $createTransaction->id,
                    'status'         => 'Diproses',
                    'created_by'     => auth()->user()->id,
                ]);
                for($count = 0; $count < count($request['product_id']); $count++){
                    $createTransactionDetail = TransactionDetail::create([
                        'transaction_id' => $createTransaction->id,
                        'product_id'     => $request['product_id'][$count],
                        'qty'            => $request['qty'][$count],
                        'sub_total'      => $request['sub_total'][$count],
                        'created_by'     => auth()->user()->id,
                    ]);
                }
                if (count($request['product_id']) == $count) {
                    DB::commit();
                    $data = ['status' => true, 'code' => 'SC001', 'message' => 'Transaksi berhasil ditambah'];
                }
            }
            }
        } catch (\Exception $ex) {
            DB::rollback();
            $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
        }
        return $data;
    }

    public function show($id)
    {
        if (auth()->user()->can('Transaksi')){
            try {
                $data = ['status' => false, 'code' => 'EC001', 'message' => 'Transaksi gagal didapatkan'];
                $transaction = Transaction::with(['customer', 'transactionDetails', 'transactionDetails.product', 'transactionDetails.product.product_type', 'transactionStatuses', 'transactionStatuses.createdBy'])->where('id', $id)->first();
                if($transaction){
                    $data = ['status'=> true, 'code'=> '', 'message'=> 'Transaksi berhasil didapatkan', 'data'=> $transaction];
                }
            } catch (\Exception $ex) {
                $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
            }
            return $data;
        }else{
            return view('errors.403');
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $data = ['status' => false, 'code' => 'EC001', 'message' => 'Transaksi gagal diperbaharui'];
            
            $createTransactionStatus = TransactionStatus::create([
                'transaction_id' => $request['id'],
                'status'         => $request['status'],
                'created_by'     => auth()->user()->id,
            ]);
            if($createTransactionStatus){
                $data = ['status' => true, 'code' => 'SC001', 'message' => 'Transaksi berhasil diperbaharui'];
            }
        } catch (\Exception $ex) {
            $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
        }
        return $data;
    }

    public function downloadNota($id)
    {
        if (auth()->user()->can('Cetak Transaksi')){
            try {
                $transaction = Transaction::with(['customer', 'transactionDetails', 'transactionDetails.product', 'transactionDetails.product.product_type', 'transactionStatuses', 'transactionStatuses.createdBy'])->where('id', $id)->first();
                if($transaction){
                    $pdf = PDF::loadView('transaction.nota', compact('transaction'))->setPaper('A5','potrait');
                    return $pdf->stream('Nota.pdf');
                }else{
                    return $data = ['status' => false, 'code' => 'EC001', 'message' => 'Transaksi gagal didapatkan'];
                }
            } catch (\Exception $ex) {
                return $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
            }
        }else{
            return view('errors.403');
        }
    }
}

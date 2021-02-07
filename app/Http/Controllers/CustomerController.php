<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        if (auth()->user()->can('Pelanggan')){
            return view('customer.index');
        }else{
            return view('errors.403');
        }
    }

    public function getData(Request $request){
        $keyword = $request['searchkey'];

        $users = Customer::offset($request['start'])
            ->limit(($request['length'] == -1) ? Customer::count() : $request['length'])
            ->when($keyword, function ($query, $keyword) {
            return $query->where('name', 'like', '%'. $keyword . '%');
            })
            ->get();

        $usersCounter = Customer::when($keyword, function ($query, $keyword) {
            return $query->where('name', 'like', '%'. $keyword . '%');
        })
        ->count();
        $response = [
            'status' => true,
            'code' => '',
            'message' => '',
            'draw' => $request['draw'],
            'recordsTotal' => Customer::count(),
            'recordsFiltered' => $usersCounter,
            'data' => $users,
        ];
        return $response;
    }

    public function store(Request $request)
    {
        try {
            $data = ['status' => false, 'code' => 'EC001', 'message' => 'Pelanggan gagal ditambah'];
            if(Customer::where('email', $request['email'])->exists()){
                return $data = ['status' => false, 'code' => 'EC002', 'message' => 'Email sudah digunakan'];
            }
            $create = Customer::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'address' => $request['address'],
            ]);
            if($create){
                $data = ['status' => true, 'code' => 'SC001', 'message' => 'Pelanggan berhasil ditambah'];
            }
        } catch (\Exception $ex) {
            $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
        }
        return $data;
    }

    public function show($id)
    {
        if (auth()->user()->can('Pelanggan')){
            try {
                $data = ['status' => false, 'code' => 'EC001', 'message' => 'Pelanggan gagal didapatkan'];
                
                $customer = Customer::findOrFail($id);
                if($customer){
                    $data = ['status' => true, 'code' => 'SC001', 'message' => 'Pelanggan berhasil didapatkan', 'data'=> $customer];
                }
            } catch (\Exception $ex) {
                $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
            }
            return $data;
        }else{
            return view('errors.403');
        }
    }

    public function update(Request $request, Customer $customer)
    {
        try {
            $data = ['status' => false, 'code' => 'EC001', 'message' => 'Pelanggan gagal diperbaharui'];
            $update = Customer::where('id', $request['id'])->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'address' => $request['address'],
            ]);
            if($update){
                $data = ['status' => true, 'code' => 'SC001', 'message' => 'Pelanggan berhasil diperbaharui'];
            }
        } catch (\Exception $ex) {
            $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
        }
        return $data;
    }

    public function destroy($id)
    {
        try {
            $data = ['status' => false, 'code' => 'EC001', 'message' => 'Pelanggan gagal dihapus'];
            
            $delete = Customer::where('id', $id)->delete();
            if($delete){
                $data = ['status' => true, 'code' => 'SC001', 'message' => 'Pelanggan berhasil dihapus'];
            }
        } catch (\Exception $ex) {
            $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
        }
        return $data;
    }
}

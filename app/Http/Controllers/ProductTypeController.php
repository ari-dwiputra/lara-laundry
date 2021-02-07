<?php

namespace App\Http\Controllers;

use App\ProductType;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    public function index()
    {
        if (auth()->user()->can('Tipe Produk')){
            return view('product_type.index');
        }else{
            return view('errors.403');
        }
    }

    public function getData(Request $request){
        $keyword = $request['searchkey'];

        $users = ProductType::offset($request['start'])
            ->limit(($request['length'] == -1) ? ProductType::count() : $request['length'])
            ->when($keyword, function ($query, $keyword) {
            return $query->where('name', 'like', '%'. $keyword . '%');
            })
            ->get();

        $usersCounter = ProductType::when($keyword, function ($query, $keyword) {
            return $query->where('name', 'like', '%'. $keyword . '%');
        })
        ->count();
        $response = [
            'status' => true,
            'code' => '',
            'message' => '',
            'draw' => $request['draw'],
            'recordsTotal' => ProductType::count(),
            'recordsFiltered' => $usersCounter,
            'data' => $users,
        ];
        return $response;
    }

    public function store(Request $request)
    {
        try {
            $data = ['status' => false, 'code' => 'EC001', 'message' => 'Tipe produk gagal ditambah'];
            $create = ProductType::create([
                'name' => $request['name'],
            ]);
            if($create){
                $data = ['status' => true, 'code' => 'SC001', 'message' => 'Tipe produk berhasil ditambah'];
            }
        } catch (\Exception $ex) {
            $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
        }
        return $data;
    }

    public function show($id)
    {
        if (auth()->user()->can('Tipe Produk')){
            try {
                $data = ['status' => false, 'code' => 'EC001', 'message' => 'Tipe produk gagal didapatkan'];
                $product_type = ProductType::findOrFail($id);
                if ($product_type) {
                    $data = ['status'=> true, 'code'=> '', 'message'=> 'Tipe produk berhasil didapatkan', 'data'=> $product_type];
                }
            } catch (\Exception $ex) {
                $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
            }
            return $data;
        }else{
            return view('errors.403');
        }
    }

    public function update(Request $request)
    {
        try {
            $data = ['status' => false, 'code' => 'EC001', 'message' => 'Tipe produk gagal diperbaharui'];
            
            $update = ProductType::where('id', $request['id'])->update([
                'name' => $request['name'],
            ]);
            if($update){
                $data = ['status' => true, 'code' => 'SC001', 'message' => 'Tipe produk berhasil diperbaharui'];
            }
        } catch (\Exception $ex) {
            $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
        }
        return $data;
    }

    public function destroy($id)
    {
        try {
            $data = ['status' => false, 'code' => 'EC001', 'message' => 'Tipe produk gagal dihapus'];
            
            $delete = ProductType::where('id', $id)->delete();
            if($delete){
                $data = ['status' => true, 'code' => 'SC001', 'message' => 'Tipe produk berhasil dihapus'];
            }
        } catch (\Exception $ex) {
            $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
        }
        return $data;
    }
}

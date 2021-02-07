<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        if (auth()->user()->can('Produk')){
            return view('product.index');
        }else{
            return view('errors.403');
        }
    }

    public function getData(Request $request){
        $keyword = $request['searchkey'];

        $users = Product::with(['product_type'])
            ->offset($request['start'])
            ->limit(($request['length'] == -1) ? Product::count() : $request['length'])
            ->when($keyword, function ($query, $keyword) {
            return $query->where('name', 'like', '%'. $keyword . '%');
            })
            ->get();

        $usersCounter = Product::when($keyword, function ($query, $keyword) {
            return $query->where('name', 'like', '%'. $keyword . '%');
        })
        ->count();
        $response = [
            'status' => true,
            'code' => '',
            'message' => '',
            'draw' => $request['draw'],
            'recordsTotal' => Product::count(),
            'recordsFiltered' => $usersCounter,
            'data' => $users,
        ];
        return $response;
    }

    public function store(Request $request)
    {
        try {
            $data = ['status' => false, 'code' => 'EC001', 'message' => 'Produk gagal ditambah'];
            $create = Product::create([
                'name' => $request['name'],
                'product_type_id' => $request['product_type_id'],
                'price' => str_replace('.', '', $request['price']),
            ]);
            if($create){
                $data = ['status' => true, 'code' => 'SC001', 'message' => 'Produk berhasil ditambah'];
            }
        } catch (\Exception $ex) {
            $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
        }
        return $data;
    }

    public function show($id)
    {
        if (auth()->user()->can('Produk')){
            try {
                $data = ['status' => false, 'code' => 'EC001', 'message' => 'Produk gagal didapatkan'];
                $product = Product::with(['product_type'])->findOrFail($id);
                if ($product) {
                    $data = ['status'=> true, 'code'=> '', 'message'=> 'Produk berhasil didapatkan', 'data'=> $product];
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
            $data = ['status' => false, 'code' => 'EC001', 'message' => 'Produk gagal diperbaharui'];
            
            $update = Product::where('id', $request['id'])->update([
                'name' => $request['name'],
                'product_type_id' => $request['product_type_id'],
                'price' => str_replace('.', '', $request['price']),
            ]);
            if($update){
                $data = ['status' => true, 'code' => 'SC001', 'message' => 'Produk berhasil diperbaharui'];
            }
        } catch (\Exception $ex) {
            $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
        }
        return $data;
    }

    public function destroy($id)
    {
        try {
            $data = ['status' => false, 'code' => 'EC001', 'message' => 'Produk gagal dihapus'];
            
            $delete = Product::where('id', $id)->delete();
            if($delete){
                $data = ['status' => true, 'code' => 'SC001', 'message' => 'Produk berhasil dihapus'];
            }
        } catch (\Exception $ex) {
            $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
        }
        return $data;
    }
}

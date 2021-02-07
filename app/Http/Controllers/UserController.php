<?php

namespace App\Http\Controllers;

use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        if (auth()->user()->can('Pengguna')){
            return view('user.index');
        }else{
            return view('errors.403');
        }
    }
    public function getData(Request $request){
		$keyword = $request['searchkey'];

        $users = User::select()
            ->with(['roles'])
        	->offset($request['start'])
        	->limit(($request['length'] == -1) ? User::count() : $request['length'])
        	->when($keyword, function ($query, $keyword) {
            return $query->where('name', 'like', '%'. $keyword . '%');
            })
        	->get();

        $usersCounter = User::select()
        ->when($keyword, function ($query, $keyword) {
            return $query->where('name', 'like', '%'. $keyword . '%');
            })
        ->count();
        $response = [
            'status' => true,
            'code' => '',
            'message' => '',
            'draw' => $request['draw'],
            'recordsTotal' => User::count(),
            'recordsFiltered' => $usersCounter,
            'data' => $users,
        ];
        return $response;
    }
    public function store(Request $request)
    {
        $fileName = Str::random(20);
        $path = 'images/user/';
        try {
            DB::beginTransaction();
            $data = ['status' => false, 'code' => 'EC001', 'message' => 'Pengguna gagal ditambah'];
            if(User::where('email', $request['email'])->exists()){
                return $data = ['status' => false, 'code' => 'EC002', 'message' => 'Email sudah digunakan'];
            }
            $validator = Validator::make($request->all(), [
                'photo' => 'image|mimes:jpg,jpeg,png|max:2048',
            ], [
                'photo' => 'File tidak boleh lebih dari 2MB, dengan format jpg, jpeg, png',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 'EC001', 'message' => 'File tidak boleh lebih dari 2MB, dengan format jpg, jpeg, png']);
            }
            if($request->file('extension') != null){
                $extension = $request->file('photo')->extension();
                $photoName = $fileName.'.'.$extension;
                Storage::disk('public')->putFileAs($path,$request->file('photo'), $fileName.".".$extension);
            }else{
                $photoName = null;
            }
            $user           = new User;
            $user->name     = $request['name'];
            $user->email    = $request['email'];
            $user->photo    = $photoName;
            $user->password = Hash::make($request['password']);
            $user->save();

            $user->assignRole($request->role_id);

            if($user){
                DB::commit();
                $data = ['status' => true, 'code' => 'SC001', 'message' => 'Pengguna berhasil ditambah'];
            }
        } catch (\Exception $ex) {
            DB::rollback();
            $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
        }
        return $data;
    }
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = ['status' => false, 'code' => 'EC001', 'message' => 'Pengguna gagal diperbaharui'];
            if ( auth()->user()->email === $request['email']) {
                if(User::where('email', $request['email'])->exists()){
                    return $data = ['status' => false, 'code' => 'EC002', 'message' => 'Email sudah digunakan'];
                }
            }
            
            $user           = User::find($request->id);
            $user->name     = $request->name;
            $user->email    = $request->email;
            $user->save();
            $user->syncRoles($request->role_id);

            if($user){
                DB::commit();
                $data = ['status' => true, 'code' => 'SC001', 'message' => 'Pengguna berhasil diperbaharui'];
            }
        } catch (\Exception $ex) {
            DB::rollback();
            $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
        }
        return $data;
    }
    public function changePassword(Request $request)
    {
       try {
            $data = ['status' => false, 'code' => 'EC001', 'message' => 'Password gagal diperbaharui'];
            
            $update = User::where('id', $request['id'])->update([
                'password' => Hash::make($request['password']),
            ]);
            if($update){
                $data = ['status' => true, 'code' => 'SC001', 'message' => 'Password berhasil diperbaharui'];
            }
        } catch (\Exception $ex) {
            $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
        }
        return $data; 
    }
    public function changeProfile(Request $request)
    {
        $fileName = Str::random(20);
        $path = 'images/user/';
        try {
            $data = ['status' => false, 'code' => 'EC001', 'message' => 'Foto profile gagal diperbaharui'];
            $validator = Validator::make($request->all(), [
                'photo' => 'image|mimes:jpg,jpeg,png|max:2048',
            ], [
                'photo' => 'File tidak boleh lebih dari 2MB, dengan format jpg, jpeg, png',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 'EC001', 'message' => 'File tidak boleh lebih dari 2MB, dengan format jpg, jpeg, png']);
            }

            $extension = $request->file('photo')->extension();
            $photoName = $fileName.'.'.$extension;

            Storage::disk('public')->putFileAs($path,$request->file('photo'), $fileName.".".$extension);
            $update = User::where('id', $request['id'])->update([
                'photo' => $photoName,
            ]);

            if($update){
                $data = ['status' => true, 'code' => 'SC001', 'message' => 'Foto profile berhasil diperbaharui'];
            }
        } catch (\Exception $ex) {
            $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
        }
        return $data; 
    }
    public function show($id)
    {
        if (auth()->user()->can('Pengguna')){
            try {
                $data = ['status' => false, 'code' => 'EC001', 'message' => 'Pengguna gagal didapatkan'];
                $user = User::with(['roles'])->where('id', $id)->first();
                if ($user) {
                    $data = ['status'=> true, 'code'=> 'EEC001', 'message'=> 'Pengguna berhasil didapatkan','data'=> $user];
                }
            } catch (\Exception $ex) {
                $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
            }
            return $data;
        }else{
            return view('errors.403');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $data = ['status' => false, 'code' => 'EC001', 'message' => 'Pengguna gagal dihapus'];
            
            $user = User::find($id);
            $user->removeRole($user->roles->first());
            $user->delete();
            if($user){
                DB::commit();
                $data = ['status' => true, 'code' => 'SC001', 'message' => 'Pengguna berhasil dihapus'];
            }
        } catch (\Exception $ex) {
            $data = ['status' => false, 'code' => 'EEC001', 'message' => 'Ups, Terjadi kesalahan sistem'.$ex];
        }
        return $data;
    }
}

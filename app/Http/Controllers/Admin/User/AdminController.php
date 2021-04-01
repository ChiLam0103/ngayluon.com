<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Requests\AdminRequest;
use App\Models\PartnerAPI;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    protected $breadcrumb = ['Quản lý admin','admins'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.elements.users.admin.index', ['active' => 'admins', 'breadcrumb' => $this->breadcrumb]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.elements.users.admin.add', ['active' => 'admins', 'breadcrumb' => $this->breadcrumb]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(AdminRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = new User();
            $data->uuid = $request->uuid;
            $data->name = $request->name;
            $data->password = Hash::make($request->password);
            $data->email = $request->email;
            $data->home_number = $request->home_number;
            $data->phone_number = $request->phone_number;
            $data->province_id = $request->province_id;
            $data->district_id = $request->district_id;
            $data->ward_id = $request->ward_id;
            $data->birth_day = $request->birth_day;
            $data->id_number = $request->id_number;
            $data->bank_account = $request->bank_account;
            $data->bank_account_number = $request->bank_account_number;
            $data->bank_name = $request->bank_name;
            $data->bank_branch = $request->bank_branch;
            $data->role = 'admin';
            if ($request->hasFile('avatar')) {
                $file = $request->avatar;
                $filename = date('Ymd-His-') . $file->getFilename() . '.' . $file->extension();
                $filePath = 'img/avatar/';
                $movePath = public_path($filePath);
                $file->move($movePath, $filename);
                $data->avatar = $filePath . $filename;
            }
            $data->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
        return redirect(url('admin/admins'))->with('success', 'Thêm mới admin thành công');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = DB::table('users')->where('users.id', $id)->first();
        return view('admin.elements.users.admin.add', ['active' => 'admin','user' => $admin,  'breadcrumb' => $this->breadcrumb]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = User::find($id);
            $data->name = $request->name;
            if ($request->password != null) {
                $data->password = Hash::make($request->password);
            }
            $data->email = $request->email;
            $data->home_number = $request->home_number;
            $data->phone_number = $request->phone_number;
            $data->province_id = $request->province_id;
            $data->district_id = $request->district_id;
            $data->ward_id = $request->ward_id;
            $data->birth_day = $request->birth_day;
            $data->id_number = $request->id_number;
            $data->bank_account = $request->bank_account;
            $data->bank_account_number = $request->bank_account_number;
            $data->bank_name = $request->bank_name;
            $data->bank_branch = $request->bank_branch;
            $data->role = 'admin';
            if ($request->hasFile('avatar')) {
                $file = $request->avatar;
                $filename = date('Ymd-His-') . $file->getFilename() . '.' . $file->extension();
                $filePath = 'img/avatar/';
                $movePath = public_path($filePath);
                $file->move($movePath, $filename);
                $data->avatar = $filePath . $filename;
            }
            $data->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
        return redirect(url('admin/admins'))->with('success', 'Chỉnh sửa admin thành công');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            User::find($id)->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
        return redirect(url('admin/admins'))->with('delete', 'Xóa admin thành công');
    }
}

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
        return view('admin.elements.users.admin.index', ['active' => 'admin', 'breadcrumb' => $this->breadcrumb]);
    }
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
        return redirect(url('admin/admin'))->with('delete', 'Xóa admin thành công');
    }
}

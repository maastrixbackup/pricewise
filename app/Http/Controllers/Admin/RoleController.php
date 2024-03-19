<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
	protected string $guard = 'admin';
	public function guard()
	{
		return Auth::guard($this->guard);
	}
	function __construct()
	{
		$this->middleware('auth:admin');
		$this->middleware('permission:role-list', ['only' => ['index', 'store']]);
		$this->middleware('permission:role-create', ['only' => ['create', 'store']]);
		$this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
		$this->middleware('permission:role-delete', ['only' => ['destroy']]);

		$role_list = Permission::get()->filter(function ($item) {
			return $item->name == 'role-list';
		})->first();
		$role_create = Permission::get()->filter(function ($item) {
			return $item->name == 'role-create';
		})->first();
		$role_edit = Permission::get()->filter(function ($item) {
			return $item->name == 'role-edit';
		})->first();
		$role_delete = Permission::get()->filter(function ($item) {
			return $item->name == 'role-delete';
		})->first();


		if ($role_list == null) {
			Permission::create(['name' => 'role-list']);
		}
		if ($role_create == null) {
			Permission::create(['name' => 'role-create']);
		}
		if ($role_edit == null) {
			Permission::create(['name' => 'role-edit']);
		}
		if ($role_delete == null) {
			Permission::create(['name' => 'role-delete']);
		}
	}

	public function index(Request $request)
	{
		$roles = Role::all();
		return view('admin.roles.index', compact('roles'));
	}

	public function create()
	{
		$permissions = Permission::get();
		return view('admin.roles.create', compact('permissions'));
	}

	public function store(Request $request)
	{
		$rules = [
			'name' 					=> 'required|unique:roles,name',
			'code' 					=> 'required|unique:roles,code',
			'permission' 			=> 'required',
		];

		$messages = [
			'name.required'    		=> __('The name field is required!'),
			'name.unique'    		=> __('Name already exists!'),
			'code.required'    		=> __('The code field is required!'),
			'code.unique'    		=> __('Code already exists!'),
			'permission.required'   => __('The permission field is required!'),
		];

		$this->validate($request, $rules, $messages);

		try {
			$role = Role::create([
				'name' => $request->input('name'),
				'code' => $request->input('code'),
				'slug' => $request->input('code')
			]);
			$role->syncPermissions($request->input('permission'));
			Toastr::success(__('Role added successfully!'));
			return redirect()->route('admin.roles.index');
		} catch (Exception $e) {
			Toastr::error(__('There is an error! Please try later!'));
			return redirect()->route('admin.roles.index');
		}
	}

	public function edit($id)
	{
		$role = Role::find($id);
		$permissions = Permission::all();
		return view('admin.roles.edit', compact('role', 'permissions'));
	}

	public function update(Request $request, $id)
	{
		$rules = [
			'name' 					=> 'required|unique:roles,name,' . $id,
			'code' 					=> 'required|unique:roles,code,' . $id,
			'permission' 			=> 'required',
		];

		$messages = [
			'name.required'    		=> __('The name field is required!'),
			'name.unique'    		=> __('Name already exists!'),
			'code.required'    		=> __('The code field is required!'),
			'code.unique'    		=> __('Code already exists!'),
			'permission.required'   => __('The permission field is required!'),
		];

		$this->validate($request, $rules, $messages);

		try {
			$role = Role::find($id);
			$role->name = $request->input('name');
			$role->code = $request->input('code');
			$role->slug = $request->input('code');
			$role->save();
			$role->syncPermissions($request->input('permission'));

			Toastr::success(__('Role updated successfully!'));
			return redirect()->route('admin.roles.index');
		} catch (Exception $e) {
			Toastr::error(__('There is an error! Please try later!'));
			return redirect()->route('admin.roles.index');
		}
	}

	public function destroy()
	{
		$id = request()->input('id');
		$allrole = Role::all();
		$countallrole = $allrole->count();

		if ($countallrole <= 1) {
			Toastr::error(__('Last Role can not be deleted!'));
			return redirect()->route('users.index');
		} else {
			$getrole = Role::find($id);
			try {
				Role::find($id)->delete();
				return back()->with(Toastr::error(__('Role deleted successfully!')));
			} catch (Exception $e) {
				$error_msg = Toastr::error(__('There is an error! Please try later!'));
				return redirect()->route('admin.roles.index')->with($error_msg);
			}
		}
	}
}

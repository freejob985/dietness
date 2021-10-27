<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admins;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = admins::all();
        return view('admin.admin.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        $locale = app()->getLocale();
        return view('admin.admin.create', compact('permissions', "locale"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'email' => 'required|string|unique:admins,email',
            'password' => 'required|string|min:3',
            'verify_password' => 'required|same:password',
            'permissions' => 'required|array',
            'permissions.*' => 'required|exists:permissions,id',
        ];
        $request->validate($rules);

        $data = ['name' => $request->name, 'email' => $request->email,'password' => Hash::make($request->password)];
        $admin = admins::create($data);
        $admin->permissions()->sync($request->permissions);
        return redirect(route('admins.index'))->with('message',__('admin.admins.added'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\admins  $admins
     * @return \Illuminate\Http\Response
     */
    public function edit(admins $admin)
    {
        $permissions = Permission::all();
        $locale = app()->getLocale();
        $admin_permissions = $admin->permissions()->pluck('id')->toArray();
        return view('admin.admin.edit', compact('admin', 'permissions', 'locale', 'admin_permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\admins  $admins
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, admins $admin)
    {
        $rules = [
            'name' => 'required|string',
            'email' => 'required|string|unique:admins,email,' . $admin->id,
            'password' => 'nullable|string|min:3',
            'verify_password' => 'required_with:password|same:password',
            'permissions' => 'required|array',
            'permissions.*' => 'required|exists:permissions,id',
        ];
        $request->validate($rules);
        $data = ['name' => $request->name, 'email' => $request->email];
        if($request->password) {
            $data['password'] = Hash::make($request->password);
        }
        $admin->update($data);
        $admin->permissions()->sync($request->permissions);
        return redirect(route('admins.index'))->with('message',__('admin.admins.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\admins  $admins
     * @return \Illuminate\Http\Response
     */
    public function destroy(admins $admin)
    {
        $admin->delete();
        return redirect(route('admins.index'))->with('message',__('admin.admins.deleted'));
    }
}

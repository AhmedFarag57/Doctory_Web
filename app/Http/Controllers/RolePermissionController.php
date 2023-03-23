<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    /**
     * ROLES INDEX
     */
    public function rolesIndex()
    {
        $roles = Role::with('permissions')->get();
        return view('backend.admin.roles.index')->with('roles', $roles);
    }

    /**
     * ROLES CREATE
     */
    public function rolesCreate()
    {
        $permissions = Permission::latest()->get();
        return view('backend.admin.roles.create')->with('permissions', $permissions);
    }

    /**
     * ROLES STORE
     */
    public function rolesStore(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:roles'
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->givePermissionTo($request->selectedpermissions);

        return redirect('/roles-permissions');
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
     * ROLES EDIT
     */
    public function rolesEdit($id)
    {
        $role = Role::with('permissions')->find($id);
        $permissions = Permission::latest()->get();

        return view('backend.admin.roles.edit')->with([
            'role' => $role,
            'permissions' => $permissions
        ]);
    }

    /**
     * ROLES UPDATE
     */
    public function rolesUpdate(Request $request, $id) {

        $this->validate($request, [
            'name' => 'required|string|max:255|unique:roles,name,'.$id
        ]);

        $role = Role::findById($id);
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->selectedpermissions);

        return redirect('/roles-permissions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * PERMISSIONS CREATE
     */
    public function permissionsCreate() {
        
        $roles = Role::latest()->get();
        $permissions = Permission::latest()->get();

        return view('backend.admin.permissions.create')->with([
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }

    /**
     * PERMISSIONS STORE
     */
    public function permissionsStore(Request $request) {

        $this->validate($request, [
            'name' => 'required|string|max:255|unique:permissions'
        ]);

        $permission = Permission::create(['name' => $request->name]);
        $permission->assignRole($request->selectedroles);

        $roles = Role::latest()->get();
        $permissions = Permission::latest()->get();

        return view('backend.admin.permissions.create')->with([
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }

    /**
     * PERMISSIONS EDIT
     */
    public function permissionsEdit($id) {

        $permission = Permission::with('roles')->find($id);
        $roles = Role::latest()->get();

        return view('backend.admin.permissions.edit')->with([
            'roles' => $roles,
            'permissions' => $permission
        ]);
    }

    /**
     * PERMISSIONS UPDATE
     */
    public  function permissionsUpdate(Request $request, $id) {

        $this->validate($request, [
            'name' => 'required|string|max:255|unique:permissions,name,'.$id
        ]);

        $permission = Permission::findById($id);
        $permission->update(['name' => $request->name]);
        $permission->syncRoles($request->selectedroles);

        return redirect('/roles-permissions');
    }
}

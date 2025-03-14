<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{
// Creating a role

public function store(Request $request)
{
    $role = Role::create(['name' => $request->name]);
    return response()->json($role);
}

// Updating a role
public function update(Request $request, Role $role)
{
    $role->update(['name' => $request->name]);
    return response()->json($role);
}

// Deleting a role
public function destroy(Role $role)
{
    $role->delete();
    return response()->json(null, 204);
}
}
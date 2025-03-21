<?php

namespace App\Http\Controllers;
use App\Traits\HandleErrorTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{
    use HandleErrorTrait;

    public function store(Request $request)
    {
        try {
            $role = Role::create(['name' => $request->name]);
            return response()->json($role);
        } catch (\Throwable $th) {
            return $this->handleError($th);
        }

    }

    // Updating a role
    public function update(Request $request, Role $role)
    {
        try {
            $role->update(['name' => $request->name]);
            return response()->json($role);
        } catch (\Throwable $th) {
            return $this->handleError($th);
        }

    }

    // Deleting a role
    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return $this->handleError($th);
        }

    }
}
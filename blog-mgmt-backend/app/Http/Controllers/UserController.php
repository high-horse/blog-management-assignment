<?php
namespace App\Http\Controllers;
use App\Traits\HandleErrorTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class UserController extends Controller
{

    use HandleErrorTrait;
    public function __construct()
    {

    }

    public function createUsers(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'name' => 'required',
            'role' => 'required',
            'password' => 'required',
        ]);
        try {
            $user = User::create([
                'email' => $request->email,
                'name' => $request->name,
                'password' => bcrypt($request->password),
            ])
                ->assignRole($request->role);

            return response()->json([
                'message' => 'User Created Successfully',
                'status' => true,
            ]);
        } catch (\Throwable $th) {
           return $this->handleError($th);
        }
    }

    public function getUsers(Request $request)
    {
        try {
            if (!$request->user()->hasRole('admin')) {
                throw new \Exception("Unauthorized ");
            }
            // Select users with their roles
            $users = User::with('roles')->select('id', 'name', 'email')->get();

            $data = $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->roles->pluck('name')->first() // Get the first role name
                ];
            });
            return response()->json([
                'status' => true,
                'users' => $data
            ]);

        } catch (\Throwable $th) {
            return $this->handleError($th);
        }
    }

    public function deleteUsers(Request $request, $id)
    {
        try {
            if (auth()->user()->id == $id) {
                throw new \Exception('Cannot delete self');
            }

            $user = User::findOrFail($id);
            $user->update([
                'email' => Carbon::now() . '' . $user->email,
            ]);
            $user->delete();

            return response()->json([
                'status' => true,
                'message' => 'User deleted Successfully',
            ]);


        } catch (\Throwable $th) {
            return $this->handleError($th);
        }
    }

    public function getUserDetail($id)
    {
        try {
            // Select users with their roles
            $user = User::with('roles')->select('id', 'name', 'email')->findOrFail($id);
            $user['role'] = $user->roles->pluck('name')->first();
            return response()->json([
                'status' => true,
                'user' => $user
            ]);

        } catch (\Throwable $th) {
            return $this->handleError($th);
        }
    }

    public function getResourceRoles(Request $request)
    {
        try {
            $roles = Role::get()->pluck('name')->toArray();
            return response()->json([
                'status' => true,
                'roles' => $roles,
            ]);
        } catch (\Throwable $th) {
            return $this->handleError($th);
        }
    }

    public function updateUserDetail(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'role' => 'required'
        ]);

        try {
            $user = User::findOrFail($id);
            $user->update([
                'email' => $request->email,
                'name' => $request->name,
            ]);

            // Sync the user's role
            $user->syncRoles($request->input('role'));

            return response()->json([
                'status' => true,
                'message' => 'User updated successfully',
                // 'user' => $user
            ]);

        } catch (\Throwable $th) {
            return $this->handleError($th);
        }
    }
}
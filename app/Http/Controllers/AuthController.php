<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    // Register a new user
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6', // password_confirmation field required
            'role' => 'required|in:admin,team_manager,referee',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

         // Delete all existing tokens for this user
        $user->tokens()->delete();

        // Issue token
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => [
                'id'         => $user->id,
                'name'       => $user->name,
                'role'       => $user->role
            ]
        ]);
    }

    public function logout(Request $request)
    {
        // Delete current access token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function getUsers()
    {
        try {
            $users = User::select(['id', 'name', 'email', 'role'])->get();
            return $this->successResponse('Users fetched successfully', $users);
        } catch (\Throwable $th) {
            Log::error("message: " . $th->getMessage() . " line: " . $th->getLine());
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function getRoles()
    {
        try {
            $result = DB::select("SELECT COLUMN_TYPE 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_NAME = 'users' 
            AND COLUMN_NAME = 'role'");

            if (!empty($result)) {
                $enumValues = $result[0]->COLUMN_TYPE;
                preg_match('/enum\((.*)\)/', $enumValues, $matches);

                $roles = explode(',', $matches[1]);
                $roles = array_map(function ($role) {
                            return trim($role, "'");
                        }, $roles);
                                
                return $this->successResponse('Roles fetched successfully', $roles);
            }
            else{
                return $this->successResponse('Roles fetched successfully', []);
            }
        } catch (\Throwable $th) {
            Log::error("message: " . $th->getMessage() . " line: " . $th->getLine());
            return $this->errorResponse($th->getMessage(), 500);
        }
        
    }

    public function getManagers(Request $request){          // get only managers who are not already assigned to any team
        try {
            $role = $request->query('role');

            $query = User::query();

            if ($role) {
                $query->where('role', $role);
            }

            // Only users not assigned to any team
            // $query->whereNull('team_id');

            $users = $query->get(['id', 'name']);

            return $this->successResponse('Managers fetched successfully', $users);
        
        } catch (\Throwable $th) {
            Log::error("message: " . $th->getMessage() . " line: " . $th->getLine());
            return $this->errorResponse($th->getMessage(), 500);
        }
        
    }

    public function getByRole(Request $request)
    {
        $role = $request->query('role');

        if (!$role) {
            return response()->json([
                'status' => false,
                'message' => 'Role query parameter is required.',
            ], 400);
        }

        $users = User::where('role', $role)
                     ->get(['id', 'name', 'email']);

        return response()->json([
            'status' => true,
            'data' => $users,
        ]);
    }


}

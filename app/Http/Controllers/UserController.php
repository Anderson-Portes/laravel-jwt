<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware([
            'auth:api',
            'check_is_admin'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json([
            'users' => User::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        return response()->json([
            'user' => User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => isset($request->is_admin) ? $request->is_admin : false,
                'email_verified_at' => now()
            ])
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $user = User::find($id);

        if (!$user) {
            return response([
                'error' => 'User not found!'
            ], 404);
        }

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response([
                'error' => 'User not found!'
            ], 404);
        }

        $emailExists = User::firstWhere("email", $request->email);

        if ($emailExists && $emailExists->id != $id) {
            return response([
                'error' => 'Email has been taken!'
            ], 404);
        }

        $user->update($request->all());

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $user = User::find($id);

        if (!$user) {
            return response([
                'error' => 'User not found!'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully!'
        ]);
    }
}

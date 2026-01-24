<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     *
     * @return Response
     */
    public function show(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string|min:6'
        ]);

        $user = User::where('email', $request->get('email'))->first();

        if(!$user) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        if(Hash::check($request->get('password'), $user->password)) {
           return response()->json(['message' => 'User get', 'user' => $user], 201);
        }

        return response()->json(['message' => 'Password wrong'], 404);
    }

    /**
     * Post a new user.
     *
     * @return Response
     */
    public function post(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'level' => '1',
            'acc' => '0',
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    public function delete($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return response()->json(['message' => 'Item deleted successfully']);
        } else {
            return response()->json(['message' => 'Item not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);

        $allInput = $request->all();

        $user = User::find($id);
        $user->update($allInput);

        return response()->json(['status' => 'success', 'data' => $allInput]);
    }

    public function all()
    {
        $users = User::all();

        return response()->json($users);
    }
}

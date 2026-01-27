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
            'name' => 'required|string|max:30',
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);

        $userExists = User::where('email', $request->get('email'))->first();

        if($userExists) {
            return response()->json(['message' => 'User loaded successfully', 'user' => $userExists]);
        }

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
    public function all()
    {
        $users = User::all();

        return response()->json($users);
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'password' => 'required|string|min:6',
        ]);

        $user = User::find($id);

        if($user) {
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json(['message' => 'User password updated successfully', 'user' => $user]);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
    public function level(Request $request, $id)
    {
        $this->validate($request, [
            'level' => 'required|string',
            'acc' => 'required|string',
        ]);

        $user = User::find($id);

        if ($user) {
            $acc = $request->input('acc');
            $level = $request->input('level');

            // TODO: All leves
            $levels = [
                '30',
                '60',
                '100',
                '130',
                '200',
                '320',
                '400',
                '520',
                '670',
                '900',
                '1090',
                '1250',
                '1510',
                '1750'
            ];

            $act = (int)$level;

            $levelAct = $levels[$act];
            $levelNext = $levels[++$act];

            $dif = $levelAct - $levelNext;

            if($dif < $acc) {
                $user->level = (string)(++$act);
                $user->acc = (string)($acc - $dif);
                $user->save();
            }

            return response()->json(['message' => 'User updated successfully', 'user' => $user], 201);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
}

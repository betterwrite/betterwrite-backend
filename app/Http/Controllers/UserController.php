<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show(Request $request): JsonResponse
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
    public function login(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);

        $userExists = User::where('email', $request->get('email'))->first();

        if($userExists && Hash::check($request->get('password'), $userExists->password)) {
            return response()->json(['message' => 'User loaded successfully', 'user' => $userExists]);
        }

        if($userExists) {
            return response()->json(['message' => 'Password is wrong'], 403);
        } else {
            return response()->json(['message' => 'User does not exist'], 404);
        }
    }
    public function register(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);

        $userExists = User::where('email', $request->get('email'))->first();

        if($userExists) {
            return response()->json(['message' => 'User exists'], 403);
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'level' => '1',
            'acc' => '0',
        ]);

        return response()->json(['message' => 'User create successfully', 'user' => $user], 201);
    }

    public function delete($id): JsonResponse
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return response()->json(['message' => 'Item deleted successfully']);
        } else {
            return response()->json(['message' => 'Item not found'], 404);
        }
    }
    public function all(): JsonResponse
    {
        $users = User::all();

        return response()->json($users);
    }
    public function update(Request $request, $id): JsonResponse
    {
        $this->validate($request, [
            'prevPassword' => 'required|string|min:6',
            'nextPassword' => 'required|string|min:6',
        ]);

        $user = User::find($id);

        if($user && Hash::check($request->get('prevPassword'), $user->password)) {
            $user->password = Hash::make($request->nextPassword);
            $user->save();
            return response()->json(['message' => 'User password updated successfully', 'user' => $user]);
        } else {
            return response()->json(['message' => 'User not found or wrong password'], 404);
        }
    }
    public function level(Request $request, $id): JsonResponse
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
                '0',
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

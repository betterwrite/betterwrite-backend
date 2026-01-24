<?php

namespace App\Http\Controllers;

use App\Models\Library;
use App\Models\User;
use App\Models\Vault;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    /**
     * Retrieve the user for the given ID.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return Library::findOrFail($id);
    }

    /**
     * Post a new user.
     *
     * @return Response
     */
    public function post(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'content' => 'required'
        ]);

        $user = User::find($id);

        if(!$user) {
            return response()->json(['status' => 'fail'], 401);
        }

        $library = Library::create([
            'title' => $request->input('title'),
            'user_id' => $id
        ]);

        $vault = Vault::create([
            'content' => $request->input('content'),
            'library_id' => $library->id
        ]);

        return response()->json(['message' => 'Items created successfully', 'library' => $library, 'vault' => $vault], 201);
    }

    public function delete($id)
    {
        $library = Library::find($id);

        if ($library) {
            $library->delete();
            return response()->json(['message' => 'Item deleted successfully']);
        } else {
            return response()->json(['message' => 'Item not found'], 404);
        }
    }
}

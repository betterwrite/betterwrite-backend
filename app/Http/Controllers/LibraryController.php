<?php

namespace App\Http\Controllers;

use App\Models\Library;
use App\Models\User;
use App\Models\Vault;
use Illuminate\Http\Request;

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
       $library = Library::find($id);
       if($library) {
            $vault = Vault::find($library->id);

            if($vault) {
                return response()->json(['message' => 'Library items and content!', 'vault' => $vault, 'library' => $library], 200);
            } else {
                return response()->json(['status' => 'fail'], 401);
            }
       } else {
            return response()->json(['status' => 'fail'], 401);
       }
    }

    public function all($id) {
        $libraries = Library::where('user_id', $id)->get();

        if(!$libraries) {
            return response()->json(['message' => 'Libraries not found'], 404);
        }

        return response()->json($libraries);
    }

    /**
     * Post a new library or update.
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

        $existsLibraries = Library::where('user_id', $id);

        foreach ($existsLibraries as $existsLibrary) {
            if($existsLibrary->title === $request->input('title')) {
                $existsVault = Vault::where('library_id', $existsLibrary->id)->first();

                if(!$existsVault) {
                    return response()->json(['status' => 'fail'], 401);
                }

                $existsVault->content = $request->input('content');
                $existsVault->save();

                return response()->json(['message' => 'Items updated successfully', 'library' => $existsLibrary, 'vault' => $existsVault], 201);
            }
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
            $payload = $library;
            $library->delete();
            return response()->json(['message' => 'Item deleted successfully', 'library' => $payload]);
        } else {
            return response()->json(['message' => 'Item not found'], 404);
        }
    }
}

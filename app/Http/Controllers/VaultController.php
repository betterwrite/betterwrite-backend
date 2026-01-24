<?php

namespace App\Http\Controllers;

use App\Models\Vault;
use Illuminate\Http\Request;

class VaultController extends Controller
{
    /**
     * Retrieve the user for the given ID.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return Vault::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
       $this->validate($request, [
            'content' => 'required',
        ]);

        $allInput = $request->all();

        $vault = Vault::find($id);
        if($vault) {
            $vault->update($allInput);
            return response()->json(['status' => 'success', 'data' => $allInput]);
        } else {
            return response()->json(['message' => 'Item not found'], 404);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Vault;
use Illuminate\Http\Request;

class VaultController extends Controller
{
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

<?php

namespace App\Http\Controllers;

use App\Models\ClientRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class ClientRoleController extends Controller
{
    public function index()
    {
        $cacheKey = 'client_roles';
        $data = Cache::remember($cacheKey, 20, function () {
            return ClientRole::all();
        });
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:client_roles',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role = ClientRole::create($request->all());

        return response()->json($role, 201);
    }

    public function show($id)
    {
        $role = ClientRole::find($id);

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $role = ClientRole::find($id);

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:client_roles,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role->update($request->all());

        return response()->json($role);
    }

    public function destroy($id)
    {
        $role = ClientRole::find($id);

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        if ($role->users()->count() > 0) {
            return response()->json(['message' => 'Cannot delete role with users'], 422);
        }

        $role->delete();

        return response()->json(null, 204);
    }
}

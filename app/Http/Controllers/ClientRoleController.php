<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRoleRequest;
use App\Http\Requests\UpdateClientRoleRequest;
use App\Models\ClientRole;
use Illuminate\Support\Facades\Cache;

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

    public function store(StoreClientRoleRequest $request)
    {
        $role = ClientRole::create($request->validated());

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

    public function update(UpdateClientRoleRequest $request, $id)
    {
        $role = ClientRole::find($id);

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $role->update($request->validated());

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

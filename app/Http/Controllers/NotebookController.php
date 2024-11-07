<?php

namespace App\Http\Controllers;

use App\Models\Notebook;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class NotebookController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 10);  
    
        $notebooks = Notebook::paginate($perPage);
    
        return response()->json($notebooks);
    }

    public function store(Request $request): JsonResponse
{
    $validator = Validator::make($request->all(), [
        'fio' => 'required|string|max:255',
        'company' => 'nullable|string|max:255',
        'phone' => 'required|string|max:20|unique:notebooks,phone',
        'email' => 'required|email|max:255|unique:notebooks,email',
        'birth_date' => 'nullable|date',
        'photo' => 'nullable|string|max:255',
    ]);

    if ($validator->fails()) {
        // Check if the error is related to either phone or email uniqueness
        if ($validator->errors()->has('phone') || $validator->errors()->has('email')) {
            return response()->json([
                'error' => 'The phone or email has already been taken.'
            ], 422);
        }

        // Return default error messages if other validation fails
        return response()->json($validator->errors(), 422);
    }

    $notebook = Notebook::create($request->all());
    return response()->json($notebook, 201);
}

    public function show($id): JsonResponse
    {
        $notebook = Notebook::find($id);

        if (!$notebook) {
            return response()->json(['error' => 'Notebook entry not found'], 404);
        }

        return response()->json($notebook);
    }

    public function update(Request $request, $id): JsonResponse
{
    $notebook = Notebook::find($id);

    if (!$notebook) {
        return response()->json(['error' => 'Notebook entry not found'], 404);
    }

    $validator = Validator::make($request->all(), [
        'fio' => 'required|string|max:255',
        'company' => 'nullable|string|max:255',
        'phone' => 'required|string|max:20|unique:notebooks,phone,' . $id,
        'email' => 'required|email|max:255|unique:notebooks,email,' . $id,
        'birth_date' => 'nullable|date',
        'photo' => 'nullable|string|max:255',
    ]);

    if ($validator->fails()) {
        // Check if the error is related to either phone or email uniqueness
        if ($validator->errors()->has('phone') || $validator->errors()->has('email')) {
            return response()->json([
                'error' => 'The phone or email has already been taken.'
            ], 422);
        }

        // Return default error messages if other validation fails
        return response()->json($validator->errors(), 422);
    }

    $notebook->update($request->all());
    return response()->json($notebook);
}

    public function destroy($id): JsonResponse
    {
        $notebook = Notebook::find($id);

        if (!$notebook) {
            return response()->json(['error' => 'Notebook entry not found'], 404);
        }

        $notebook->delete();
        return response()->json(['message' => 'Notebook entry deleted successfully']);
    }
}


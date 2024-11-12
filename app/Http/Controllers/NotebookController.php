<?php

namespace App\Http\Controllers;

use App\Models\Notebook;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;




/**
 * @OA\Schema(
 *     schema="Notebook",
 *     type="object",
 *     title="Notebook",
 *     description="A notebook entry",
 *     required={"fio", "phone", "email"},
 *     @OA\Property(property="fio", type="string", description="Full name"),
 *     @OA\Property(property="company", type="string", description="Company name", nullable=true),
 *     @OA\Property(property="phone", type="string", description="Phone number"),
 *     @OA\Property(property="email", type="string", format="email", description="Email address"),
 *     @OA\Property(property="birth_date", type="string", format="date", description="Birth date", nullable=true),
 *     @OA\Property(property="photo", type="string", description="Photo URL", nullable=true)
 * )
 */
class NotebookController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/v1/notebook",
     *     summary="Get all notebook entries",
     *     tags={"Notebook"},
     *     @OA\Response(
     *         response=200,
     *         description="List of notebook entries",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Notebook")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse // Get all notebooks
    {
        $perPage = $request->get('per_page', 10);  // Number of entries per page (10 by default)
    
        $notebooks = Notebook::paginate($perPage);
    
        return response()->json($notebooks);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/notebook",
     *     summary="Create a new notebook entry",
     *     tags={"Notebook"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"fio", "phone", "email"},
     *             @OA\Property(property="fio", type="string", example="John Doe"),
     *             @OA\Property(property="company", type="string", example="NewBee Corp"),
     *             @OA\Property(property="phone", type="string", example="+123456789"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="birth_date", type="string", format="date", example="1980-01-01"),
     *             @OA\Property(property="photo", type="string", example="http://example.com/photo.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Notebook entry created",
     *         @OA\JsonContent(ref="#/components/schemas/Notebook")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="The phone or email has already been taken.")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse // Create a new notebook
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

/**
     * @OA\Get(
     *     path="/api/v1/notebook/{id}",
     *     summary="Get a specific notebook entry",
     *     tags={"Notebook"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the notebook entry",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notebook entry found",
     *         @OA\JsonContent(ref="#/components/schemas/Notebook")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Notebook entry not found"
     *     )
     * )
     */
    public function show($id): JsonResponse // Get a single notebook
    {
        $notebook = Notebook::find($id);

        if (!$notebook) {
            return response()->json(['error' => 'Notebook entry not found'], 404);
        }

        return response()->json($notebook);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/notebook/{id}",
     *     summary="Update an existing notebook entry",
     *     tags={"Notebook"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the notebook entry",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"fio", "phone", "email"},
     *             @OA\Property(property="fio", type="string", example="Janete Doe"),
     *             @OA\Property(property="company", type="string", example="Another Corp"),
     *             @OA\Property(property="phone", type="string", example="+99954321"),
     *             @OA\Property(property="email", type="string", example="janete.doe@example.com"),
     *             @OA\Property(property="birth_date", type="string", format="date", example="1990-05-15"),
     *             @OA\Property(property="photo", type="string", example="http://example.com/photo.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notebook entry updated",
     *         @OA\JsonContent(ref="#/components/schemas/Notebook")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Notebook entry not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, $id): JsonResponse // Update a notebook
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


/**
     * @OA\Delete(
     *     path="/api/v1/notebook/{id}",
     *     summary="Delete a notebook entry",
     *     tags={"Notebook"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the notebook entry",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Notebook entry deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Notebook entry not found"
     *     )
     * )
     */
    public function destroy($id): JsonResponse // Delete a notebook
    {
        $notebook = Notebook::find($id);

        if (!$notebook) {
            return response()->json(['error' => 'Notebook entry not found'], 404);
        }

        $notebook->delete();
        return response()->json(['message' => 'Notebook entry deleted successfully']);
    }
}


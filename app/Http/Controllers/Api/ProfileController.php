<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/profile",
     *     summary="Update user profile",
     *     tags={"Profile"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"_method"},
     *             @OA\Property(property="_method", type="string", example="PUT", description="HTTP method"),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="address", type="string", example="123 Main St"),
     *             @OA\Property(property="phone_number", type="string", example="1234567890"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Update Profile Successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data updated"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="address", type="string", example="123 Main St"),
     *                 @OA\Property(property="phone_number", type="string", example="1234567890"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2022-01-01T00:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2022-01-01T00:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid input data"),
     *         )
     *     )
     * )
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $user->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Data updated',
            'data' => $user
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/profile",
     *     summary="Get user profile",
     *     tags={"Profile"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Get Profile Successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data found"),
     *             @OA\Property(property="data", type="object"
     *             )
     *         )    
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid input data"),
     *         )
     *     )
     * )
     */

    public function show()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Data found',
            'data' => $user
        ]);
    }
}

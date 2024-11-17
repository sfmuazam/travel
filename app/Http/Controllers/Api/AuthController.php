<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Register a new user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "phone_number", "address"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="address", type="string", example="123 Main St"),
     *             @OA\Property(property="phone_number", type="string", example="1234567890"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", example="password"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registrasi Berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Registrasi Berhasil"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="address", type="string", example="123 Main St"),
     *                     @OA\Property(property="phone_number", type="string", example="1234567890"),
     *                     @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 ),
     *                 @OA\Property(property="token", type="string", example="some-jwt-token")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ada masalah saat registrasi"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Ada masalah saat registrasi',
                    'data' => $validator->errors(),
                ]
            );
        }

        $user = User::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'address' => $request->address,
            'role' => 'user',
            'is_agent' => 0,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(
            [
                'success' => true,
                'message' => 'Registrasi Berhasil',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ],
            ]
        );
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Login a user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", example="password"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login Berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Login Berhasil"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="address", type="string", example="123 Main St"),
     *                     @OA\Property(property="phone_number", type="string", example="1234567890"),
     *                     @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 ),
     *                 @OA\Property(property="token", type="string", example="some-jwt-token")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ada masalah saat login"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Email atau password salah"),
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Ada masalah saat login',
                    'data' => $validator->errors(),
                ]
            );
        }

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Email atau password salah',
                ]
            );
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(
            [
                'success' => true,
                'message' => 'Login Berhasil',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ],
            ]
        );
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Logout a user",
     *     tags={"Auth"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully logged out.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "message" => "Successfully logged out."
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/reset-password",
     *     summary="Reset the password",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "token", "password", "password_confirmation"},
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="token", type="string", example="some-reset-token"),
     *             @OA\Property(property="password", type="string", example="new-password"),
     *             @OA\Property(property="password_confirmation", type="string", example="new-password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Password reset successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ada masalah saat mereset password"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Ada masalah saat mereset password',
                    'data' => $validator->errors(),
                ],
                422
            );
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Password reset successfully',
                    'data' => null,
                ],
                200
            );
        }

        return response()->json(
            [
                'success' => false,
                'message' => 'Ada masalah saat mereset password',
                'data' => null,
            ],
            422
        );
    }
}

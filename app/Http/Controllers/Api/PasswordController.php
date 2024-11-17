<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class PasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(
                [
                    'message' => 'The provided password does not match our records.',
                    'errors' => [
                        'current_password' => ['The provided password does not match our records.'],
                    ],
                ],
                422,
            );
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully.']);
    }

    /**
     * @OA\Post(
     *     path="/api/forgot-password",
     *     summary="Send a password reset link to the user's email",
     *     tags={"Password Reset"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reset link sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="We have e-mailed your password reset link!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unable to send reset link"),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="We can't find a user with that email address."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unable to send reset link due to server error"),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="Server error message"))
     *             )
     *         )
     *     )
     * )
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(
                [
                    'message' => 'Unable to send reset link',
                    'errors' => [
                        'email' => ['We can\'t find a user with that email address.'],
                    ],
                ],
                422,
            );
        }

        try {
            $status = Password::sendResetLink($request->only('email'));

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json(['message' => __($status)]);
            }

            return response()->json(
                [
                    'message' => 'Unable to send reset link',
                    'errors' => [
                        'email' => [__($status)],
                    ],
                ],
                422,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Unable to send reset link due to server error',
                    'errors' => [
                        'email' => [$e->getMessage()],
                    ],
                ],
                500,
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/reset-password",
     *     summary="Reset the user's password",
     *     tags={"Password Reset"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="reset-token"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="newpassword"),
     *             @OA\Property(property="password_confirmation", type="string", example="newpassword")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Your password has been reset successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unable to reset password"),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="Invalid token or email."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unable to reset password due to server error"),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="Server error message"))
     *             )
     *         )
     *     )
     * )
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
                $user
                    ->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])
                    ->save();

                event(new \Illuminate\Auth\Events\PasswordReset($user));
            });

            if ($status == Password::PASSWORD_RESET) {
                return redirect()->back()->with('status', __('Your password has been reset successfully.'));
            }

            return redirect()
                ->back()
                ->withErrors([
                    'email' => [__($status)],
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors([
                    'email' => [$e->getMessage()],
                ]);
        }
    }

    // public function resetPassword(Request $request) INI API
    // {
    //     $request->validate([
    //         'token' => 'required|string',
    //         'email' => 'required|email',
    //         'password' => 'required|string|min:8|confirmed',
    //     ]);

    //     try {
    //         $status = Password::reset(
    //             $request->only('email', 'password', 'password_confirmation', 'token'),
    //             function ($user, $password) {
    //                 $user->forceFill([
    //                     'password' => Hash::make($password),
    //                     'remember_token' => Str::random(60),
    //                 ])->save();

    //                 event(new \Illuminate\Auth\Events\PasswordReset($user));
    //             }
    //         );

    //         if ($status == Password::PASSWORD_RESET) {
    //             return response()->json(['message' => __($status)]);
    //         }

    //         return response()->json([
    //             'message' => 'Unable to reset password',
    //             'errors' => [
    //                 'email' => [__($status)]
    //             ]
    //         ], 422);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Unable to reset password due to server error',
    //             'errors' => [
    //                 'email' => [$e->getMessage()]
    //             ]
    //         ], 500);
    //     }
    // }

    public function showResetForm($token, Request $request)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }
}

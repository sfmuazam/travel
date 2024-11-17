<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AddOnProduct;
use Illuminate\Http\Request;

class AddOnController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/add_on_product",
     *     summary="Get all add on product",
     *     tags={"AddOn"},
     *     @OA\Response(
     *         response=200,
     *         description="Get all add on product",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data found"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */

    public function index()
    {
        $data = AddOnProduct::all();
        foreach ($data as $product) {
            $product->thumbnail = asset('storage/' . $product->thumbnail);
        }

        return response()->json(
            [
                'success' => true,
                'message' => 'Data found',
                'data' => $data
            ]
        );
    }
}

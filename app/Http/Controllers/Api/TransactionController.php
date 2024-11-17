<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BranchFinancial;
use App\Models\Commission;
use App\Models\Invoice;
use App\Models\PackageType;
use App\Models\Transaction;
use App\Models\TravelPackage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/transaction",
     *     summary="Get transaction by User",
     *     tags={"Transaction"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Data found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data found"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Data not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Data not found"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),   
     * )
     */

    public function index()
    {
        $transactions = Transaction::where('by_id', auth()->user()->id)->get();
        if ($transactions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
                'data' => null
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Data found',
            'data' => $transactions
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/transaction",
     *     summary="Create a new transaction",
     *     tags={"Transaction"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"qty", "travel_package_id", "travel_package_type_id", "total_price", "payment_status", "payment_method"},
     *             @OA\Property(property="total_price", type="number", format="float", example=100.0),
     *             @OA\Property(property="qty", type="integer", example=1),
     *             @OA\Property(property="travel_package_id", type="integer", example=1),
     *             @OA\Property(property="travel_package_type_id", type="integer", example=1),
     *             @OA\Property(property="notes", type="string", example="Some notes"),
     *             @OA\Property(property="payment_status", type="string", enum={"full", "partial"}, example="full"),
     *             @OA\Property(property="payment_method", type="string", enum={"va", "transfer"}, example="va"),
     *             @OA\Property(property="dp_amount", type="number", format="float", example=50.0),
     *             @OA\Property(property="dp_due_date", type="string", format="date", example="2024-12-31"),
     *             @OA\Property(property="addons", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="remaining_amount", type="number", format="float", example=50.0)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transaction created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Transaction created successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ada masalah saat registrasi"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'total_price' => 'required',
            'qty' => 'required',
            'travel_package_id' => 'required|exists:travel_packages,id',
            'travel_package_type_id' => 'required|exists:package_types,id',
            'notes' => 'nullable',
            'payment_status' => 'required|in:full,partial',
            'payment_method' => 'required|in:va,transfer',
            'dp_amount' => 'nullable',
            'dp_due_date' => 'nullable',

            'addons' => 'nullable|array',
            'remaining_amount' => 'nullable',
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
        $user = User::find(auth()->user()->id);

        $qty = $request->qty;
        $travel = TravelPackage::find($request->travel_package_id);
        DB::beginTransaction();

        try {
            $insufficientStock = false;

            if ($travel->id_airline != null) {
                if ($travel->airline->stock >= $qty) {
                    $travel->airline->decrement('stock', $qty);
                } else {
                    $insufficientStock = true;
                }
            }

            if ($travel->id_catering != null) {
                if ($travel->catering->stock >= $qty) {
                    $travel->catering->decrement('stock', $qty);
                } else {
                    $insufficientStock = true;
                }
            }

            if ($travel->id_transportation != null) {
                if ($travel->transportation->stock >= $qty) {
                    $travel->transportation->decrement('stock', $qty);
                } else {
                    $insufficientStock = true;
                }
            }

            if ($insufficientStock) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Stock tidak mencukupi',
                    ],
                    400
                );
            }
            $transaction = Transaction::create([
                'total_price' => $request->total_price,
                'qty' => $request->qty,
                'travel_package_id' => $request->travel_package_id,
                'travel_package_type_id' => $request->travel_package_type_id,
                'by_id' => auth()->user()->id,
                'notes' => $request->notes,
                'payment_status' => $request->payment_status,
                'payment_method' => $request->payment_method,
                'dp_amount' => $request->dp_amount,
                'dp_due_date' => $request->dp_due_date,
            ]);

            $financial = $transaction->financial()->create([
                'type' => 'in',
                'status' => 'pending',
                'amount' => $request->total_price,
                'id_transaction' => $transaction->id,
            ]);

            $rate_commmission = $user->commission_rate->commission_rate;

            if (auth()->user()->isAgentNotVerified()) {
                $rate_commmission = 0;
            }
            $newCommission = $request->total_price * $rate_commmission / 100;
            $lastCommission = Commission::where('user_id', auth()->user()->id)->orderBy('updated_at', 'desc')->first();

            if ($lastCommission) {
                $totalCommission = $lastCommission->total_commission + $newCommission;
            } else {
                $totalCommission = $newCommission;
            }

            Commission::create([
                'user_id' => auth()->user()->id,
                'id_transaction' => $transaction->id,
                'commission' => $newCommission,
                'notes' => 'upcoming',
                'total_commission' => $totalCommission,
            ]);
            Invoice::create([
                'transaction_id' => $transaction->id,
                'status' => 'pending',
            ]);

            if ($user->branchOfficers && $user->branchOfficers->branch_id != null) {
                BranchFinancial::create([
                    'type' => 'in',
                    'status' => 'pending',
                    'amount' => $request->total_price,
                    'id_transaction' => $transaction->id,
                    'branch_id' => $user->branchOfficers->branch_id,
                ]);
            }

            PackageType::where('id', $request->travel_package_type_id)->decrement('stock', $qty);
            DB::commit();
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Transaction created successfully',
                    'data' => $transaction
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                400
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/api/transaction/{id}",
     *     summary="Get transaction by ID",
     *     tags={"Transaction"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the transaction",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Data not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Data not found")
     *         )
     *     )
     * )
     */
    public function show(int $id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Data not found',
                ],
                404
            );
        }
        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }
    
    /**
     * @OA\POst(
     *     path="/api/transaction/{id}",
     *     summary="Update transaction by ID",
     *     tags={"Transaction"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the transaction",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"qty", "travel_package_id", "travel_package_type_id", "total_price", "payment_status", "payment_method", "_method"},
     *             @OA\Property(property="_method", type="string", example="PUT"),
     *             @OA\Property(property="total_price", type="number", format="float", example=100.0),
     *             @OA\Property(property="qty", type="integer", example=1),
     *             @OA\Property(property="travel_package_id", type="integer", example=1),
     *             @OA\Property(property="travel_package_type_id", type="integer", example=1),
     *             @OA\Property(property="notes", type="string", example="Some notes"),
     *             @OA\Property(property="payment_status", type="string", enum={"full", "partial"}, example="full"),
     *             @OA\Property(property="payment_method", type="string", enum={"va", "transfer"}, example="va"),
     *             @OA\Property(property="dp_amount", type="number", format="float", example=50.0),
     *             @OA\Property(property="dp_due_date", type="string", format="date", example="2024-12-31")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Data not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Data not found")
     *         )
     *     )
     * )
     */

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'total_price' => 'required',
            'qty' => 'required',
            'travel_package_id' => 'required',
            'travel_package_type_id' => 'required',
            'notes' => 'nullable',
            'payment_status' => 'required',
            'payment_method' => 'required',
            'dp_amount' => 'nullable',
            'dp_due_date' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $validator->errors(),
                ],
                400
            );
        }

        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ],
                404
            );
        }
        $transaction->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Data updated successfully'
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/transaction/{id}",
     *     summary="Delete transaction by ID",
     *     tags={"Transaction"},
     *     security={{ "bearerAuth": {} }},
     * 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the transaction",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Data found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data found"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Data not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Data not found"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     * )
     *  
     */
    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ],
                404
            );
        }
        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data deleted successfully'
        ]);
    }
}

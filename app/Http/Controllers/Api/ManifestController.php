<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Manifest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ManifestController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/manifest/{id}",
     *     summary="Register a new manifest",
     *     tags={"Manifest"},
     *     security={{ "bearerAuth": {} }},
     * 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "address", "birthdate", "place_of_birth", "gender", "last_name", "father_name", "mother_name", "ktp", "passport", "file_ktp", "file_passport", "file_family_card", "file_pas_photo_4x6", "file_marriage_book", "passport_expiry_date", "family_card"},
     *             @OA\Property(property="name", type="string", example="John Doe", description="Full name of the person"),
     *             @OA\Property(property="address", type="string", example="123 Main St", description="Address of the person"),
     *             @OA\Property(property="birthdate", type="string", format="date", example="1990-01-01", description="Birthdate of the person"),
     *             @OA\Property(property="place_of_birth", type="string", example="New York", description="Place of birth of the person"),
     *             @OA\Property(property="gender", type="string", example="male", description="Gender of the person"),
     *             @OA\Property(property="last_name", type="string", example="Doe", description="Last name of the person"),
     *             @OA\Property(property="middle_name", type="string", example="Middle", nullable=true, description="Middle name of the person"),
     *             @OA\Property(property="father_name", type="string", example="Father Name", description="Father's name"),
     *             @OA\Property(property="mother_name", type="string", example="Mother Name", description="Mother's name"),
     *             @OA\Property(property="ktp", type="string", example="1234567890", description="KTP number"),
     *             @OA\Property(property="passport", type="string", example="A12345678", description="Passport number"),
     *             @OA\Property(property="file_ktp", type="string", format="binary", description="KTP file"),
     *             @OA\Property(property="file_passport", type="string", format="binary", description="Passport file"),
     *             @OA\Property(property="bpjs", type="string", example="bpjs123", nullable=true, description="BPJS number"),
     *             @OA\Property(property="file_bpjs", type="string", format="binary", nullable=true, description="BPJS file"),
     *             @OA\Property(property="file_recommendation_letter", type="string", format="binary", nullable=true, description="Recommendation letter file"),
     *             @OA\Property(property="file_family_card", type="string", format="binary", description="Family card file"),
     *             @OA\Property(property="file_pas_photo_4x6", type="string", format="binary", description="Pas photo 4x6"),
     *             @OA\Property(property="file_marriage_book", type="string", format="binary", description="Marriage book file"),
     *             @OA\Property(property="file_covid_certificate", type="string", format="binary", nullable=true, description="Covid certificate file"),
     *             @OA\Property(property="passport_expiry_date", type="string", format="date", example="2025-01-01", description="Passport expiry date"),
     *             @OA\Property(property="family_card", type="string", example="familycard123", description="Family card number")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registrasi Berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Registrasi Berhasil"),
     *             @OA\Property(property="data", type="object")
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
    public function register(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'place_of_birth' => 'required|string|max:255',
            'gender' => 'required|string|max:255',

            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',

            'ktp' => 'required|string|max:255',
            'passport' => 'required|string|max:255',

            'file_ktp' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            'file_passport' => 'required|file|mimes:jpeg,png,jpg|max:2048',

            'bpjs' => 'nullable|string|max:255',

            'file_bpjs' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'file_recommendation_letter' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',

            'file_family_card' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            'file_pas_photo_4x6' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            'file_marriage_book' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            'file_covid_certificate' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',

            'passport_expiry_date' => 'required|date',
            'family_card' => 'required|string|max:255',
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

        $manifest = Manifest::create([
            'name' => $request->name,
            'address' => $request->address,
            'birthdate' => $request->birthdate,
            'place_of_birth' => $request->place_of_birth,
            'gender' => $request->gender,
            'by_id' => $id,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
        ]);

        $filesData = [
            'ktp' => $request->ktp,
            'passport' => $request->passport,

            'passport_expiry_date' => $request->passport_expiry_date,
            'family_card' => $request->family_card,

            'bpjs' => $request->bpjs,
        ];

        if ($request->hasFile('file_ktp')) {
            $filesData['file_ktp'] = $request->file('file_ktp')->store('file_ktp', 'public');
        }
        if ($request->hasFile('file_passport')) {
            $filesData['file_passport'] = $request->file('file_passport')->store('file_passport', 'public');
        }
      
        if ($request->hasFile('file_bpjs')) {
            $filesData['file_bpjs'] = $request->file('file_bpjs')->store('file_bpjs', 'public');
        }
        
        if ($request->hasFile('file_recommendation_letter')) {
            $filesData['file_recommendation_letter'] = $request->file('file_recommendation_letter')->store('file_recommendation_letter', 'public');
        }
        if ($request->hasFile('file_family_card')) {
            $filesData['file_family_card'] = $request->file('file_family_card')->store('file_family_card', 'public');
        }
        if ($request->hasFile('file_pas_photo_4x6')) {
            $filesData['file_pas_photo_4x6'] = $request->file('file_pas_photo_4x6')->store('file_pas_photo_4x6', 'public');
        }
        if ($request->hasFile('file_marriage_book')) {
            $filesData['file_marriage_book'] = $request->file('file_marriage_book')->store('file_marriage_book', 'public');
        }
        if ($request->hasFile('file_covid_certificate')) {
            $filesData['file_covid_certificate'] = $request->file('file_covid_certificate')->store('file_covid_certificate', 'public');
        }


        $manifest->filesuser()->create($filesData);

        return response()->json(
            [
                'success' => true,
                'message' => 'Registrasi Berhasil',
                'data' => $manifest,
            ]
        );
    }

    /**
     * @OA\Post(
     *     path="/api/manifest/{id}/{manifest_id}",
     *     summary="Update an existing manifest",
     *     tags={"Manifest"},
     *     security={{ "bearerAuth": {} }},
     * 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="manifest_id",
     *         in="path",
     *         description="ID of the manifest",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"_method","name", "address", "birthdate", "place_of_birth", "gender", "last_name", "father_name", "mother_name", "ktp", "passport", "passport_expiry_date", "family_card"},
     *             @OA\Property(property="_method", type="string", example="PUT", description="HTTP method"),
     *             @OA\Property(property="name", type="string", example="John Doe", description="Full name of the person"),
     *             @OA\Property(property="address", type="string", example="123 Main St", description="Address of the person"),
     *             @OA\Property(property="birthdate", type="string", format="date", example="1990-01-01", description="Birthdate of the person"),
     *             @OA\Property(property="place_of_birth", type="string", example="New York", description="Place of birth of the person"),
     *             @OA\Property(property="gender", type="string", example="male", description="Gender of the person"),
     *             @OA\Property(property="last_name", type="string", example="Doe", description="Last name of the person"),
     *             @OA\Property(property="middle_name", type="string", example="Middle", nullable=true, description="Middle name of the person"),
     *             @OA\Property(property="father_name", type="string", example="Father Name", description="Father's name"),
     *             @OA\Property(property="mother_name", type="string", example="Mother Name", description="Mother's name"),
     *             @OA\Property(property="ktp", type="string", example="1234567890", description="KTP number"),
     *             @OA\Property(property="passport", type="string", example="A12345678", description="Passport number"),
     *             @OA\Property(property="bpjs", type="string", example="bpjs123", nullable=true, description="BPJS number"),
     *             @OA\Property(property="passport_expiry_date", type="string", format="date", example="2025-01-01", description="Passport expiry date"),
     *             @OA\Property(property="family_card", type="string", example="familycard123", description="Family card number")
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data berhasil diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data berhasil diperbarui"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Data tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Data tidak ditemukan")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id, $manifest_id)
    {
         $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'place_of_birth' => 'required|string|max:255',
            'gender' => 'required|string|max:255',

            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',

            'ktp' => 'required|string|max:255',
            'passport' => 'required|string|max:255',

            'file_ktp' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'file_passport' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',

            'bpjs' => 'nullable|string|max:255',

            'file_bpjs' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'file_recommendation_letter' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',

            'file_family_card' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'file_pas_photo_4x6' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'file_marriage_book' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'file_covid_certificate' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',

            'passport_expiry_date' => 'required|date',
            'family_card' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Ada masalah saat mengupdate data',
                    'data' => $validator->errors(),
                ]
            );
        }
        $manifest = Manifest::where('by_id', $id)->where('id', $manifest_id)->first();

        if (!$manifest) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ],
                404
            );
        }
        $manifest->update($request->all());

        $filesData = [
            'ktp' => $request->ktp,
            'passport' => $request->passport,

            'passport_expiry_date' => $request->passport_expiry_date,
            'family_card' => $request->family_card,

            'bpjs' => $request->bpjs,
        ];

        if ($request->hasFile('file_ktp')) {
            $filesData['file_ktp'] = $request->file('file_ktp')->store('file_ktp', 'public');
        }
        if ($request->hasFile('file_passport')) {
            $filesData['file_passport'] = $request->file('file_passport')->store('file_passport', 'public');
        }
        
        if ($request->hasFile('file_bpjs')) {
            $filesData['file_bpjs'] = $request->file('file_bpjs')->store('file_bpjs', 'public');
        }
        
        if ($request->hasFile('file_recommendation_letter')) {
            $filesData['file_recommendation_letter'] = $request->file('file_recommendation_letter')->store('file_recommendation_letter', 'public');
        }
        if ($request->hasFile('file_family_card')) {
            $filesData['file_family_card'] = $request->file('file_family_card')->store('file_family_card', 'public');
        }
        if ($request->hasFile('file_pas_photo_4x6')) {
            $filesData['file_pas_photo_4x6'] = $request->file('file_pas_photo_4x6')->store('file_pas_photo_4x6', 'public');
        }
        if ($request->hasFile('file_marriage_book')) {
            $filesData['file_marriage_book'] = $request->file('file_marriage_book')->store('file_marriage_book', 'public');
        }
        if ($request->hasFile('file_covid_certificate')) {
            $filesData['file_covid_certificate'] = $request->file('file_covid_certificate')->store('file_covid_certificate', 'public');
        }


        // Update or create filesuser
        $manifest->filesuser()->updateOrCreate(['manifest_id' => $manifest->id], $filesData);

        return response()->json(
            [
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => $manifest,
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/api/manifest/{id}",
     *     summary="Get manifest by ID",
     *     tags={"Manifest"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
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
    public function byID($id)
    {
        $manifest = Manifest::where('by_id', $id)->get();

        if ($manifest->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Manifest not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Manifest found',
            'data' => $manifest
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/manifest/{id}/{manifest_id}",
     *     summary="Delete manifest by ID",
     *     tags={"Manifest"},
     *     security={{ "bearerAuth": {} }},
     * 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="manifest_id",
     *         in="path",
     *         description="ID of the manifest",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
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
    public function destroy($id, $manifest_id)
    {
        $manifest = Manifest::where('by_id', $id)->where('id', $manifest_id)->first();
        if (!$manifest) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ],
                404
            );
        }

        $manifest->delete();
        return response()->json(
            [
                'success' => true,
                'message' => 'Data berhasil dihapus',
            ]
        );
    }
}

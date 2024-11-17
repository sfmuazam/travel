<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Airlines;
use App\Models\Catering;
use App\Models\Hotel;
use App\Models\PackageTerms;
use App\Models\PackageType;
use App\Models\Transportation;
use App\Models\TravelCategory;
use App\Models\TravelPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TravelPackageController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/travel-package",
     *     summary="Get all travel packages with pagination and search",
     *     tags={"Travel Package"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Page number for pagination"
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Search keyword for filtering packages"
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Category name for filtering packages"   
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved travel packages",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="last_page", type="integer"),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $category = $request->category;
        $query = TravelPackage::query();

        if ($request->page) {
            $query->skip(($request->page - 1) * 10);
        }

        $query->orderBy('id', 'desc');

        if ($category) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('name', 'LIKE', "%{$category}%");
            });
        }

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%");
        }

        $packages = $query->paginate(10);

        $packages->getCollection()->transform(function ($package) {
            $packageTerms = [];
            foreach ($package->package_terms as $term) {
                if ($term['includes']) {
                    foreach ($term['includes'] as $include) {
                        $include = PackageTerms::find($include);
                        if ($include) {
                            if ($include->icon)
                                $include->icon = asset('storage/' . $include->icon);
                            $packageTerms['includes'][] = $include;
                        } else {
                            $packageTerms['includes'] = [];
                        }
                    }
                }
                if ($term['exludes']) {
                    foreach ($term['exludes'] as $exclude) {
                        $exclude = PackageTerms::find($exclude);
                        if ($exclude) {
                            if ($exclude->icon)
                                $exclude->icon = asset('storage/' . $exclude->icon);
                            $packageTerms['excludes'][] = $exclude;
                        } else {
                            $packageTerms['excludes'] = [];
                        }
                    }
                }
                if ($term['terms']) {
                    foreach ($term['terms'] as $term) {
                        $term = PackageTerms::find($term);
                        if ($term) {
                            if ($term->icon)
                                $term->icon = asset('storage/' . $term->icon);
                            $packageTerms['terms'][] = $term;
                        } else {
                            $packageTerms['terms'] = [];
                        }
                    }
                }
            }
            $package->package_terms = $packageTerms;

            if ($package->id_hotel) {
                $packageHotel = [];
                foreach ($package->id_hotel as $hotel) {
                    $hotel = Hotel::find($hotel);
                    if ($hotel) {
                        if ($hotel->logo)
                            $hotel->logo = asset('storage/' . $hotel->logo);
                        if ($hotel->thumbnail)
                            $hotel->thumbnail = asset('storage/' . $hotel->thumbnail);
                        $packageHotel[] = $hotel;
                    }
                }
                $package->hotel = $packageHotel;
            } else {
                $package->hotel = [];
            }

            $packageTransportation = [];
            if ($package->id_transportation) {
                $packageTransportation = Transportation::find($package->id_transportation);
                $package->transportation = $packageTransportation;
            } else {
                $package->transportation = [];
            }

            $packageCatering = [];
            if ($package->id_catering) {
                $packageCatering = Catering::find($package->id_catering);
                $package->catering = $packageCatering;
            } else {
                $package->catering = [];
            }

            $packageAirline = [];
            if ($package->id_airline) {
                $packageAirline = Airlines::find($package->id_airline);
                $package->airline = $packageAirline;
            } else {
                $package->airline = [];
            }
            unset($package->id_airline);
            unset($package->id_catering);
            unset($package->id_hotel);
            unset($package->id_transportation);
            $category = TravelCategory::find($package->category_id);
            $package->category = $category->name;
            $package->package_type = $package->packageType;
            unset($package->category_id);

            $package->thumbnail = asset('storage/' . $package->thumbnail);

            return $package;
        });

        return response()->json([
            'success' => true,
            'data' => $packages->items(),
            'current_page' => $packages->currentPage(),
            'last_page' => $packages->lastPage(),
            'total' => $packages->total(),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/travel-package/{id}",
     *     summary="Get travel package details",
     *     tags={"Travel Package"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Get Travel Package Successful",
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
    public function show($id)
    {
        $package = TravelPackage::find($id);
        if ($package) {
            $packageTerms = [
                'terms' => [],
                'excludes' => [],
                'includes' => []
            ];
            $package->thumbnail = asset('storage/' . $package->thumbnail);

            foreach ($package->package_terms as $term) {
                if (!empty($term['includes'])) {
                    foreach ($term['includes'] as $includeId) {
                        $include = PackageTerms::find($includeId);
                        if ($include) {
                            if ($include->icon)
                                $include->icon = asset('storage/' . $include->icon);
                            $packageTerms['includes'][] = $include;
                        }
                    }
                }
                if (!empty($term['excludes'])) {
                    foreach ($term['excludes'] as $excludeId) {
                        $exclude = PackageTerms::find($excludeId);
                        if ($exclude) {
                            if ($exclude->icon)
                                $exclude->icon = asset('storage/' . $exclude->icon);
                            $packageTerms['excludes'][] = $exclude;
                        }
                    }
                }
                if (!empty($term['terms'])) {
                    foreach ($term['terms'] as $termId) {
                        $termDetail = PackageTerms::find($termId);
                        if ($termDetail) {
                            if ($termDetail->icon)
                                $termDetail->icon = asset('storage/' . $termDetail->icon);
                            $packageTerms['terms'][] = $termDetail;
                        }
                    }
                }
            }
            $package->package_terms = $packageTerms;

            $packageHotel = [];
            if (!empty($package->id_hotel)) {
                foreach ($package->id_hotel as $hotelId) {
                    $hotel = Hotel::find($hotelId);
                    if ($hotel) {
                        if ($hotel->logo)
                            $hotel->logo = asset('storage/' . $hotel->logo);
                        if ($hotel->thumbnail)
                            $hotel->thumbnail = asset('storage/' . $hotel->thumbnail);
                        $packageHotel[] = $hotel;
                    }
                }
            }
            $package->hotel = $packageHotel;

            $package->transportation = $package->id_transportation ? Transportation::find($package->id_transportation) : [];

            $package->catering = $package->id_catering ? Catering::find($package->id_catering) : [];

            $package->airline = $package->id_airline ? Airlines::find($package->id_airline) : [];

            unset($package->id_hotel);
            unset($package->id_transportation);
            unset($package->id_catering);
            unset($package->id_airline);

            $package->package_type = $package->packageType;

            return response()->json([
                'success' => true,
                'data' => $package
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Travel package not found'
            ], 404);
        }
    }
}

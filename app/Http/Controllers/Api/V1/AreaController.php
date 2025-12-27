<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AreaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $city = $request->query('city');
        $destinationId = $request->query('destination_id');

        $query = Area::query();
        if ($destinationId) {
            $query->where('destination_id', $destinationId);
        } elseif ($city) {
            $destination = Destination::where('city', $city)->first();
            if ($destination) {
                $query->where('destination_id', $destination->id);
            } else {
                return response()->json(['success' => true, 'data' => []]);
            }
        }

        $areas = $query->orderBy('name')->get(['id','name','destination_id']);
        return response()->json(['success' => true, 'data' => $areas]);
    }

    public function search(Request $request): JsonResponse
    {
        $q = $request->query('query');
        $destinationId = $request->query('destination_id');
        $query = Area::query();
        if ($destinationId) {
            $query->where('destination_id', $destinationId);
        }
        if ($q) {
            $query->where('name', 'like', "%$q%");
        }
        $areas = $query->orderBy('name')->limit(50)->get(['id','name','destination_id']);
        return response()->json(['success' => true, 'data' => $areas]);
    }
}

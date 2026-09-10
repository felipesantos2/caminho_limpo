<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCollectionPointRequest;
use App\Http\Requests\Api\V1\UpdateCollectionPointRequest;
use App\Http\Resources\CollectionPointResource;
use App\Models\CollectionPoint;
use App\Services\Location\GeneratePlusCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

final class CollectionPointController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return CollectionPointResource::collection(CollectionPoint::query()->latest()->paginate(25));
    }

    public function store(StoreCollectionPointRequest $request, GeneratePlusCode $plusCode): JsonResponse
    {
        $data = $request->validated();
        $point = CollectionPoint::query()->create([
            ...$data,
            'plus_code' => $plusCode->handle((float) $data['latitude'], (float) $data['longitude']),
        ]);

        return (new CollectionPointResource($point))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CollectionPoint $collectionPoint): CollectionPointResource
    {
        return new CollectionPointResource($collectionPoint);
    }

    public function update(
        UpdateCollectionPointRequest $request,
        CollectionPoint $collectionPoint,
        GeneratePlusCode $plusCode,
    ): CollectionPointResource {
        $data = $request->validated();
        $collectionPoint->update([
            ...$data,
            'plus_code' => $plusCode->handle((float) $data['latitude'], (float) $data['longitude']),
        ]);

        return new CollectionPointResource($collectionPoint->refresh());
    }

    public function destroy(CollectionPoint $collectionPoint): Response
    {
        $collectionPoint->delete();

        return response()->noContent();
    }
}

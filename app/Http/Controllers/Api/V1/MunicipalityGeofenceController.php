<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreMunicipalityGeofenceRequest;
use App\Http\Requests\Api\V1\UpdateMunicipalityGeofenceRequest;
use App\Http\Resources\MunicipalityGeofenceResource;
use App\Models\MunicipalityGeofence;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

final class MunicipalityGeofenceController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return MunicipalityGeofenceResource::collection(
            MunicipalityGeofence::query()->orderBy('municipality')->get(),
        );
    }

    public function store(StoreMunicipalityGeofenceRequest $request): JsonResponse
    {
        $geofence = MunicipalityGeofence::query()->create($request->validated());

        return (new MunicipalityGeofenceResource($geofence))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(MunicipalityGeofence $municipalityGeofence): MunicipalityGeofenceResource
    {
        return new MunicipalityGeofenceResource($municipalityGeofence);
    }

    public function update(
        UpdateMunicipalityGeofenceRequest $request,
        MunicipalityGeofence $municipalityGeofence,
    ): MunicipalityGeofenceResource {
        $municipalityGeofence->update($request->validated());

        return new MunicipalityGeofenceResource($municipalityGeofence->refresh());
    }

    public function destroy(MunicipalityGeofence $municipalityGeofence): Response
    {
        $municipalityGeofence->delete();

        return response()->noContent();
    }
}

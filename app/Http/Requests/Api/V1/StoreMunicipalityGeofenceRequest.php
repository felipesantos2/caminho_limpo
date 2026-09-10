<?php

namespace App\Http\Requests\Api\V1;

use App\Support\MunicipalityGeofenceRules;
use Illuminate\Foundation\Http\FormRequest;

final class StoreMunicipalityGeofenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return MunicipalityGeofenceRules::all();
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return MunicipalityGeofenceRules::messages();
    }
}

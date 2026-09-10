<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Enums\ReportCategoryEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ListReportsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'category' => ['nullable', Rule::enum(ReportCategoryEnum::class)],
            'per_page' => ['nullable', 'integer', 'between:1,50'],
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Support\ReportRules;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return ReportRules::update();
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return ReportRules::messages();
    }
}

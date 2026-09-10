<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\ReportCategoryEnum;
use App\Enums\ReportStatusEnum;
use Illuminate\Validation\Rule;

final class ReportRules
{
    /** @return array<string, mixed> */
    public static function create(): array
    {
        return self::rules(imageRequired: true);
    }

    /** @return array<string, mixed> */
    public static function update(): array
    {
        return self::rules(imageRequired: false);
    }

    /** @return array<string, mixed> */
    private static function rules(bool $imageRequired): array
    {
        return [
            'category'    => ['required', Rule::enum(ReportCategoryEnum::class)],
            'description' => ['required', 'string', 'min:10', 'max:500'],
            'address'     => ['required', 'string', 'max:255'],
            'latitude'    => ['nullable', 'required_with:longitude', 'numeric', 'between:-90,90'],
            'longitude'   => ['nullable', 'required_with:latitude', 'numeric', 'between:-180,180'],
            'status'      => ['required', Rule::enum(ReportStatusEnum::class)],
            'image'       => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    /** @return array<string, string> */
    public static function messages(): array
    {
        return [
            'image.required'          => 'Adicione uma foto do local.',
            'image.image'             => 'O arquivo deve ser uma imagem válida.',
            'image.mimes'             => 'A foto deve ser JPG, PNG ou WebP.',
            'image.max'               => 'A foto deve ter no máximo 5 MB.',
            'latitude.required_with'  => 'Informe a latitude e a longitude juntas.',
            'longitude.required_with' => 'Informe a latitude e a longitude juntas.',
        ];
    }
}

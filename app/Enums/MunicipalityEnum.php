<?php

declare(strict_types=1);

namespace App\Enums;

enum MunicipalityEnum: string
{
    case NovoCruzeiro = 'novo_cruzeiro';
    case AguasFormosas = 'aguas_formosas';
    case TeofiloOtoni = 'teofilo_otoni';
    case Itaipe = 'itaipe';
    case Catuji = 'catuji';

    public function label(): string
    {
        return match ($this) {
            self::NovoCruzeiro  => 'Novo Cruzeiro',
            self::AguasFormosas => 'Águas Formosas',
            self::TeofiloOtoni  => 'Teófilo Otoni',
            self::Itaipe        => 'Itaipé',
            self::Catuji        => 'Catuji',
        };
    }

    /** @return array<int, array{value: string, label: string}> */
    public static function options(): array
    {
        return array_map(fn (self $municipality): array => [
            'value' => $municipality->value,
            'label' => $municipality->label(),
        ], self::cases());
    }
}

<?php

declare(strict_types=1);

namespace App\Enums;

enum ReportStatusEnum: string
{
    case Received = 'received';
    case Triage = 'triage';
    case Published = 'published';
    case Restricted = 'restricted';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Received   => 'Recebido',
            self::Triage     => 'Em triagem',
            self::Published  => 'Publicado',
            self::Restricted => 'Restrito',
            self::Rejected   => 'Rejeitado',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Received   => 'badge-info',
            self::Triage     => 'badge-warning',
            self::Published  => 'badge-success',
            self::Restricted => 'badge-neutral',
            self::Rejected   => 'badge-error',
        };
    }

    /** @return array<int, array{value: string, label: string}> */
    public static function options(): array
    {
        return array_map(
            fn (self $status): array => [
                'value' => $status->value,
                'label' => $status->label(),
            ],
            self::cases(),
        );
    }
}

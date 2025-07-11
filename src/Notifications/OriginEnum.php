<?php

namespace App\Enums;

namespace CasaPublicadoraBrasileira\PortalUtils\Notifications;

enum OriginEnum: string
{
    case Eclass = 'eclass';
    case Trilhas = 'trilhas';
    case TrilhasUC = 'trilhas-uc';
    case Avaliacoes = 'avaliacoes';
    case Others = 'others';

    public static function isValid(string $value): bool
    {
        return collect(self::cases())->contains(fn ($enum) => $enum->value === $value);
    }

    public static function getValuesList(): string
    {
        return collect(self::cases())->map(fn ($enum) => $enum->value)->join(',');
    }

    public static function getValues(): array
    {
        return collect(self::cases())
            ->map(fn ($enum) => $enum->value)
            ->values()
            ->toArray();
    }

    public static function getValuesNoOthers(): array
    {
        return collect(self::cases())
            ->filter(fn ($enum) => $enum !== OriginEnum::Others)
            ->map(fn ($enum) => $enum->value)
            ->values()
            ->toArray();
    }
}

<?php

namespace App\Enumerations;

enum UserRoleEnum: string
{
    case ADMIN = 'Administrator';
    case COMPANY_USER = 'Client Company Employee';
    case COMPANY_ADMIN = 'Client Company Administrator';

    /** return array<string> */
    public static function companyRoles(): array
    {
        return [
            self::COMPANY_ADMIN->value,
            self::COMPANY_USER->value,
        ];
    }
}

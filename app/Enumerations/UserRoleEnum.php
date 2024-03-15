<?php

namespace App\Enumerations;

enum UserRoleEnum: string
{

    case ADMIN = 'Administrator';
    case COMPANY_USER = 'Client Company Employee';
    case SOLO_USER = 'Normal User';
}

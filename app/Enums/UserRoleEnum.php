<?php

namespace App\Enums;


enum UserRoleEnum: string
{
    case ADMIN = 'admin';
    case CLIENT = 'client';
}

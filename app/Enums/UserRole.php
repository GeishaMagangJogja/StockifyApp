<?php
namespace App\Enums;
enum UserRole: string
{
    case ADMIN = 'Admin';
    case STAFF_GUDANG = 'Staff Gudang';
    case MANAJER_GUDANG = 'Manajer Gudang';
}

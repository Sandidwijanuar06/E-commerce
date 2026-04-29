<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /**
     * Contoh: Mencegah role 'super-admin' dihapus secara tidak sengaja
     */
    public function isDeletable(): bool
    {
        return !in_array($this->name, ['super-admin', 'admin']);
    }
}

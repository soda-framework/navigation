<?php

namespace Soda\Navigation\Support;

use Soda\Cms\Database\Models\Role;
use Soda\Cms\Database\Models\Permission;

use Illuminate\Database\Seeder as BaseSeeder;

class InstallPermissions extends BaseSeeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $permissionManageNavigation = Permission::firstOrCreate([
            'name'                       => 'manage-navigation',
            'display_name'               => 'Manage Navigation',
            'description'                => 'View, edit and delete navigation items.',
        ]);

        foreach(['developer', 'super-admin', 'admin'] as $roleName) {
            if ($role = Role::where('name', $roleName)->first()) {
                $role->attachPermission($permissionManageNavigation);
            }
        }
    }
}

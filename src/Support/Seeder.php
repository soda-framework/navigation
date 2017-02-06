<?php

namespace Soda\Navigation\Support;

use Soda\Cms\Models\Role;
use Soda\Cms\Models\Permission;
use Illuminate\Database\Seeder as BaseSeeder;

class Seeder extends BaseSeeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $permissionManageNavigation = Permission::firstOrCreate([
            'name'         => 'manage-navigation',
            'display_name' => 'Manage Navigation',
            'description'  => 'View, edit and delete navigation items.',
        ]);

        $adminRole = Role::where('name', 'admin')->first();

        if ($adminRole) {
            $adminRole->attachPermission($permissionManageNavigation);
        }

        $developerRole = Role::where('name', 'developer')->first();

        if ($developerRole) {
            $developerRole->attachPermission($permissionManageNavigation);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = [
            'role',
            'employee',
            'jobOrderType',
            'country',
            'type',
            'task',
            'setting',
            'report',
            'home',
            'taskCompleted',
            'taskApproval',
            'taskInDay',
            'product',
            'unit',
            'warehouse',
            'noteVoucher',
            'rolloutSuperVisor',
            'excavation',

        ];

        foreach ($modules as $module) {
            $actions = ['table', 'add', 'edit', 'delete'];
            foreach ($actions as $action) {
                Permission::create([
                    'name' => "$module-$action",
                    'guard_name' => 'admin',
                ]);
            }
        }

         // Adding custom single permissions
         $customPermissions = ['submit-button', 'reject-button','whatsapp-send'];

         foreach ($customPermissions as $permission) {
             Permission::firstOrCreate([
                 'name' => $permission,
                 'guard_name' => 'admin',
             ]);
         }



    }
}

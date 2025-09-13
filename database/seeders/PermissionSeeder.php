<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the default permissions
        $permissions = [
            array('name' => 'Create Role','group_name' => 'Roles'),
            array('name' => 'View Role','group_name' => 'Roles'),
            array('name' => 'Edit Role','group_name' => 'Roles'),
            array('name' => 'Delete Role','group_name' => 'Roles'),
            array('name' => 'Assign Permission','group_name' => 'Roles'),

            array('name' => 'Create Permission','group_name' => 'Permissions'),
            array('name' => 'View Permission','group_name' => 'Permissions'),
            array('name' => 'Edit Permission','group_name' => 'Permissions'),
            array('name' => 'Delete Permission','group_name' => 'Permissions'),

            array('name' => 'User Create','group_name' => 'User'),
            array('name' => 'User View','group_name' => 'User'),
            array('name' => 'User Edit','group_name' => 'User'),
            array('name' => 'User Delete','group_name' => 'User'),

            array('name' => 'Admin Create','group_name' => 'Admin'),
            array('name' => 'Admin View','group_name' => 'Admin'),
            array('name' => 'Admin Edit','group_name' => 'Admin'),
            array('name' => 'Admin Delete','group_name' => 'Admin'),

            array('name' => 'Brand Create','group_name' => 'Brand'),
            array('name' => 'Brand View','group_name' => 'Brand'),
            array('name' => 'Brand Edit','group_name' => 'Brand'),
            array('name' => 'Brand Delete','group_name' => 'Brand'),

            array('name' => 'Category Create','group_name' => 'Category'),
            array('name' => 'Category View','group_name' => 'Category'),
            array('name' => 'Category Edit','group_name' => 'Category'),
            array('name' => 'Category Delete','group_name' => 'Category'),

            array('name' => 'Hsncode Create','group_name' => 'HSN Code'),
            array('name' => 'Hsncode View','group_name' => 'HSN Code'),
            array('name' => 'Hsncode Edit','group_name' => 'HSN Code'),
            array('name' => 'Hsncode Delete','group_name' => 'HSN Code'),

            array('name' => 'Order View','group_name' => 'Order'),
            array('name' => 'Order Edit','group_name' => 'Order'),
            array('name' => 'Order Delete','group_name' => 'Order'),

            array('name' => 'Seat Number Create','group_name' => 'Seat Number'),
            array('name' => 'Seat Number View','group_name' => 'Seat Number'),
            array('name' => 'Seat Number Edit','group_name' => 'Seat Number'),
            array('name' => 'Seat Number Delete','group_name' => 'Seat Number'),

            array('name' => 'Unit Master Create','group_name' => 'Unit Master'),
            array('name' => 'Unit Master View','group_name' => 'Unit Master'),
            array('name' => 'Unit Master Edit','group_name' => 'Unit Master'),
            array('name' => 'Unit Master Delete','group_name' => 'Unit Master'),

            array('name' => 'Vendor Create','group_name' => 'Vendor'),
            array('name' => 'Vendor View','group_name' => 'Vendor'),
            array('name' => 'Vendor Edit','group_name' => 'Vendor'),
            array('name' => 'Vendor Delete','group_name' => 'Vendor'),

            array('name' => 'Product View','group_name' => 'Product'),
            array('name' => 'Product Basic Info Create','group_name' => 'Product'),
            array('name' => 'Product Basic Info Edit','group_name' => 'Product'),
            array('name' => 'Product Price Edit','group_name' => 'Product'),
            array('name' => 'Product Variation Edit','group_name' => 'Product'),
            array('name' => 'Product Images Edit','group_name' => 'Product'),
            array('name' => 'Product Addons & Complementary Edit','group_name' => 'Product'),
            array('name' => 'Product Delete','group_name' => 'Product'),
            
        ];

        // Create the permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                ['group_name' => $permission['group_name']]
            );
        }
        
    }
}

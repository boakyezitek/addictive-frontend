<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $collection = collect();

        foreach (glob(__DIR__.'/../../app/Policies/*.php') as $file)
        {
            $class = '\App\Policies\\'.basename($file, '.php');

            if (class_exists($class) && is_subclass_of($class, \App\Services\NovaPermissions\NovaPermissionPolicy::class))
            {
                $collection->add($class::$key);
            }
        }

        $collection->each(function ($item, $key) {
            // create permissions for each collection item
            if(!Permission::where('group', $item)->exists()){
                Permission::create(['group' => $item, 'name' => 'view ' . $item, 'guard_name' => 'admin']);
                Permission::create(['group' => $item, 'name' => 'view own ' . $item, 'guard_name' => 'admin']);
                Permission::create(['group' => $item, 'name' => 'manage ' . $item, 'guard_name' => 'admin']);
                Permission::create(['group' => $item, 'name' => 'manage own ' . $item, 'guard_name' => 'admin']);
                Permission::create(['group' => $item, 'name' => 'restore ' . $item, 'guard_name' => 'admin']);
                Permission::create(['group' => $item, 'name' => 'forceDelete ' . $item, 'guard_name' => 'admin']);
            }
        });

        // Create a Super-Admin Role and assign all permissions to it
        if(!Role::where('name', 'super-admin')->exists()){
            $role = Role::create(['name' => 'super-admin', 'guard_name' => 'admin']);
            $role->givePermissionTo(Permission::all());

            // Give User Super-Admin Role
            $user = \App\Models\Admin::whereEmail('admin@appsolute.fr')->first(); // enter your email here
            $user->assignRole('super-admin');
        }


    }
}

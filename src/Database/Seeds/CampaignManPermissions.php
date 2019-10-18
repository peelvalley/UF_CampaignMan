<?php

namespace UserFrosting\Sprinkle\CampaignMan\Database\Seeds;

use UserFrosting\Sprinkle\Core\Database\Seeder\BaseSeed;
use UserFrosting\Sprinkle\Account\Database\Models\Permission;
use UserFrosting\Sprinkle\Account\Database\Models\Role;

class CampaignManPermissions extends BaseSeed
{
    /**
     * {@inheritDoc}
     */
    public function run()
    {
        $this->validateMigrationDependencies([
            '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\RolesTable',
            '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\PermissionsTable'
        ]);

        // Get and save permissions
        $permissions = $this->getPermissions();
        $this->savePermissions($permissions);
        // Add default mappings to permissions
        $this->syncPermissionsRole($permissions);
    }

    /**
     * @return array Permissions to seed
     */
    protected function getPermissions()
    {
        return [
            'group_create_mailing_list' => new Permission([
                'slug' => 'create_mailing_list',
                'name' => 'Create group mailing list',
                'conditions' => 'equals_num(self.group_id, group.id)',
                'description' => 'Enables the user to create mailing lists for their own group'
            ]),

            'group_create_subscription' => new Permission([
                'slug' => 'create_subscription',
                'name' => 'Create group mailing list subscription',
                'conditions' => 'equals_num(self.group_id, group.id)',
                'description' => 'Enables the user to create list subscriptions for their own group'
            ]),

            'group_view_mailing_lists' => new Permission([
                'slug' => 'view_mailing_lists',
                'name' => 'View group mailing lists',
                'conditions' => 'equals_num(self.group_id, group.id)',
                'description' => 'Enables the user to view all mailing lists for their own group'
            ]),

            'group_view_mailing_list' => new Permission([
                'slug' => 'view_mailing_list',
                'name' => 'View group mailing list details',
                'conditions' => 'equals_num(self.group_id, group.id)',
                'description' => 'Enables the user to view mailing list details for their own group'
            ]),

            'group_view_list_subscriptions' => new Permission([
                'slug' => 'view_list_subscriptions',
                'name' => 'View group mailing list subscribers',
                'conditions' => 'equals_num(self.group_id, group.id)',
                'description' => 'Enables the user to view list subscribers for their own group'
            ]),

            'group_update_subscription_field' => new Permission([
                'slug' => 'update_subscription_field',
                'name' => 'Update subscription details',
                'conditions' => "equals_num(self.group_id, group.id) && subset(fields,['group_id', 'subscriber_name', 'data'])",
                'description' => 'Enables the user to update list subscription details for their own group'
            ]),

            'view_mailing_lists' => new Permission([
                'slug' => 'view_mailing_lists',
                'name' => 'View site mailing lists',
                'conditions' => 'always()',
                'description' => 'Enables the user to view all mailing lists for the site'
            ]),
        ];
    }

    /**
     * Save permissions.
     *
     * @param array $permissions
     */
    protected function savePermissions(array &$permissions)
    {
        foreach ($permissions as $slug => $permission) {
            // Trying to find if the permission already exist
            $existingPermission = Permission::where(['slug' => $permission->slug, 'conditions' => $permission->conditions])->first();
            // Don't save if already exist, use existing permission reference
            // otherwise to re-sync permissions and roles
            if ($existingPermission == null) {
                $permission->save();
            } else {
                $permissions[$slug] = $existingPermission;
            }
        }
    }

    /**
     * Sync permissions with default roles.
     *
     * @param array $permissions
     */
    protected function syncPermissionsRole(array $permissions)
    {
        $roleGroupAdmin = Role::where('slug', 'group-admin')->first();
        if ($roleGroupAdmin) {
            $roleGroupAdmin->permissions()->syncWithoutDetaching([
                $permissions['group_update_subscription_field']->id,
                $permissions['group_view_list_subscriptions']->id,
                $permissions['group_create_mailing_list']->id,
                $permissions['group_create_subscription']->id,
                $permissions['group_view_mailing_lists']->id,
                $permissions['group_view_mailing_list']->id
            ]);
        }

        $roleUser = Role::where('slug', 'site-admin')->first();
        if ($roleUser) {
            $roleUser->permissions()->syncWithoutDetaching([
                $permissions['view_mailing_lists']->id
            ]);
        }
    }
}
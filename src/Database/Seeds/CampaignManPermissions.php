<?php

namespace \UserFrosting\Sprinkle\CampaignMan\Database\Seeds;

use UserFrosting\Sprinkle\Core\Database\Seeder\BaseSeed;
use UserFrosting\Sprinkle\Account\Database\Models\Permission;

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
            $roleGroupAdmin->permissions()->sync([
                $permissions['group_create_mailing_list']->id,
            ]);
        }
    }
}
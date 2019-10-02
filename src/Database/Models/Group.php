<?php


namespace UserFrosting\Sprinkle\CampaignMan\Database\Models;


if (class_exists('\UserFrosting\Sprinkle\UserProfile\Database\Models\Group')) {
    class_alias('\UserFrosting\Sprinkle\UserProfile\Database\Models\Group', '\UserFrosting\Sprinkle\CampaignMan\Database\Models\CoreGroup');
} else {
    class_alias('\UserFrosting\Sprinkle\Account\Database\Models\Group', '\UserFrosting\Sprinkle\CampaignMan\Database\Models\CoreGroup');
}

class Group extends \UserFrosting\Sprinkle\UserProfile\Database\Models\Group
{

    /**
     * Eloquent relation to the mailing_lists table.
     */
    public function mailingLists()
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;
        return $this->hasMany($classMapper->getClassMapping('mailing_list'), 'group_id');
    }

    public function debug()
    {
        return print_r([
            'parent' =>get_class_methods(parent),
            'this' => get_class_methods($this)
        ], TRUE);
    }

    /**
     * Delete the group's mailing lists when deleting the main model.
     *
     * @return void
     */
    public function delete()
    {
        $this->mailingLists()->delete();
        parent::delete();
    }
}

<?php


namespace UserFrosting\Sprinkle\CampaignMan\Database\Models;


if (class_exists('UserFrosting\Sprinkle\UserProfile\Database\Models\Group')) {
    class Group extends UserFrosting\Sprinkle\UserProfile\Database\Models\Group
    {
        /**
         * Eloquent relation to the mailing_lists table.
         */
        public function mailingLists()
        {
            return $this->hasMany($classMapper->getClassMapping('mailing_list'), 'group_id');
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
} else {
    class Group extends UserFrosting\Sprinkle\Account\Database\Models\Group
    {
        /**
         * Eloquent relation to the mailing_lists table.
         */
        public function mailingLists()
        {
            return $this->hasMany($classMapper->getClassMapping('mailing_list'), 'group_id');
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
}



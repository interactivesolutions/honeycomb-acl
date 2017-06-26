<?php

namespace interactivesolutions\honeycombacl\app\models\users;

use interactivesolutions\honeycombacl\app\models\HCUsers;
use interactivesolutions\honeycombcore\models\HCUuidModel;

class HCGroups extends HCUuidModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hc_users_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'label', 'creator_id'];

    /**
     * Getting users for groups
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users ()
    {
        return $this->belongsToMany(HCUsers::class, HCGroupsUsers::getTableName(), 'group_id', 'user_id');
    }
}
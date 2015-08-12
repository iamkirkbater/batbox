<?php
/**
 * An helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace Batbox\Models{
/**
 * Batbox\Models\Project
 *
 * @property integer $id 
 * @property string $name 
 * @property boolean $status 
 * @property integer $contact 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @method static \Illuminate\Database\Query\Builder|\Batbox\Models\Project whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Batbox\Models\Project whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Batbox\Models\Project whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Batbox\Models\Project whereContact($value)
 * @method static \Illuminate\Database\Query\Builder|\Batbox\Models\Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Batbox\Models\Project whereUpdatedAt($value)
 */
	class Project {}
}

namespace Batbox\Models{
/**
 * Batbox\Models\User
 *
 * @property integer $id 
 * @property string $email 
 * @property string $password 
 * @property string $permissions 
 * @property string $last_login 
 * @property string $first_name 
 * @property string $last_name 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @method static \Illuminate\Database\Query\Builder|\Batbox\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Batbox\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Batbox\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Batbox\Models\User wherePermissions($value)
 * @method static \Illuminate\Database\Query\Builder|\Batbox\Models\User whereLastLogin($value)
 * @method static \Illuminate\Database\Query\Builder|\Batbox\Models\User whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\Batbox\Models\User whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\Batbox\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Batbox\Models\User whereUpdatedAt($value)
 */
	class User {}
}


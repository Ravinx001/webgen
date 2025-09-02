<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $complaint_category_id
 * @property string $title
 * @property string|null $description
 * @property string|null $form_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ComplaintCategory $category
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommonComplaint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommonComplaint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommonComplaint query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommonComplaint whereComplaintCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommonComplaint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommonComplaint whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommonComplaint whereFormData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommonComplaint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommonComplaint whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommonComplaint whereUpdatedAt($value)
 */
	class CommonComplaint extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $parent_id
 * @property string $category_type
 * @property string|null $description
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ComplaintCategory> $children
 * @property-read int|null $children_count
 * @property-read mixed $complaints_count
 * @property-read mixed $full_name
 * @property-read mixed $is_main_category
 * @property-read mixed $is_sub_category
 * @property-read ComplaintCategory|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintCategory active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintCategory byType($type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintCategory inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintCategory mainCategories()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintCategory subCategories()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintCategory whereCategoryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintCategory whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintCategory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintCategory withComplaintsCount()
 */
	class ComplaintCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\ComplaintCategory|null $category
 * @property-read mixed $fields
 * @property-read mixed $form_config
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintFormStructure active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintFormStructure byCategory($categoryId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintFormStructure newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintFormStructure newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintFormStructure query()
 */
	class ComplaintFormStructure extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}


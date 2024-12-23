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
 * 
 *
 * @property int $id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property int $person_id
 * @property int|null $country_id
 * @property int|null $province_id
 * @property int|null $city_id
 * @property string|null $location_title
 * @property string|null $street_name
 * @property int|null $builder_no
 * @property int|null $floor_no
 * @property int|null $unit_no
 * @property string|null $lat
 * @property string|null $lon
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Area|null $Area
 * @property-read \App\Models\City|null $City
 * @property-read \App\Models\Country|null $Country
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\Person $Person
 * @property-read \App\Models\Province|null $Province
 * @method static \Database\Factories\AddressFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereBuilderNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereFloorNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereLocationTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereStreetName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereUnitNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereUpdatedAt($value)
 */
	class Address extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FamilyBoard> $FamilyBoards
 * @property-read int|null $family_boards_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Memory> $Memories
 * @property-read int|null $memories_count
 * @method static \Database\Factories\CategoryContentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent whereUpdatedAt($value)
 */
	class CategoryContent extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $province_id
 * @property string $title
 * @property string|null $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Province $Province
 * @method static \Database\Factories\CityFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City query()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City withoutTrashed()
 */
	class City extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string|null $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Database\Factories\CountryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country query()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country withoutTrashed()
 */
	class Country extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FamilyEvent> $FamilyEvents
 * @property-read int|null $family_events_count
 * @method static \Database\Factories\EventFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event query()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withoutTrashed()
 */
	class Event extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property int $category_content_id
 * @property int $group_category_id
 * @property string $title
 * @property string $selected_date
 * @property string|null $file_path
 * @property string $description
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\CategoryContent $Category
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @method static \Database\Factories\FamilyBoardFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard query()
 * @mixin \Eloquent
 * @property-read \App\Models\CategoryContent|null $CategoryContent
 * @property-read \App\Models\GroupCategory|null $GroupCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notif> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereCategoryContentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereGroupCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereSelectedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard withoutTrashed()
 */
	class FamilyBoard extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $person_id
 * @property int $event_id
 * @property int $category_content_id
 * @property int $group_category_id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property string $event_date
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\Event $Event
 * @property-read \App\Models\Person $Person
 * @method static \Database\Factories\FamilyEventFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent query()
 * @mixin \Eloquent
 * @property-read \App\Models\CategoryContent $CategoryContent
 * @property-read \App\Models\GroupCategory|null $GroupCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notif> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereCategoryContentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereEventDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereGroupCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent withoutTrashed()
 */
	class FamilyEvent extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property int $person_id
 * @property int $group_category_id
 * @property string|null $image
 * @property string|null $title
 * @property string|null $description
 * @property string|null $star
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\Person $Person
 * @method static \Database\Factories\FavoriteFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite query()
 * @mixin \Eloquent
 * @property-read \App\Models\GroupCategory|null $GroupCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notif> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereGroupCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereStar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite withoutTrashed()
 */
	class Favorite extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \App\Models\User|null $Creator
 * @property-read \App\Models\User|null $Editor
 * @method static \Database\Factories\GroupFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group withoutTrashed()
 * @mixin Eloquent
 * @property int $id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property string $title
 * @property int $status 1=Active 2=Inactive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GroupCategoryDetail> $GroupCategoryDetails
 * @property-read int|null $group_category_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GroupDetail> $GroupDetails
 * @property-read int|null $group_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Person> $people
 * @property-read int|null $people_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereUpdatedAt($value)
 */
	class Group extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \App\Models\User|null $Creator
 * @property-read \App\Models\User|null $Editor
 * @method static \Database\Factories\GroupCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategory withoutTrashed()
 * @mixin Eloquent
 * @property int $id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property string $title
 * @property int $status 1=Active 2=Inactive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GroupCategoryDetail> $GroupCategoryDetails
 * @property-read int|null $group_category_details_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategory whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategory whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategory whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategory whereUpdatedAt($value)
 */
	class GroupCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property int $person_id
 * @property string|null $image
 * @property string|null $title
 * @property string|null $description
 * @property int $star 0=none  1=One 2=Two 3=Three 4=Four 5=Five
 * @property int $status 1=Active 2=Inactive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\Group|null $Group
 * @property-read GroupCategoryDetail|null $GroupCategoryDetail
 * @method static \Database\Factories\GroupCategoryDetailFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereStar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail withoutTrashed()
 * @mixin Eloquent
 * @property int $group_category_id
 * @property int $group_id
 * @property-read \App\Models\GroupCategory $GroupCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Person> $personsInRelatedGroups
 * @property-read int|null $persons_in_related_groups_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereGroupCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereGroupId($value)
 */
	class GroupCategoryDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \App\Models\User|null $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\Group|null $Group
 * @property-read \App\Models\Person|null $Person
 * @method static \Database\Factories\GroupDetailFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail withoutTrashed()
 * @mixin Eloquent
 * @property int $id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property int $group_id
 * @property int $person_id
 * @property string $title
 * @property int $status 1=Active 2=Inactive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail whereUpdatedAt($value)
 */
	class GroupDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $person_id
 * @property int $category_content_id
 * @property int $group_category_id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property string|null $content
 * @property string $title
 * @property string|null $description
 * @property int $is_shown_after_death
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\CategoryContent $Category
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\GroupCategory $GroupCategory
 * @property-read \App\Models\Person $Person
 * @method static \Database\Factories\MemoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory query()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notif> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereCategoryContentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereGroupCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereIsShownAfterDeath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory withoutTrashed()
 */
	class Memory extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property int $priority
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Database\Factories\NaslanRelationshipFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship query()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship withoutTrashed()
 */
	class NaslanRelationship extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $creator_id
 * @property int $user_id
 * @property string|null $message
 * @property int $notif_status  1=Read 2=NotRead
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notif newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notif newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notif query()
 * @mixin \Eloquent
 * @property int $notifiable_id
 * @property string $notifiable_type
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $RelatedUser
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $notifiable
 * @method static \Database\Factories\NotifFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notif notRead()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notif onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notif whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notif whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notif whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notif whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notif whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notif whereNotifStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notif whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notif whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notif whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notif withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notif withoutTrashed()
 */
	class Notif extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property string $node_code
 * @property int $node_level_x
 * @property int $node_level_y
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $birth_date
 * @property string|null $death_date
 * @property int $is_owner
 * @property int $gender 1 is man 0 is woman
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Address> $Addresses
 * @property-read int|null $addresses_count
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FamilyEvent> $FamilyEvents
 * @property-read int|null $family_events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Favorite> $Favorites
 * @property-read int|null $favorites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Memory> $Memories
 * @property-read int|null $memories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonMarriage> $PersonMarriages
 * @property-read int|null $person_marriages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Score> $Scores
 * @property-read int|null $scores_count
 * @method static \Database\Factories\PersonFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person query()
 * @property string|null $country_code
 * @property string|null $mobile
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereDeathDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereIsOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereNodeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person withoutTrashed()
 */
	class Person extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property int $person_marriage_id
 * @property int $child_id
 * @property string $child_kind
 * @property string $child_status
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\PersonMarriage $PersonMarriage
 * @property-read \App\Models\Person $WhoseChild
 * @method static \Database\Factories\PersonChildFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild whereChildId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild whereChildKind($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild whereChildStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild wherePersonMarriageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild withoutTrashed()
 */
	class PersonChild extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $person_id
 * @property string|null $profile_picture
 * @property string $physical_condition
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Person $Person
 * @method static \Database\Factories\PersonDetailFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail query()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail wherePhysicalCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail withoutTrashed()
 */
	class PersonDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $man_id
 * @property int $woman_id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property string $marriage_status
 * @property string $status
 * @property string|null $marriage_date
 * @property string|null $divorce_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Person> $Children
 * @property-read int|null $children_count
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\Person $Man
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonChild> $PersonChild
 * @property-read int|null $person_child_count
 * @property-read \App\Models\Person $Woman
 * @method static \Database\Factories\PersonMarriageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage query()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage whereDivorceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage whereManId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage whereMarriageDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage whereMarriageStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage whereWomanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage withoutTrashed()
 */
	class PersonMarriage extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property int $person_id
 * @property int $score_id
 * @property string $score_level
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\Person $Person
 * @property-read \App\Models\Score $Score
 * @method static \Database\Factories\PersonScoreFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore query()
 * @mixin \Eloquent
 * @property int|null $group_category_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereGroupCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereScoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereScoreLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore withoutTrashed()
 */
	class PersonScore extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $country_id
 * @property string $title
 * @property string|null $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Country $Country
 * @method static \Database\Factories\ProvinceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province query()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province withoutTrashed()
 */
	class Province extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Database\Factories\ScoreFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score query()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score withoutTrashed()
 */
	class Score extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $country_code
 * @property string $mobile
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property int $mobile_is_verified
 * @property string|null $password
 * @property string|null $sent_code
 * @property \Illuminate\Support\Carbon|null $code_expired_at
 * @property int $password_change_attempts
 * @property string|null $last_password_change_attempt
 * @property \Illuminate\Support\Carbon|null $last_attempt_at
 * @property string $status
 * @property string $role
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $avatar
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Favorite> $FavoriteCreates
 * @property-read int|null $favorite_creates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Favorite> $FavoriteEdites
 * @property-read int|null $favorite_edites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Memory> $MemoryCreates
 * @property-read int|null $memory_creates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Memory> $MemoryEdites
 * @property-read int|null $memory_edites_count
 * @property-read int|null $mobiles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonChild> $PersonChildCreates
 * @property-read int|null $person_child_creates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonChild> $PersonChildEdites
 * @property-read int|null $person_child_edites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Person> $PersonCreates
 * @property-read int|null $person_creates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Person> $PersonEdites
 * @property-read int|null $person_edites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonMarriage> $PersonMarriageCreates
 * @property-read int|null $person_marriage_creates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonMarriage> $PersonMarriageEdites
 * @property-read int|null $person_marriage_edites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Person> $Persons
 * @property-read int|null $persons_count
 * @property-read int|null $subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Client> $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Token> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notif> $Notifications
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCodeExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastAttemptAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastPasswordChangeAttempt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMobileIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePasswordChangeAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $sender_id
 * @property int $receiver_id
 * @property int $request_is_read  0=not read 1=read
 * @property int $request_status  1=active 2=refused 3=suspend
 * @property int|null $merge_sender_id
 * @property int|null $merge_receiver_id
 * @property int $merge_is_read  0=not read 1=read
 * @property string|null $merge_expired_at
 * @property int $merge_status  1=active 2=refused 3=suspend
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Person|null $mergeReceiver
 * @property-read \App\Models\Person|null $mergeSender
 * @property-read \App\Models\Person $receiver
 * @property-read \App\Models\Person $sender
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest query()
 * @method static \Database\Factories\UserMergeRequestFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest onlyTrashed()
 * @property string|null $request_expired_at
 * @property int $creator_id
 * @property int|null $editor_id
 * @property int $user_sender_id
 * @property int $node_sender_id
 * @property int $user_receiver_id
 * @property int $node_receiver_id
 * @property int $request_status_sender  1=Active 2=Cancel 3=Suspend
 * @property string|null $request_sender_expired_at
 * @property int $request_status_receiver 1=Active 2=Refused 3=Suspend
 * @property string|null $merge_ids_sender
 * @property string|null $merge_ids_receiver
 * @property int $merge_status_sender  1=Active 2=Cancel 3=Suspend
 * @property string|null $merge_sender_expired_at
 * @property int $merge_status_receiver  1=Active 2=Refused 3=Suspend
 * @property int $status  1=Active 2=Inactive 3=Suspend 4=Complete
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereMergeIdsReceiver($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereMergeIdsSender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereMergeSenderExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereMergeStatusReceiver($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereMergeStatusSender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereNodeReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereNodeSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereRequestSenderExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereRequestStatusReceiver($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereRequestStatusSender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereUserReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereUserSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest withoutTrashed()
 */
	class UserMergeRequest extends \Eloquent {}
}


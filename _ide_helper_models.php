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
 * @property int|null $area_id
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereAreaId($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address withoutTrashed()
 * @mixin \Eloquent
 */
	class Address extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $city_id
 * @property string $title
 * @property string|null $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\City $City
 * @method static \Database\Factories\AreaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area withoutTrashed()
 * @mixin \Eloquent
 */
	class Area extends \Eloquent {}
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryContent withoutTrashed()
 * @mixin \Eloquent
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
 * @property-read \App\Models\Province $Area
 * @property-read \App\Models\Province $Province
 * @method static \Database\Factories\CityFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City withoutTrashed()
 * @mixin \Eloquent
 */
	class City extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property int|null $biggest_person_id
 * @property string $title
 * @property string $clan_exact_family_name
 * @property string $clan_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClanMember> $Members
 * @property-read int|null $members_count
 * @property-read \App\Models\Person|null $OldestAncestry
 * @property-read \App\Models\Person $Person
 * @property-read \App\Models\User|null $User
 * @method static \Database\Factories\ClanFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan whereBiggestPersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan whereClanCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan whereClanExactFamilyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Clan withoutTrashed()
 * @mixin \Eloquent
 */
	class Clan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property int $clan_id
 * @property int|null $related_to
 * @property string $node_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\Clan|null $FamilyRelations
 * @property-read \App\Models\Person|null $Person
 * @method static \Database\Factories\ClanMemberFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember whereClanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember whereNodeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember whereRelatedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClanMember withoutTrashed()
 * @mixin \Eloquent
 */
	class ClanMember extends \Eloquent {}
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country withoutTrashed()
 * @mixin \Eloquent
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withoutTrashed()
 * @mixin \Eloquent
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereCategoryContentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereSelectedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyBoard withoutTrashed()
 * @mixin \Eloquent
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereEventDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent withoutTrashed()
 * @mixin \Eloquent
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereStar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite withoutTrashed()
 * @mixin \Eloquent
 */
	class Favorite extends \Eloquent {}
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
 * @method static \Database\Factories\GroupViewFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView withoutTrashed()
 * @mixin \Eloquent
 */
	class GroupView extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ip
 * @property string|null $date_attemp
 * @property int $today_attemp
 * @property int $total_attemp
 * @property string $status
 * @property string|null $expire_blocked_time
 * @property int $number_of_blocked_times
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Database\Factories\IpTrackingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereDateAttemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereExpireBlockedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereNumberOfBlockedTimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereTodayAttemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereTotalAttemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpTracking withoutTrashed()
 * @mixin \Eloquent
 */
	class IpTracking extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $ip_address
 * @property int $today_attempts
 * @property string|null $attempt_date
 * @property string|null $expire_blocked_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt whereAttemptDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt whereExpireBlockedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt whereTodayAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAttempt whereUserId($value)
 * @mixin \Eloquent
 */
	class LoginAttempt extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $person_id
 * @property int $category_content_id
 * @property int $group_view_id
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
 * @property-read \App\Models\GroupView $GroupView
 * @property-read \App\Models\Person $Person
 * @method static \Database\Factories\MemoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereCategoryContentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereGroupViewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereIsShownAfterDeath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory withoutTrashed()
 * @mixin \Eloquent
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NaslanRelationship withoutTrashed()
 * @mixin \Eloquent
 */
	class NaslanRelationship extends \Eloquent {}
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereBirthDate($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereNodeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereNodeLevelX($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereNodeLevelY($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person withoutTrashed()
 * @mixin \Eloquent
 * @property string|null $country_code
 * @property string|null $mobile
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereMobile($value)
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
 * @mixin \Eloquent
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail wherePhysicalCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail withoutTrashed()
 * @mixin \Eloquent
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
 * @mixin \Eloquent
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereScoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereScoreLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore withoutTrashed()
 * @mixin \Eloquent
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province withoutTrashed()
 * @mixin \Eloquent
 */
	class Province extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Database\Factories\QuestionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question withoutTrashed()
 * @mixin \Eloquent
 */
	class Question extends \Eloquent {}
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score withoutTrashed()
 * @mixin \Eloquent
 */
	class Score extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property int $day_number
 * @property int $volume_amount (MB)
 * @property string|null $description
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Database\Factories\SubscriptionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereDayNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereVolumeAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription withoutTrashed()
 * @mixin \Eloquent
 */
	class Subscription extends \Eloquent {}
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
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $avatar
 * @property-read \App\Models\UserAnswer|null $Answer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Favorite> $FavoriteCreates
 * @property-read int|null $favorite_creates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Favorite> $FavoriteEdites
 * @property-read int|null $favorite_edites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Memory> $MemoryCreates
 * @property-read int|null $memory_creates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Memory> $MemoryEdites
 * @property-read int|null $memory_edites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserMobile> $Mobiles
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserSubscription> $Subscriptions
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $creator_id
 * @property int $user_id
 * @property int $question_id
 * @property string $answer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\Question $Question
 * @property-read \App\Models\User $User
 * @method static \Database\Factories\UserAnswerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer withoutTrashed()
 * @mixin \Eloquent
 */
	class UserAnswer extends \Eloquent {}
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereMergeExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereMergeIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereMergeReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereMergeSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereMergeStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereRequestExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereRequestIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereRequestStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereUpdatedAt($value)
 * @method static \Database\Factories\UserMergeRequestFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereHassan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest withoutTrashed()
 * @property string|null $request_expired_at
 * @mixin \Eloquent
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereEditorId($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereUserReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMergeRequest whereUserSenderId($value)
 */
	class UserMergeRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $creator_id
 * @property int $user_id
 * @property string $mobile
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User $User
 * @method static \Database\Factories\UserMobileFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMobile withoutTrashed()
 * @mixin \Eloquent
 */
	class UserMobile extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $subscription_id
 * @property string|null $start_date
 * @property string|null $end_date
 * @property int|null $remain_volume (MB)
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\Subscription $Subscription
 * @method static \Database\Factories\UserSubscriptionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereRemainVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSubscription withoutTrashed()
 * @mixin \Eloquent
 */
	class UserSubscription extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $volume_extra_id
 * @property int|null $remain_volume (MB)
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @method static \Database\Factories\UserVolumeExtraFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra whereRemainVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra whereVolumeExtraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra withoutTrashed()
 * @mixin \Eloquent
 */
	class UserVolumeExtra extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property int $day_number
 * @property string|null $description
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Database\Factories\VolumeExtraFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra whereDayNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra withoutTrashed()
 * @mixin \Eloquent
 */
	class VolumeExtra extends \Eloquent {}
}


<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Category
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $parent_id
 * @property string $name
 * @property string $code
 * @property string $external_id
 * @method static Builder|Category whereCode($value)
 * @method static Builder|Category whereCreatedAt($value)
 * @method static Builder|Category whereExternalId($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereName($value)
 * @method static Builder|Category whereParentId($value)
 * @method static Builder|Category whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read Category|null $parent
 * @method static bool|null forceDelete()
 * @method static QueryBuilder|Category onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|Category whereDeletedAt($value)
 * @method static QueryBuilder|Category withTrashed()
 * @method static QueryBuilder|Category withoutTrashed()
 */
class Category extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'code', 'external_id'];

    public function parent(): HasOne
    {
        return $this->hasOne(self::class);
    }
}

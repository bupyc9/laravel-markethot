<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Offer
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property int $product_id
 * @property float $price
 * @property int $amount
 * @property string $article
 * @property string $external_id
 * @property-read \App\Product $product
 * @method static bool|null forceDelete()
 * @method static QueryBuilder|Offer onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|Offer whereAmount($value)
 * @method static Builder|Offer whereArticle($value)
 * @method static Builder|Offer whereCreatedAt($value)
 * @method static Builder|Offer whereDeletedAt($value)
 * @method static Builder|Offer whereExternalId($value)
 * @method static Builder|Offer whereId($value)
 * @method static Builder|Offer wherePrice($value)
 * @method static Builder|Offer whereProductId($value)
 * @method static Builder|Offer whereUpdatedAt($value)
 * @method static QueryBuilder|Offer withTrashed()
 * @method static QueryBuilder|Offer withoutTrashed()
 * @mixin \Eloquent
 */
class Offer extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['price', 'amount', 'article', 'external_id'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

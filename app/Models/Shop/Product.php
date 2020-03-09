<?php

namespace App\Models\Shop;

use App\Models\Traits\Filterable;
use App\Models\Traits\HasMedia\HasMedia;
use App\Models\Traits\HasMedia\HasMediaTrait;
use App\Models\Traits\Metatagable;
use App\Models\Traits\Navigable;
use App\Traits\UrlAliasGenerator;
use Fomvasss\Taxonomy\Models\Traits\HasTaxonomies;
use Fomvasss\UrlAliases\Traits\UrlAliasable;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Spatie\MediaLibrary\Models\Media;

class Product extends Model implements HasMedia
{
    use HasTaxonomies, UrlAliasable, UrlAliasGenerator, HasMediaTrait, Sortable, Filterable, Navigable, Metatagable;

    /** @var int */
    const TYPE_PRODUCT = 1;

    /** @var int */
    const TYPE_COLLECTION = 2;

    /** @var array */
    protected $guarded = ['id'];

    /** @var array */
    protected $mediaFieldsMultiple = ['images'];

    /** @var array */
    protected $mediaFieldsSingle = [];

    /** @var array */
    protected $casts = [
        'data' => 'array',
    ];

    /** @var array */
    protected $sortable = [
        'id', 'name', 'price', 'rating', 'created_at',
    ];

    /** @var array */
    protected $filterable = [
        'sku' => 'like',
        'name' => 'like',
        'price' => 'between',
        'publish' => 'in',
        'created_at' => 'between_date',
    ];

    /**
     * Кеширование результата метода getCalculatePrice().
     * @var null
     */
    private $cacheCalculatePrice = null;

    /**
     * Attributes values.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function values()
    {
        return $this->belongsToMany(Value::class)->withPivot('price');
    }

    /**
     * Reviews for product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }

    /**
     * Taxonomy term - main category.
     *
     * @return mixed
     */
    public function txCategory()
    {
        return $this->term('category_id', 'id')
            ->where('vocabulary', 'product_categories');
    }

    /**
     * Taxonomy terms - categories.
     *
     * @return mixed
     */
    public function txCategories()
    {
        return $this->termsByVocabulary('product_categories');
    }

    /**
     * Get group product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(ProductGroup::class, 'product_group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function sales()
    {
        return $this->morphToMany(Sale::class, 'model', 'saleables');
    }

    public function salesIsPublish()
    {
        return $this->morphToMany(Sale::class, 'model', 'saleables')->isPublish();
    }

    /**
     * WARNING: This is not relation!!!
     * Акции скидки, для главной категории товара.
     *
     * @return mixed
     */
    public function salesThroughCategory()
    {
        return $this->txCategory->sales();
    }

    public function salesThroughCategoryIsPublish()
    {
        return $this->txCategory->salesIsPublish;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    // TODO: check relation
    //public function collections()
    //{
    //    return $this->belongsToMany(self::class, 'product_collection', 'collection_id', 'product_id')
    //        ->where('type', '<>', self::TYPE_COLLECTION);
    //}

    /**
     * All product variants from self groups.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function variants()
    {
        return $this->group->products();
        //return $this->hasManyThrough(Product::class, ProductGroup::class);
    }

    /**
     * Все атрибуты по главной категории и ее предков.
     *
     * @return mixed
     */
    public function attrsAncestorsCategories()
    {
        $txCategory = $this->group && $this->group->product && $this->group->product->txCategory ? $this->group->product->txCategory : $this->txCategory;

        return $txCategory->ancestors->push($txCategory)->map(function ($term) {
            return $term->attrs;
        })->flatten()->unique('id');
    }

    /**
     * @return int
     */
    public function getReviewsRatingAttribute()
    {
        return $this->reviews->avg('rating');
    }

    public function scopeWithBase($query)
    {
        return $query->with('media', 'urlAlias', 'group', 'reviews', 'group.product.media', 'salesIsPublish', 'txCategory.salesIsPublish');
    }

    public function scopeIsPublish($query)
    {
        return $query->where('publish', 1);
    }

    /**
     * Строка значений указанных атрибутов для товара.
     *
     * @param string $delimiter
     * @return string
     */
    public function valuesStr(string $delimiter = ', ')
    {
        return $this->values->whereIn('attribute_id', variable('product_values_attributes_str', [1])) // TODO must by dynamic auto
            ->map(function ($v) {
                return $v->value. ' ' .$v->suffix;
            })->implode($delimiter);
    }

    /**
     * Товар-бестселлер.
     *
     * @return bool
     */
    public function isBestseller()
    {
        return $this->rating > variable('products_is_bestseller_rating', 100);
    }

    /**
     * Фасентые фильтры.
     *
     * @param $query
     * @param array $attributes
     * @return mixed
     */
    public function scopeFacetFilter($query, array $attributes = [])
    {
        $categoriesSlugs = $attributes['category'] ?? [];
        $query->when(count($categoriesSlugs), function ($q) use ($categoriesSlugs) {
            $q->whereHas('terms', function ($terms) use ($categoriesSlugs) {
                $terms->whereIn('system_name', $categoriesSlugs);
            });
        });
        unset($attributes['category']);

        foreach ($attributes as $attribute => $valuesSlugs) {
            $query->when(count($valuesSlugs), function ($q) use ($valuesSlugs, $attribute) {
                $q->whereHas('values', function ($values) use ($valuesSlugs, $attribute) {
                    $values->whereIn('slug', $valuesSlugs)->whereHas('attribute', function ($a) use ($attribute) {
                        $a->where('slug', $attribute);
                    });
                });
            });
        }
        return $query;
    }

    public function scopeHasNotPriceOld($query)
    {
        return $query->where('price_old', 0)->orWhereColumn('price', '>', 'price_old');
    }

    /**
     * TODO: DEPRECATED - use getCalculatePrice()
     * Старая цена на товар (самая большая - самая выгодная цена).
     *
     * @return int|mixed
     */
    public function priceOldCalc()
    {
        // Сумма скидки относительно заданой старой цене
        $salesDiscountTypePriceOld = isset($this->price_old) ? ($this->price_old - $this->price) : 0;

        // Сумма скидки от акции с указаным процентом скидки
        $sales = $this->sales->merge($this->salesThroughCategory)->where('type', Sale::TYPE_PRODUCT);

        $salesDiscountTypePercent = $sales->where('discount_type', Sale::DISCOUNT_TYPE_PERCENT)->max('discount');
        $discountSumSalesDiscountTypePercent = $salesDiscountTypePercent * $this->price / 100;

        // Сумма скидки от акции с указаной фиксированой суммой скидки
        $salesDiscountTypeSum = $sales->where('discount_type', Sale::DISCOUNT_TYPE_SUM)->max('discount');

        $discount = max($salesDiscountTypePriceOld, $discountSumSalesDiscountTypePercent, $salesDiscountTypeSum);

        return $discount > 0 ? ($this->price + $discount) : 0;
    }

    /**
     * Получить массив цен:
     * со скидкой, сумма скидки, акция,...
     */
    public function getCalculatePrice(string $field = null)
    {
        if (isset($this->cacheCalculatePrice)) {
            $max = $this->cacheCalculatePrice;
        } else {
            $prices[] = [
                'discount' => 0,
                'sale' => null,
                'price' => $this->price,
                'price_old' => 0,
            ];

            // Сумма скидки относительно заданой старой цене - не акция!
            if ($this->price_old > 0) {
                $prices[] = [
                    'discount' => $this->price_old - $this->price,
                    'sale' => null,
                    'price' => $this->price,
                    'price_old' => $this->price_old,
                ];
            }

            // Акции которые дают скидку на текущий товар (типа акции - скидка на тововар/категорию)
            $sales = $this->salesIsPublish->merge($this->salesThroughCategoryIsPublish())->where('type', Sale::TYPE_PRODUCT);

            // Тип скидки - Сумма скидки от акции с указаным процентом скидки
            if ($salesDiscountTypePercent = $sales->where('discount_type', Sale::DISCOUNT_TYPE_PERCENT)->sortByDesc('discount')->first()) {
                $discount = $salesDiscountTypePercent->discount * $this->price / 100;
                $prices[] = [
                    'discount' => $discount,
                    'sale' => $salesDiscountTypePercent,
                    'price' => $this->price - $discount,
                    'price_old' => $this->price,
                ];
            }

            // Тип скидки - Сумма скидки от акции с указаной фиксированой суммой скидки
            if ($salesDiscountTypeSum = $sales->where('discount_type', Sale::DISCOUNT_TYPE_SUM)->sortByDesc('discount')->first()) {
                $discount = $salesDiscountTypeSum->discount;
                $prices[] = [
                    'discount' => $discount,
                    'sale' => $salesDiscountTypeSum,
                    'price' => $this->price - $discount,
                    'price_old' => $this->price,
                ];
            }

            // Ищем цену с максимальной суммой скидки
            $max = $prices[0];
            foreach ($prices as $price) {
                if ($price['discount'] > $max['discount']) {
                    $max = $price;
                }
            }

            $this->cacheCalculatePrice = $max;
        }

        if ($field) {
            return $max[$field];
        }

        return $max;
    }

    /**
     * Изображение товара (или главного товара группы)
     * @param string $collectionName
     * @param string $conversionName
     * @return string
     */
    public function getFirstMediaUrl(string $collectionName = 'default', string $conversionName = ''): string
    {
        $media = $this->getFirstMedia($collectionName) ?: $this->group->product->getFirstMedia($collectionName);

        return $media ? $media->getUrl($conversionName) : '';
    }

    /**
     * Подписи табов на картке товара.
     *
     * @param string $tabId
     * @return string
     */
    public function getFrontTabTitle(string $tabId, string $default = '')
    {
        if (!empty($this->data['front'][$tabId])) {
            return $this->data['front'][$tabId];
        } elseif ($this->txCategory && !empty($this->txCategory->options['front'][$tabId])) {
            return $this->txCategory->options['front'][$tabId];
        }

        return $default;
    }

    /**
     * In that method you can set own algorithm
     * for calculate product rating. Enjoy!
     *
     * @return int
     */
    public function calculateRating(): int
    {
        return $this->orders->count();
    }

    /**
     * @param string|null $rawAliasPath
     * @return string
     */
    public function generateUrlAlias(string $rawAliasPath = null): string
    {
        if (empty($rawAliasPath)) {
            if ($this->txCategory) {
                $rawAliasPath = $this->getRawPathForNestedEntity($this->txCategory);
            }
            $rawAliasPath .= '/' . str_replace('/', '-', $this->name);
        }

        return trim($this->getUniqueAliasedPath($this, $rawAliasPath), '/');
    }

    /**
     * @return string
     */
    public function generateUrlSource(): string
    {
        return trim(route('product.show', $this, false), '/');
    }

    public function customMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('table')
            ->format('jpg')->quality($this->getMediaQuality())
            ->fit('crop', 500, 500);

        $this->addMediaConversion('preview')
            ->format('jpg')->quality($this->getMediaQuality())
            ->crop('crop-center', 436, 390);

        $this->addMediaConversion('header-modal')
            ->format('jpg')->quality(95)
            ->fit('crop', 212, 212);

        $this->addMediaConversion('favorite')
            ->format('jpg')->quality($this->getMediaQuality())
            ->fit('crop', 240, 240);

        $this->addMediaConversion('cart-page')
            ->format('jpg')->quality(95)
            ->fit('crop', 256, 236);
    }

    public function generateMetaTags(): array
    {
        return [
            'title' => '[product:name]: отзывы, купить в официальном интернет-магазине',//str_limit($this->name, 55, '') . ' - ' . config('app.name'),
            'description' => '[product:description]',
            'h1' => $this->name,
            //'og_title' => '[product:name]: отзывы, купить в официальном интернет-магазине',
            //'og_description' => '[product:description]',
        ];
    }

    public function generateMetaTagOgImgData(): array
    {
        return [
            'title' => $this->name,
            'subtitle' => $this->txCategory ? $this->txCategory->name : config('app.name'),
            'img' => '',
        ];
    }
}

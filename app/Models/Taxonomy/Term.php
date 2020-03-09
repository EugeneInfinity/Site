<?php

namespace App\Models\Taxonomy;

use App\Models\Shop\Attribute;
use App\Models\Shop\Product;
use App\Models\Shop\Sale;
use App\Models\Traits\HasMedia\HasMedia;
use App\Models\Traits\HasMedia\HasMediaTrait;
use App\Models\Traits\HasSafe;
use App\Models\Traits\Metatagable;
use App\Traits\UrlAliasGenerator;
use Fomvasss\LaravelEUS\Facades\EUS;
use Fomvasss\UrlAliases\Traits\UrlAliasable;
use Illuminate\Database\Eloquent\Builder;

class Term extends \Fomvasss\Taxonomy\Models\Term implements HasMedia
{
    use UrlAliasable, UrlAliasGenerator, HasMediaTrait, HasSafe, Metatagable;

    protected $mediaFieldsSingle = ['texture', 'image', 'file'];

    protected $mediaFieldsMultiple = ['images', 'files'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('weight', function (Builder $builder) {
            $builder->orderBy('weight', 'asc')->orderBy('id', 'asc');
        });
    }

    /**
     * Relation for attributes.
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function attrs()
    {
        return $this->morphedByMany(\App\Models\Shop\Attribute::class, 'termable');
    }

    /**
     * Предки.
     * @return mixed
     */
    public function attrsAncestorsCategories()
    {
        return $this->ancestors->push($this)/*->load('attrs')*/->map(function ($term) {
            return $term->attrs;
        })->flatten()->unique('id');
    }

    /**
     * Потомки.
     * @return mixed
     */
    public function attrsDescendantsCategories()
    {
        return $this->descendants->push($this)->load('attrs')->map(function ($term) {
            return $term->attrs;
        })->flatten()->unique('id');
    }

    public function products()
    {
        return $this->morphedByMany(\App\Models\Shop\Product::class, 'termable')->orderBy('id', 'desc');
    }

    /**
     * Товары в которых терм == главная категория.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productsHasCategory()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * @return mixed|null|string
     */
    public function getSlugAttribute()
    {
        return $this->system_name ?? '';
    }

    /**
     * @return string
     */
    public function generateUrlAlias()
    {
        return $this->getUniqueAliasedPathForNestedEntity($this);
    }

    /**
     * @return string
     */
    public function generateUrlSource()
    {
        if ($this->vocabulary == 'product_categories') {
            return trim(route('category.show', $this, false), '/');
        }

        return '';
    }

    public function generateMetaTags(): array
    {
        if (in_array($this->vocabulary, ['product_categories'])) {
            return array_merge($this->defaultMetaTags, [
                'title' => $this->parent_id ? null : '[term:name] Hipertin – купить в официальном интернет-магазине',
                //'title' => $this->parent_id ? '[term:facetFilter:firstAttr] [term:facetFilter:firstValue] Hipertin – купить в официальном интернет-магазине' : '[term:name] Hipertin – купить в официальном интернет-магазине',
                'description' => 'Заказать профессиональные средства для [ухода за волосами/стайлинга/окрашивания] Ипертин. Европейское качество. Бесплатная доставка от 5000 рублей.',
                'h1' => $this->name,
            ]);
        }
        return [];
    }

    public function generateSystemName()
    {
        if (in_array($this->vocabulary, ['order_statuses', 'payment_statuses', 'product_categories'])) {
            $slugSeparator = $this->vocabulary == 'product_categories' ? '-' : '_';
            return EUS::setEntity($this)
                ->setRawStr($this->name)
                ->setFieldName('system_name')
                ->setSlugSeparator($slugSeparator)
                ->get();
        }

        return '';
    }

    /**Для метатегов катигорий
     * @param $entity
     * @param $method
     * @param $attr
     * @return string
     */
    public function strTokenFacetFilter($entity, $method, $attr): string
    {

        $facet = \FacetFilter::first();

        // Если категория
        if ($facet['attr'] == 'category') {
            $terms = Term::byVocabulary('product_categories')->first();

            if ($attr == 'firstAttr') {
                return '';
            } elseif ($terms->count() && ($attr == 'firstValue')) {
                $value = $terms->where('system_name', $facet['value'])->first();
                return $value ? $value->name : '';
            }

            return '';
        }

        // Если фасетный фильтр
        $firstAttribute = Attribute::with('values')->where('slug', $facet['attr'])->first();
        if ($firstAttribute && ($attr == 'firstAttr')) {
            return $firstAttribute->title;
        } elseif ($firstAttribute && ($attr == 'firstValue')) {
            $value = $firstAttribute->values->where('slug', $facet['value'])->first();
            return $value ? $value->value : '';
        }

        return '';
    }

    public function generateMetaTagOgImgData(): array
    {
        return [
            'title' => $this->name,
            'subtitle' => $this->txCategory ? $this->txCategory->name : config('app.name'),
            'img' => '',
        ];
    }

    public function sales()
    {
        return $this->morphToMany(Sale::class, 'model', 'saleables');

    }

    public function salesIsPublish()
    {
        return $this->morphToMany(Sale::class, 'model', 'saleables')
            ->isPublish();
    }

    public function scopeIsPublish($query)
    {
        return $query->where('publish', 1);
    }

    public function statusAdminStr()
    {
        $styleClass = $this->options['admin_style'] ?? "label label-success";

        return "<sbodypan class='$styleClass'> $this->name </sbodypan>";
    }
}

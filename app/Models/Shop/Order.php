<?php

namespace App\Models\Shop;

use App\Models\Traits\Filterable;
use App\Models\Traits\Navigable;
use App\Models\User;
use Fomvasss\Taxonomy\Models\Traits\HasTaxonomies;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasTaxonomies, Filterable;

    /** @var int */
    const TYPE_ORDER = 1;

    /** @var int */
    const TYPE_CART = 2;

    /** @var array */
    protected $guarded = ['id'];

    /** @var array */
    protected $casts = [
        'data' => 'array',
    ];

    /** @var array */
    protected $dates = ['ordered_at'];

    /** @var array */
    protected $filterable = [
        'number' => 'like',
        'name' => 'like',
        'price' => 'between',
        'status' => 'in',
        'payment_status' => 'in',
        'ordered_at' => 'between_date',
    ];

    public static $deliveryMethods = [
        '' => '---',
        'cdek' => 'CDEK',
        'pickup' => 'Самовывоз с магазина',
        'courier' => 'Доставка курьером'
    ];

    public static $cdekTarifs = [
        '136' => 'Доставка до пункта самовывоза (136)',
        '234' => 'Экономичная доставка до пункта самовывоза (234)',
        '137' => 'Доставка "до двери" (137)',
        '233' => 'Экономичная доставка до двери (233)',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(['price', 'currency', 'quantity']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function previous()
    {
        $sortField = $this->getKeyName();
        return static::where($sortField, '<', $this->getKey())
            ->where('type', self::TYPE_ORDER)->orderByDesc($sortField)
            ->first();
    }

    public function next()
    {
        $sortField = $this->getKeyName();
        return static::where($sortField, '>', $this->getKey())
            ->where('type', self::TYPE_ORDER)->orderBy($sortField)
            ->first();
    }

    /**
     * @return mixed
     */
    public function txStatus()
    {
        return $this->term('status', 'system_name')
            ->where('vocabulary', 'order_statuses');
    }

    /**
     * @return mixed
     */
    public function txPaymentStatus()
    {
        return $this->term('payment_status', 'system_name')
            ->where('vocabulary', 'payment_statuses');
    }

    public function getDeliveryAddressStrAttribute()
    {
        $items = [
            $this->data['delivery']['name'] ?? '',
            $this->data['delivery']['phone'] ?? '',
            //$this->data['delivery']['email'] ?? '',
            $this->data['delivery']['region'] ?? '',
            $this->data['delivery']['city'] ?? '',
            $this->data['delivery']['zip_code'] ?? '',
        ];

        return implode(', ', array_filter($items, function ($item) {
            if (! empty($item)) { return $item; }
        }));
    }

    public function getDeliveryMethodStr()
    {
        return static::$deliveryMethods[$this->data['delivery']['method'] ?? ''] ?? '';
    }

    /**
     * @param $value
     * @return int|mixed
     */
    public function getNumberAttribute($value)
    {
        // TODO
        return $value ?? $this->id;
    }


    public function getPriceAttribute()
    {
        // TODO
        return $this->products->map(function ($p) {
            return $p->pivot->price * $p->pivot->quantity;
        })->sum();
    }

    public function getFinalSumStr($formated = true)
    {
        $res = $this->price + ($this->data['purchase']['delivery'] ?? 0) - ($this->data['purchase']['discount'] ?? 0);

        if ($formated) {
            return \Currency::format($res, 'RUB');
        }

        return $res;
    }
}

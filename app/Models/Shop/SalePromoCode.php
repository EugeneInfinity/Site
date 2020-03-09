<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class SalePromoCode extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeIsActive($query)
    {
        return $query->where('active', 1); // ADD check LIMIT
    }

    /**
     * For validation rules...
     * @param $query
     * @return mixed
     */
    public function scopeIsAvailable($query)
    {
        return $query->where('active', true)
            ->where(function ($q) {
                $q->whereDate('start_at', '<', \Carbon\Carbon::now())->orWhere('start_at', null);
            })
            ->where(function ($q) {
                $q->whereDate('end_at', '>', \Carbon\Carbon::now())->orWhere('end_at', null);
            })
            //->whereColumn('used_limit', '>', 'used_count')
            ->whereHas('sale', function ($sale) {
                $sale->isPublish();
            });
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: its
 * Date: 31.01.19
 * Time: 12:18
 */

namespace App\Helpers\ShoppingCart\StorageDrivers;

use App\Models\Shop\Order;
use App\Models\Shop\Product;

class CartEloquentStorageDriver implements CartStorageDriver
{
    protected $order;

    protected $items = null;

    /**
     * CartEloquentStorageDriver constructor.
     */
    public function __construct()
    {
        if ($this->items === null) {
            $this->order = Order::firstOrCreate([
                'user_id' => \Cart::getCurrentUserId(), //TODO: session now is not best way!
                'type' => Order::TYPE_CART,
                'status' => 'order_new',            // TODO safe status
                'payment_status' => 'payment_new',  // TODO safe status
            ]);

            $this->items = $this->order->products->mapWithKeys(function ($op) {
                return [$op->id => $op->pivot->quantity];
            })->toArray();
        }
    }

    /**
     * Returns list of product ids
     *
     * @return int[]
     */
    public function get(): array
    {
        return $this->items;
    }

    /**
     * Adds $amount product (s) with id $id
     *
     * @param int $id
     * @param int $amount
     */
    public function add(int $id, int $amount = 1): void
    {
        // Check on the existence of product in the Order-cart
        // If isset product in cart - increment "quantity"
        if ($existingProduct = $this->order->products->where('id', $id)->first()) {
            $quantityInCart = $existingProduct->pivot->quantity;
            $this->order->products()->updateExistingPivot($id, [
                'quantity' => $quantityInCart + $amount,
            ]);

            // If not isset product in cart - set "quantity" = 1
        } elseif ($product = Product::find($id)) {
            $this->order->products()->attach([$id => [
                'quantity' => $amount,
                'price' => $product->getCalculatePrice('price'),
            ]]);
        }

        if (isset($this->items[$id])) {
            $this->items[$id] += $amount;
        } else {
            $this->items[$id] = $amount;
        }
    }

    public function update(int $id, int $amount = 1): bool
    {
        // Check on the existence of product in the Order-cart
        // If isset product in cart - increment "quantity"
        if ($existingProduct = $this->order->products->where('id', $id)->first()) {
            if ($amount < 1 || $existingProduct->pivot->quantity < 1) {
                if ($this->items !== null) {
                    unset($this->items[$id]);
                }

                return $this->order->products()->detach($id) !== 0;
            }

            $this->order->products()->updateExistingPivot($id, [
                'quantity' => $amount,
            ]);

            // If not isset product in cart - set "quantity" = 1
        } elseif ($product = Product::find($id)) {
            $this->order->products()->attach([$id => [
                'quantity' => $amount,
                'price' => $product->getCalculatePrice('price'),
            ]]);
        }

        $this->items[$id] = $amount;

        return true;
    }

    /**
     * Removes $amount product (s) with id $id
     * If $amount is null, then the whole product is removed
     * Returns false if nothing has changed
     *
     * @param int $id
     * @param int|null $amount
     * @return bool
     */
    public function remove(int $id, ?int $amount = null): bool
    {
        // Check on the existence of product in the Order-cart
        // If isset product in cart - decrement "quantity" or attach all
        if ($existingProduct = $this->order->products->where('id', $id)->first()) {
            $quantityInCart = $existingProduct->pivot->quantity;
            if ($amount == 0 || $amount >= $quantityInCart) {
                if ($this->items !== null) {
                    unset($this->items[$id]);
                }

                return $this->order->products()->detach($id) !== 0;
            } else {
                if ($this->items !== null) {
                    $this->items[$id] = $quantityInCart - $amount;
                }

                return $this->order->products()->updateExistingPivot($id, [
                        'quantity' => $quantityInCart - $amount,
                    ]) !== 0;
            }
        }

        return false;
    }

    /**
     * Clear the cart
     * Returns false if cart was empty
     *
     * @return bool
     */
    public function clear(): bool
    {
        //if (empty($this->items)) {
        //    return false;
        //}

        $this->items = [];
        $this->order->products()->detach();

        return true;
    }
}
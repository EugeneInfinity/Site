<?php
/**
 * Created by PhpStorm.
 * User: its
 * Date: 31.01.19
 * Time: 12:17
 */

namespace App\Helpers\ShoppingCart;

use App\Helpers\ShoppingCart\StorageDrivers\CartStorageDriver;

class Cart
{
    protected $app;

    protected $config;

    protected $storageClass = null;

    protected $storage = null;

    protected $currentUserId = null;

    /**
     * Cart constructor.
     *
     * @param null $app
     */
    public function __construct($app = null)
    {
        if (!$app) {
            $app = app();   //Fallback when $app is not given
        }

        $this->app = $app;

        $this->config = $this->app['config'];
    }

    /**
     * @param string $storageDriverName
     * @return \App\Helpers\ShoppingCart\Favorite
     * @throws \Exception
     */
    public function storage(string $storageDriverName): self
    {
        $storageClass = $this->prepareStorageClass($storageDriverName);

        if ($this->storageClass !== $storageClass) {
            $this->storageClass = $storageClass;

            $this->storage = $this->app->make($this->storageClass);
        }

        if ($this->storage === null) {
            $this->storage = $this->app->make($storageClass);
        }

        return $this;
    }

    /**
     * @return \App\Helpers\ShoppingCart\Favorite
     * @throws \Exception
     */
    public function setDefaultStorage()
    {
        if ($defaultStorageDriverName = $this->config->get("shopping-cart.default")) {
            return $this->storage($defaultStorageDriverName);
        }

        throw new \Exception('Shopping cart default storage is not set!');
    }

    /**
     * Returns list of product ids
     *
     * @param array $storageNames
     * @param bool $clearAfterMerge
     * @return array
     * @throws \Exception
     */
    public function merge(array $storageNames, bool $clearAfterMerge = true): array
    {
        $storageNames = array_unique($storageNames);

        if (count($storageNames) > 1) {
            $firstStorageName = $storageNames[0];

            $firstStorageClass = $this->prepareStorageClass($firstStorageName);
            $firstStorage = $this->app->make($firstStorageClass);

            foreach ($storageNames as $storageName) {
                if ($storageName !== $firstStorageName) {
                    $storageClass = $this->prepareStorageClass($storageName);
                    $storage = $this->app->make($storageClass);

                    $items = $storage->get();
                    $clearAfterMerge ? $storage->clear() : null;
                    foreach ($items as $id => $amount) {
                        $firstStorage->add($id, $amount);
                    }
                }
            }

            return $firstStorage->get();
        }

        return [];
    }

    public function getIds(): array
    {
        return array_keys($this->get());
    }

    /**
     * Returns list of product ids
     *
     * @return int[]
     */
    public function get(): array
    {
        return $this->getStorage()->get();
    }

    /**
     * Adds $amount product (s) with id $id
     *
     * @param int $id
     * @param int $amount
     */
    public function add(int $id, int $amount = 1): void
    {
        $this->getStorage()->add($id, $amount);
    }

    /**
     * @param int $id
     * @param int $amount
     * @return bool
     * @throws \Exception
     */
    public function update(int $id, int $amount = 1): bool
    {
        return $this->getStorage()->update($id, $amount);
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
        return $this->getStorage()->remove($id, $amount);
    }

    /**
     * Clear the cart
     * Returns false if cart was empty
     *
     * @return bool
     */
    public function clear(): bool
    {
        return $this->getStorage()->clear();
    }


    /**
     * Get the total number of items in the cart.
     * So if you've added 2 books and 1 shirt,
     * it will return 3 items.
     *
     * @return int
     */
    public function count(): int
    {
        return array_sum($this->get());
    }

    /**
     * Get the total number of unique items in the cart.
     * So if you've added 2 books and 1 shirt,
     * it will return 2 items.
     *
     * @return int
     */
    public function uniqueCount(): int
    {
        return count($this->get());
    }

    /**
     * Get the calculated total of all items in the cart,
     * given there price and quantity.
     *
     * @return int
     */
    public function total(): int
    {
        return 0; // TODO
    }

    /**
     * @param mixed $currentUserId
     */
    public function setCurrentUserId($currentUserId): self
    {
        $this->currentUserId = $currentUserId;

        \Session::put('cart_user_id', $currentUserId);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrentUserId(int $defaultId = null)
    {
        return $this->currentUserId ?: \Auth::id() ?: \Session::get('cart_user_id', $defaultId);
    }

    /**
     * @return \App\Helpers\ShoppingCart\CartStorageDriver
     * @throws \Exception
     */
    protected function getStorage(): CartStorageDriver
    {
        if ($this->storage === null) {
            $this->setDefaultStorage();
        }

        if ($this->storage === null) {
            throw new \Exception('Shopping cart storage is not set!');
        }

        return $this->storage;
    }

    /**
     * @param string $storageDriverName
     * @return string
     * @throws \Exception
     */
    protected function prepareStorageClass(string $storageDriverName): string
    {
        $storageClass = $this->config->get("shopping-cart.storage_drivers.$storageDriverName");

        if (! class_exists($storageClass)) {
            throw new \Exception("Class '$storageClass' not found");
        }

        return $storageClass;
    }
}
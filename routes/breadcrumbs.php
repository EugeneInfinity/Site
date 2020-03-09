<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Главная', route_alias('home'));
});

Breadcrumbs::for('account.edit', function ($trail) {
    $trail->parent('home');
    $trail->push('Личные данные', route_alias('account.edit'));
});
Breadcrumbs::for('account.history', function ($trail) {
    $trail->parent('account.edit');
    $trail->push('История заказов', route_alias('account.history'));
});
Breadcrumbs::for('account.favorites', function ($trail) {
    $trail->parent('account.edit');
    $trail->push('Избранное', route_alias('account.favorites'));
});

// Home > [Category]
Breadcrumbs::for('category', function ($trail, $category) {
    $trail->parent('home');
    foreach ($category->ancestors as $ancestor) {
        $trail->push($ancestor->name, route_alias('categories.show', $ancestor));
    }
    $trail->push($category->name, route_alias('categories.show', $category));
});

// Home > [Category] > Product
Breadcrumbs::for('product', function ($trail, $product) {
    $trail->parent('category', $product->txCategory);
    $trail->push($product->name, route_alias('products.show', $product));
});

// Home > Page
Breadcrumbs::for('page', function ($trail, $page) {
    $trail->parent('home');
    $trail->push($page->name, route_alias('pages.show', $page));
});

// Home > Sale
Breadcrumbs::for('sale.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Акции', route_alias('sale.index'));
});

Breadcrumbs::for('sale.show', function ($trail, $sale) {
    $trail->parent('sale.index');
    $trail->push($sale->name, route_alias('sale.show', $sale));
});
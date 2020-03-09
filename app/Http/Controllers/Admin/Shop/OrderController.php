<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Http\Requests\Admin\Shop\OrderRequest;
use App\Models\Shop\Order;
use App\Models\Taxonomy\Term;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize("order.read");

        $orders = Order::orderBy('ordered_at', 'desc')
            ->with('txStatus', 'products', 'txPaymentStatus')
            ->filterable($request->get('filter', []))
            ->withCount('products')
            ->whereNotNull('ordered_at')
            ->where('type', Order::TYPE_ORDER) // TODO uncomment
            ->paginate();

        return view('admin.shop.orders.index', compact('orders'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::with('products.urlAlias')->findOrFail($id);

        return view('admin.shop.orders.edit', compact('order'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrderRequest $request, $id)
    {
        $this->authorize("order.update");

        $order = Order::findOrFail($id);

        $order->update($request->only('status', 'payment_status', 'comment'));
        $order->setAttribute('data->payment', $request->input('data.payment'));
        $order->setAttribute('data->delivery', $request->input('data.delivery'));
        $order->save();

        $destination = $request->session()->pull('destination', route('admin.orders.edit', $order));
        return redirect()->to($destination)
            ->with('success', trans('notifications.update.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->authorize("order.delete");

        $order = Order::findOrFail($id);
        $order->delete();

        $destination = $request->session()->pull('destination', route('admin.orders.index'));
        return redirect()->to($destination)
            ->with('success', trans('notifications.destroy.success'));
    }

    public function productDestroy ($orderId, $productId)
    {
        $this->authorize("order.update");

        $order = Order::findOrFail($orderId);
        $order->products()->detach($productId);

        return redirect()->route('admin.orders.edit', $order)
            ->with('success', trans('notifications.destroy.success'));
    }

    public function printed($id)
    {
        $this->authorize("order.read");

        $order = Order::findOrFail($id);

        return view('admin.shop.orders.print', compact('order'));
    }
}

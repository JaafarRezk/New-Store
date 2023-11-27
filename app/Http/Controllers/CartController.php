<?php
namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\CartItems;
use App\Models\Variation;
use App\Http\Traits\GeneralTrait;
use App\Http\Requests\StoreCartRequest;

class CartController extends Controller
{
    use GeneralTrait;
    public function index()
    {
        $user = auth()->user();
        $cart = $user->cart;
        if (!$cart) 
        {
            $cart = Cart::create(['user_id' => $user->id]);
        }
        $cartItems = $cart->items;
        $totalPrice = $cartItems->sum('price');
        return $this->returnData('Cart retrieved successfully', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
        ]);
    }

    public function store(StoreCartRequest $request)
    {
        $cart = auth()->user()->cart;
        $variation = Variation::findOrFail($request->variation_id);
        $item = $cart->items()->where('variation_id', $variation->id)->first();
        if ($request->quantity > 0 && $request->quantity <= $variation->quantity)
        {
            if ($item) {
                $item->quantity += $request->quantity;
                $item->price = $item->quantity * $variation->price;
                $item->save();
            } else {
                $item = new CartItems
                ([
                    'quantity' => $request->quantity,
                    'price' => $request->quantity * $variation->price,
                    'variation_id' => $request->variation_id,
                ]);
                $cart->items()->save($item);
            }
            $cartItems = $cart->items;
            $totalPrice = $cartItems->sum('price');
            $variation->decrement('quantity', $request->quantity);
            return $this->returnData('Product added to cart successfully', [
                'cartItems' => $cartItems,'totalPrice' => $totalPrice]);
        } else 
        {
            return $this->returnError('Quantity Error','Failed to add product to cart. ');
        }
    }

public function destroy($id)
{
    $item = CartItems::find($id);
    if (!$item) 
    {
        return $this->returnError('Cart item not found', 404);
    }
    $item->variation->increment('quantity', $item->quantity);
    $item->delete();
    $cart = auth()->user()->cart;
    $cartItems = $cart->items;
    $totalPrice = $cartItems->sum('price');
    return $this->returnData('Cart item deleted successfully', 
    ['cartItems' => $cartItems,'totalPrice' => $totalPrice,]);
}
}


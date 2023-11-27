<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SystemSettings;
use Illuminate\Support\Facades\Auth;
use App\Models\Order; 
use App\Models\User;
use App\Models\DeliveryOrder;
use App\Models\OrderItems;
use App\Http\Traits\GeneralTrait;
class OrderController extends Controller
{
    use GeneralTrait;
    public function store(Request $request)
{
    $user = Auth::user();
    $cart = $user->cart;
    $cartItems = $user->cart->items;
    $totalAmount = $cart->items->sum(function ($cartItem){return  $cartItem->price;});
    if ($user->address) {
        $order = new Order([
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'order_address_id' => $user->address->id,
            'status' => 'pending',
        ]);
        $order->save();
        foreach ($cartItems as $cartItem) {
            $orderItem = new OrderItems([
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
                'total_price' => $cartItem->price,
                'variation_id' => $cartItem->variation_id,
            ]);
            $order->items()->save($orderItem);
        }
        $cart->items()->delete(); 
        return $this->returnData('Order created successfully', ['order' => $order]);
    } else 
    {
        return $this->returnData('User address not found', [], 400);
    }
}
    public function index()
    {
        $orders = Order::all();
        $deliveryDrivers = User::where('role_id', 3)->get();
        $DeliveryOrder=DeliveryOrder::get();
        $setting = SystemSettings::first();
        return view('admin.order', compact('orders','setting','deliveryDrivers','DeliveryOrder'));
    }
    public function updateDriver(Request $request, $id)
        {
            $deliveryDriverId = $request->input('delivery_driver');
            $existingDeliveryOrder = DeliveryOrder::find($id);
            
            if($existingDeliveryOrder) 
            {
            $existingDeliveryOrder->update(['delivery_driver_id' => $deliveryDriverId,]);
                        }else
                        {
                            DeliveryOrder::create
                            ([
                                'order_id' => $id,
                                'delivery_driver_id' => $deliveryDriverId,
                                'delivery_date' => now(), 
                            ]);
                        }
                        return redirect()->route('orders.create');
        }
    public function show($id)
    {
        require_once base_path('vendor/tcpdf/tcpdf.php');
        $orders = DB::table('orders')
        ->select
        (
        'orders.id as order_id',
        'users.name as customer_name',
        'orders.total_amount',
        'orders.created_at as date',
        'orders.status as order_status',
        'orders.created_at as order_date',
        'order_addresses.city',
        'order_addresses.area',
        'order_addresses.street_address',
        'products.name as product_name',
        'order_items.quantity as quantity',
        'order_items.price  as price',
        'colors.name as color_name',
        'variations.size as size',
        'users.id as user_id'
        )
    ->join('users', 'users.id', '=', 'orders.user_id')
    ->join('order_addresses', 'order_addresses.id', '=', 'orders.order_address_id')
    ->join('order_items', 'order_items.order_id', '=', 'orders.id')
    ->join('variations', 'variations.id', '=', 'order_items.variation_id')
    ->join('products', 'products.id', '=', 'variations.product_id')
    ->join('colors', 'colors.id', '=', 'variations.color_id')
    ->where('orders.id', $id)
    ->get();
    $customer = User::where('id', $orders[0]->user_id)->first();
    $pdf = new \TCPDF();
    $view = view('admin.order_pdf', compact('orders','customer'))->render();
    $pdf->AddPage();
    $pdf->writeHTML($view, true, false, true, false, '');
    $pdf->Output("order_{$orders[0]->user_id}.pdf", 'I');
    }
    public function updateStatus(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) 
        {
            return redirect()->route('orders.create');
        }
        $order->status = $request->input('status');
        $order->save();
        return redirect()->route('orders.create');
    }
    public function destroy($id)
    {
        $order = Order::find($id);
        $order->delete();
        return redirect()->route('orders.create');
    }
}

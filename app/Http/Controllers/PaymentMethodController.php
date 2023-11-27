<?php
namespace App\Http\Controllers;
use App\Http\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Charge;
use App\Models\Order;
class PaymentMethodController extends Controller
{
    use GeneralTrait;
    public function processPayment(Request $request)
    {
        $user = Auth::user();
        $order = Order::find($request->order_id);
        if (!$order) 
        {
            return $this->returnData('Order not found', [], 404);
        }
        $paymentMethod = $request->payment_method;
        $amount = $order->total_amount;
    try {
            Stripe::setApiKey(config('services.stripe.secret'));
            if ($paymentMethod === 'cash')
            {
                $order->save();
                return $this->returnData('Payment processed successfully', ['order' => $order]);
            } elseif ($paymentMethod === 'delivery')
            {
                $order->status = 'delivered';
                $order->save();    
                $charge = Charge::create
                ([
                    'amount' => $amount * 100,
                    'currency' => 'USD', 
                    'source' => $request->stripe_token,
                ]);    
                return $this->returnData('Payment processed successfully', ['order' => $order]);
            } else 
            {
                return $this->returnError('Invalid payment method', [], 400);
            }
        } catch (\Exception $e) 
        {
            return $this->returnData('Payment failed: ' . $e->getMessage(), [], 500);
        }
    }
}

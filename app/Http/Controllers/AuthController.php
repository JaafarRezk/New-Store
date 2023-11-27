<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\SystemSettings;
use App\Models\Cart;
use Carbon\Carbon;
class AuthController extends Controller
{
    public function dashboard()
    {
        $usersCount = DB::table('users')->count();
        $cartsCount = DB::table('carts')->count();
        $productsCount = DB::table('products')->count();
        $completedOrdersCount = DB::table('orders')->where('status', 'delivered')->count();
        $reviewsCount = DB::table('reviews')->count();
        $bestSellingProduct = DB::table('order_items')
            ->join('variations', 'order_items.variation_id', '=', 'variations.id')
            ->join('products', 'variations.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->groupBy('products.name')
            ->orderByDesc('total_quantity')
            ->first();
        $lowestSellingProduct = DB::table('order_items')
            ->join('variations', 'order_items.variation_id', '=', 'variations.id')
            ->join('products', 'variations.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->groupBy('products.name')
            ->orderBy('total_quantity')
            ->first();
        $highestRevenueProduct = DB::table('order_items')
            ->join('variations', 'order_items.variation_id', '=', 'variations.id')
            ->join('products', 'variations.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.total_price) as total_revenue'))
            ->groupBy('products.name')
            ->orderByDesc('total_revenue')
            ->first();
        $currentMonthSales = DB::table('order_items')
            ->whereMonth('created_at', now()->month)
            ->sum('total_price');
        $currentYearSales = DB::table('order_items')
            ->whereYear('created_at', now()->year)
            ->sum('total_price');
        $setting = SystemSettings::first();

        $monthlySalesData = DB::table('orders')
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total_sales')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        $labels = $monthlySalesData->pluck('month');
        $salesAmounts = $monthlySalesData->pluck('total_sales');

        return view('admin.dashboard', compact(
            'usersCount', 'cartsCount', 'productsCount', 'completedOrdersCount', 'reviewsCount',
            'bestSellingProduct', 'lowestSellingProduct', 'highestRevenueProduct',
            'currentMonthSales', 'currentYearSales','setting','labels','salesAmounts'
        ));
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) 
        {
                $user = Auth::user();
                session(['user' => $user]);
                    if (Auth::user()->role_id == 1) 
                    {
                        return redirect('/dashboard');
                    } elseif (Auth::user()->role_id == 2) 
                    {
                        return redirect('/user_dashboard');
                    }
                
        }
                return back()->withErrors(['message' => 'Invalid email or password']);
    }
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'mobile' => 'required',
            'password' => 'required|min:6',
                ];    
        $messages = [
            'name.required' => 'Please enter your name',
            'email.required' => 'Please enter your email',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'Email is already taken',
            'mobile.required' => 'Please enter your mobile number',
            'password.required' => 'Please enter a password',
            'password.min' => 'Password should be at least 6 characters',
                    ];
            $validatedData = $request->validate($rules, $messages);
                $user = User::create
                ([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone_number' => $validatedData['mobile'],
            'gender' => $request->input('gender'),
            'birth' => $request->input('birthdate'),
            'password' => bcrypt($validatedData['password']),
            'role_id' => 2,
                ]);
        $cart = Cart::create
        ([
            'user_id' => $user->id,
        ]);
        $user = Auth::user();
        session(['user' => $user]);        
        return redirect('/user_dashboard');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}

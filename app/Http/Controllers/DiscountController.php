<?php
namespace App\Http\Controllers;
use App\Models\Discount;
use App\Models\SystemSettings;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Traits\GeneralTrait;
class DiscountController extends Controller
{
    use GeneralTrait;
    public function getDiscounts($id)
    {
        $product = Product::findOrFail($id);
        $variations = $product->variations;
        $discounts = collect();
        foreach ($variations as $variation) 
        {
            $discounts = $discounts->merge($variation->discount);
        }
        if ($discounts->isEmpty())
        {
            return $this->returnError('No Discounts Found',null,404);
        }
            return $this->returnData('',$discounts);

    }
    public function index()
    {
        $products =Product::join('variations', 'products.id', '=', 'variations.product_id')
            ->join('colors', 'variations.color_id', '=', 'colors.id')
            ->leftJoin('discounts', 'variations.id', '=', 'discounts.variation_id') 
            ->select('products.id as id', 'products.name as product_name', 'variations.id as variation_id', 'variations.price', 'variations.size', 'colors.name as color_name')
            ->selectRaw('(100 - ((MAX(discounts.discount) / variations.price) * 100)) as discount_percentage') 
            ->groupBy('products.id', 'variations.id', 'variations.price', 'variations.size', 'colors.name', 'products.category_id', 'products.name')
            ->get();
        $setting = SystemSettings::first();
        return view('admin.discount', compact('products','setting'));
    }
    public function create()
    {
        return view('discounts.create');
    }

    public function addDiscount(Request $request)
    {
        $validatedData = $request->validate
        ([
            'variation_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'discount' => 'required|numeric|min:0|max:100',
        ]);
        $discount = new Discount();
        $discount->variation_id = $validatedData['variation_id'];
        $discount->start_date = $validatedData['start_date'];
        $discount->end_date = $validatedData['end_date'];
        $discount->discount = $validatedData['discount'];
        $discount->save();
        return redirect()->back();
    }
        public function removeDiscount(Request $request)
    {
        $validatedData = $request->validate
        ([
            'variation_id' => 'required|integer',
        ]);
        $discount = Discount::where('variation_id', $validatedData['variation_id'])->first();
        if ($discount) 
        {
            $discount->delete();
        }
        return redirect()->back();
    }
}

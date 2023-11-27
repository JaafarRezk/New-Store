<?php

namespace App\Http\Controllers;

use App\Models\Variation;
use Illuminate\Http\Request;
use App\Models\SystemSettings;
use App\Models\Product;
use App\Http\Traits\GeneralTrait;
use App\Http\Requests\StoreVariationRequest;
use App\Models\Image;
class VariationController extends Controller
{
    use GeneralTrait;
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $variations = $product->variations()->with('reviews', 'images')->get();
        $total_rating = 0;
        $count = 0;
        foreach ($variations as $variation) 
        {
            $reviews = $variation->reviews;
            foreach ($reviews as $review) 
            {
                $total_rating += $review->rating;
                $count++;
            }
            //$average_rating = $count > 0 ? $total_rating / $count : 0;
            $average_rating_percentage = $total_rating / 5 * 100;
        }
       // $average_rating = $count > 0 ? $total_rating / $count : 0;
       // $average_rating_percentage = $average_rating / 5 * 100;
        if ($variations->isEmpty()) {
            return $this->returnError('No Variations Found', null, 404);
        }
        $variationImages = $variations->pluck('images')->flatten();
        return $this->returnData('', [
            'variations' => $variations,
            'average_rating_percentage' => $average_rating_percentage,
          //  'variationImages' => $variationImages,
        ]);
    }

    public function filter(StoreVariationRequest $request)
    {
        $products = Product::whereHas('variations', function ($query) use ($request) {
            if ($request->has('price')) {
                $query->where('price', $request->price);
            }
            if ($request->has('size')) {
                $query->where('size', $request->size);
            }
            if ($request->has('color')) {
                $query->whereHas('color', function ($query) use ($request) {
                    $query->where('name', $request->color);
                });
            }
        })->get();
        if ($products->isEmpty()) {
            return $this->returnError('Not Found Products', null, 404);
        }
        return $this->returnData('', $products);}

    public function create()
    {
        $products = \App\Models\Product::all();
        $colors = \App\Models\Color::all();
        $variations = \App\Models\Variation::all();
        $setting = SystemSettings::first();
        $variationImages = Variation::with('images')->get();
        return view('admin.variations', compact('products', 'colors', 'variations', 'setting', 'variationImages'));
    }
    

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required',
            'color_id' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'size' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' 
        ]);
    
        $variation = new Variation($validatedData);
        $variation->save();
    
        $imagesData = $request->file('images');

$images = [];

if (!empty($imagesData)) 
    {
    foreach ($imagesData as $imageData) {
        $path = $imageData->move('C:\wamp64\www\New_Store\New-Store\public\img', $imageData->getClientOriginalName()); 
        $url = asset('img/' . $imageData->getClientOriginalName()); 
        $image = new Image(['url' => $url]);
        $images[] = $image;
    }
    }
        $variation->images()->saveMany($images);
    
        return redirect()->route('variations.create');
}
    public function edit(Variation $variation)
    {
        $products = \App\Models\Product::all();
        $colors = \App\Models\Color::all();
        $variationImages = $variation->images;
        return view('admin.variation_edit', compact('variation', 'products', 'colors', 'variationImages'));
    }
    
    public function update(Request $request, Variation $variation)
{
    $validatedData = $request->validate([
        'product_id' => 'required',
        'color_id' => 'required',
        'quantity' => 'required',
        'price' => 'required',
        'size' => 'required',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    $variation->update($validatedData);

    $imagesData = $request->file('images');

    $images = [];

    if (!empty($imagesData)) {
        foreach ($imagesData as $imageData) {
            $path = $imageData->store('img', 'public');
            $url = asset('storage/' . $path);
            $image = new Image(['url' => $url]);
            $images[] = $image;
        }
    }

    $variation->images()->delete();
    $variation->images()->createMany($images);

    return redirect()->route('variations.create')->with('success', 'Variation updated successfully');
}
    

    public function delete(Variation $variation)
    {
        $variation->delete();
        return redirect()->route('variations.create');   

        // تنفيذ العمليات المطلوبة بعد الحذف بنجاح (مثل التوجيه أو إظهار رسالة)
    }
}

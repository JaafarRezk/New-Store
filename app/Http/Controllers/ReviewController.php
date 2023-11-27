<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\systemSettings;
use App\Models\Variation;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Traits\GeneralTrait;
class ReviewController extends Controller
{
    public function showReviewProducts()
    {
        $setting = SystemSettings::first();
        $products = Product::join('variations', 'products.id', '=', 'variations.product_id')
            ->join('colors', 'variations.color_id', '=', 'colors.id')
            ->leftJoin('reviews', 'variations.id', '=', 'reviews.variation_id')
            ->leftJoin('images', 'variations.id', '=', 'images.variation_id')
            ->select('products.id as id', 'products.name as product_name', 'variations.id as variation_id', 'variations.price', 'variations.size', 'colors.name as color_name')
            ->selectRaw('COUNT(reviews.id) as total_reviews')
            ->selectRaw('AVG(reviews.rating) as average_rating')
            ->selectRaw('GROUP_CONCAT(images.url) as image_urls')
            ->groupBy('products.id', 'variations.id', 'variations.price', 'variations.size', 'colors.name', 'products.category_id', 'products.name')
            ->get();
    
        return view('admin.ReviewAll', compact('products', 'setting'));
    }
    
    public function showProductWithReviews($productId)
    {
        $setting = SystemSettings::first();
        $product = Product::join('variations', 'products.id', '=', 'variations.product_id')
            ->join('colors', 'variations.color_id', '=', 'colors.id')
            ->leftJoin('reviews', 'variations.id', '=', 'reviews.variation_id')
            ->leftJoin('images', 'variations.id', '=', 'images.variation_id')
            ->select('products.id as id', 'products.name as product_name', 'variations.id as variation_id', 'variations.price', 'variations.size', 'colors.name as color_name')
            ->selectRaw('COUNT(reviews.id) as total_reviews')
            ->selectRaw('AVG(reviews.rating) as average_rating')
            ->selectRaw('GROUP_CONCAT(images.url) as image_urls')
            ->where('products.id', $productId)
            ->groupBy('products.id', 'variations.id', 'variations.price', 'variations.size', 'colors.name', 'products.category_id', 'products.name')
            ->first();
    
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }
    
        $comments = Review::where('variation_id', $product->variation_id)->get();
        return view('admin.Review', compact('product', 'setting', 'comments'));
    }   
        public function addComment(Request $request)
        {
            $request->validate
            ([
                'user_id' => 'required|numeric',
                'variation_id' => 'required|numeric',
                'comment' => 'required|string|max:255',
            ]);
            $review = new Review();
            $review->user_id = $request->user_id;
            $review->variation_id = $request->variation_id;
            $review->comment = $request->comment;
            $review->save();
            return redirect()->back();
        }
        use GeneralTrait;
    public function index($variation_id)
{
    $variation = Variation::findOrFail($variation_id);
    $reviews = $variation->reviews;
    $total_rating = 0;
    $count = 0;
    foreach ($reviews as $review) 
    {
        $total_rating += $review->rating;
        $count++;
    }
    $average_rating = $count > 0 ? $total_rating / $count : 0;
    $average_rating_percentage = $average_rating / 5 * 100;
    return $this->returnData('', 
    [
        'reviews' => $reviews,
        'average_rating_percentage' => $average_rating_percentage,
    ], 200);
}
    public function store(StoreReviewRequest $request, $variation_id)
    {
        $review = new Review;
        $review->user_id = auth()->user()->id;
        $review->variation_id = $variation_id;
        $review->rating = $request['rating'];
        $review->comment = $request['comment'];
        $review->save();
        return $this->returnSuccessMessage(['Review created successfully',$review->id]);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Category;
use App\Models\Product;
use App\Models\VendorProduct;

class CategoeyAPI extends Controller
{
    /*public function index( Request $request, string $id = null){
        $vendorIds = $request->user()->vendors->pluck('id');
        // return $vendorIds;
        
        if($id != null){
            $categories = Category::where('is_visible',1)->where('parent_id',$id)->whereIn('vendor_id', $vendorIds)->with('media')->get();
        }else{
            $categories = Category::where('is_visible',1)->where('parent_id',null)->whereIn('vendor_id', $vendorIds)->with('media')->get();
        }

        $categories->each(function($categorie) {
            $categorie->image_url = $categorie->getFirstMediaUrl('category');
        });

        return response()->json([
            'status' => 'true',
            'data' =>  $categories,
        ]);
    }*/

    public function index(Request $request, string $id = null)
    {
        // $vendorIds = $request->user()->vendors->pluck('id');
        $vendorIds = collect([$request->vendorId]);

        $availableProductIds = VendorProduct::whereIn('vendor_id', $vendorIds)
            ->where('availability', 1)
            ->pluck('product_id');

        // If no available products → return empty
        if ($availableProductIds->isEmpty()) {
            return response()->json([
                'status' => true,
                'data' => [],
            ]);
        }

        // Top-level categories OR subcategories depending on $id
        $categories = Category::where('is_visible', 1)
            ->when($id, function ($q) use ($id) {
                return $q->where('parent_id', $id);
            }, function ($q) {
                return $q->whereNull('parent_id');
            })

            // 🔥 IMPORTANT:
            // show only categories that have products available for this vendor
            ->whereHas('products', function ($q) use ($availableProductIds) {
                $q->whereIn('products.id', $availableProductIds);
            })

            ->with('media')
            ->get();

        // Set category image
        $categories->each(function ($cat) {
            $cat->image_url = $cat->getFirstMediaUrl('category');
        });

        return response()->json([
            'status' => true,
            'data' => $categories,
        ]);
    }

    
    // public function index(Request $request, string $id = null)
    // {
    //     // get all vendor IDs linked to this user 
    //     $vendorIds = $request->user()->vendors->pluck('id');
    
    //     // get products belonging to those vendors
    //     $products = Product::whereIn('vendor_id', $vendorIds)->with('categories.media');
    
    //     // filter by parent_id (subcategory or root)
    //     if ($id !== null) {
    //         $products->whereHas('categories', function ($q) use ($id) {
    //             $q->where('parent_id', $id)->where('is_visible', 1);
    //         });
    //     } else {
    //         $products->whereHas('categories', function ($q) {
    //             $q->whereNull('parent_id')->where('is_visible', 1);
    //         });
    //     }
    
    //     // fetch categories through products
    //     $categories = Category::whereHas('products', function ($q) use ($vendorIds) {
    //         $q->whereIn('vendor_id', $vendorIds);
    //     })
    //     ->with('media')
    //     ->when($id !== null, function ($q) use ($id) {
    //         $q->where('parent_id', $id)->where('is_visible', 1);
    //     }, function ($q) {
    //         $q->whereNull('parent_id')->where('is_visible', 1);
    //     })
    //     ->get();
    
    //     // add image_url from spatie media
    //     $categories->each(function ($categorie) {
    //         $categorie->image_url = $categorie->getFirstMediaUrl('category');
    //     });
    
    //     return response()->json([
    //         'status' => true,
    //         'data'   => $categories,
    //     ]);
    // }

}
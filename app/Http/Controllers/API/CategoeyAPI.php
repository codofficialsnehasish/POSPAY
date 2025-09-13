<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Category;
use App\Models\Product;

class CategoeyAPI extends Controller
{
    // public function index( Request $request, string $id = null){
    //     // $vendorIds = $request->user()->vendors->pluck('id');
    //     // return $vendorIds;
        
    //     if($id != null){
    //         $categories = Category::where('is_visible',1)->where('parent_id',$id)->with('media')->get();
    //     }else{
    //         $categories = Category::where('is_visible',1)->where('parent_id',null)->with('media')->get();
    //     }

    //     $categories->each(function($categorie) {
    //         $categorie->image_url = $categorie->getFirstMediaUrl('category');
    //     });

    //     return response()->json([
    //         'status' => 'true',
    //         'data' =>  $categories,
    //     ]);
    // }
    
    public function index(Request $request, string $id = null)
    {
        // get all vendor IDs linked to this user 
        $vendorIds = $request->user()->vendors->pluck('id');
    
        // get products belonging to those vendors
        $products = Product::whereIn('vendor_id', $vendorIds)->with('categories.media');
    
        // filter by parent_id (subcategory or root)
        if ($id !== null) {
            $products->whereHas('categories', function ($q) use ($id) {
                $q->where('parent_id', $id)->where('is_visible', 1);
            });
        } else {
            $products->whereHas('categories', function ($q) {
                $q->whereNull('parent_id')->where('is_visible', 1);
            });
        }
    
        // fetch categories through products
        $categories = Category::whereHas('products', function ($q) use ($vendorIds) {
            $q->whereIn('vendor_id', $vendorIds);
        })
        ->with('media')
        ->when($id !== null, function ($q) use ($id) {
            $q->where('parent_id', $id)->where('is_visible', 1);
        }, function ($q) {
            $q->whereNull('parent_id')->where('is_visible', 1);
        })
        ->get();
    
        // add image_url from spatie media
        $categories->each(function ($categorie) {
            $categorie->image_url = $categorie->getFirstMediaUrl('category');
        });
    
        return response()->json([
            'status' => true,
            'data'   => $categories,
        ]);
    }

}
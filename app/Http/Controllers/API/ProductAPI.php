<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariationOption;
use App\Models\Cart;

class ProductAPI extends Controller
{
    public function index(string $id = null){
        if($id != null){ // for product details
            $products = Product::with('addons.products')->with('complamentary.product')->where('is_visible',1)->where('id',$id)->first();
            $products->images = $products->getMedia('products-media');
        }else{ // for all products
            $products = Product::with('addons.products')->with('complamentary.product')->where('is_visible',1)->get();
        }

        $products->each(function($product) {
            $product->image_url = getProductMainImage($product->id);
        });


        return response()->json([
            'status' => 'true',
            'data' =>  $products,
        ]);
    }




        
    // public function get_products_by_category(string $id)
    // {
    //     $category = Category::find($id);
    //     // echo "<pre>";
    //     // print_r( $category);
    //     // die;
        
    //     if ($category) {
    //         $products = $category->products()
    //             ->with([
    //                 'addons.products',
    //                 'complamentary.product',
    //                 'variations.options', 
    //             ])
    //             ->where('is_visible', 1)
    //             ->get();

    //         $products->each(function ($product) {
    //             $product->image_url = getProductMainImage($product->id);
    //         });

    //         return response()->json([
    //             'status' => 'true',
    //             'data' => $products,
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => 'false',
    //             'message' => 'Category Not found',
    //         ]);
    //     }
    // }

    // public function get_products_by_category(string $id)
    // {
    //     $category = Category::with('subcategory')->find($id);

    //     if (!$category) {
    //         return response()->json([
    //             'status' => 'false',
    //             'message' => 'Category not found',
    //         ]);
    //     }

    //     // Get IDs of this category + its children
    //     $categoryIds = collect([$category->id])->merge($category->subcategory->pluck('id'));

    //     // Fetch products from all these categories
    //     $products = Product::whereHas('categories', function ($query) use ($categoryIds) {
    //             $query->whereIn('categories.id', $categoryIds);
    //         })
    //         ->with([
    //             'addons.products',
    //             'complamentary.product',
    //             'variations.options',
    //         ])
    //         ->where('is_visible', 1)
    //         ->get();

    //     // Attach image URLs
    //     $products->each(function ($product) {
    //         $product->image_url = getProductMainImage($product->id);
    //     });

    //     return response()->json([
    //         'status' => true,
    //         'category' => $category,
    //         'subcategories' => $category->subcategory,
    //         'products' => $products,
    //     ]);
    // }
    
    
    
     public function get_products_by_category(Request $request, string $id)
    {
        $category = Category::with('subcategory')->find($id);

        if (!$category) {
            return response()->json([
                'status' => 'false',
                'message' => 'Category not found',
            ]);
        }

        $categoryIds = collect([$category->id])->merge($category->subcategory->pluck('id'));
        $query = Product::whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
            ->with([
                'addons.products',
                'complamentary.product',
                'variations.options',
            ])
            ->where('is_visible', 1);
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }
        $products = $query->get();
        $products->each(function ($product) {
            $product->image_url = getProductMainImage($product->id);
        });

        return response()->json([
            'status' => true,
            'category' => $category,
            'subcategories' => $category->subcategory,
            'products' => $products,
        ]);
    }
    
    
    /*public function get_category_wise_product(Request $request)
    {
        // Fetch only parent categories
        $parentCategories = Category::with('subcategory')->whereNull('parent_id')->get();

        $result = [];

        foreach ($parentCategories as $category) {
            // Get category + child category IDs
            $categoryIds = collect([$category->id])
                ->merge($category->subcategory->pluck('id'));

                // Fetch products for this parent + children
             $query = Product::whereHas('categories', function ($query) use ($categoryIds) {
                    $query->whereIn('categories.id', $categoryIds);
                })
                ->with([
                    'addons.products',
                    'complamentary.product',
                    'variations.options',
                ])
                ->where('is_visible', 1);
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }
            $products = $query->get();
                

            // Attach image URLs
            $products->each(function ($product) {
                $product->image_url = getProductMainImage($product->id);
            });

            // Add to response
            $result[] = [
                'category' => $category,
                'products' => $products,
            ];
        }

        return response()->json([
            'status' => true,
            'data' => $result,
        ]);
    }*/
    
    public function get_category_wise_product(Request $request)
    {
        // get all vendor IDs linked to the logged-in user
        $vendorIds = $request->user()->vendors->pluck('id');
    
        if ($vendorIds->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No vendors assigned to this user',
            ], 400);
        }
    
        // fetch only parent categories that have products for these vendors
        $parentCategories = Category::with('subcategory')
            ->whereNull('parent_id')
            ->whereHas('products', function ($q) use ($vendorIds) {
                $q->whereIn('vendor_id', $vendorIds);
            })
            ->get();
    
        $result = [];
    
        foreach ($parentCategories as $category) {
            // get category + child category IDs
            $categoryIds = collect([$category->id])
                ->merge($category->subcategory->pluck('id'));
    
            // fetch products for these vendors and categories
            $query = Product::whereIn('vendor_id', $vendorIds)
                ->whereHas('categories', function ($query) use ($categoryIds) {
                    $query->whereIn('categories.id', $categoryIds);
                })
                ->with([
                    'addons.products',
                    'complamentary.product',
                    'variations.options',
                ])
                ->where('is_visible', 1);
    
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }
    
            $products = $query->get();
    
            if ($products->isNotEmpty()) {
                // attach image URLs
                $products->each(function ($product) {
                    $product->image_url = getProductMainImage($product->id);
                });
    
                // add to response only if products exist
                $result[] = [
                    'category' => $category,
                    'products' => $products,
                ];
            }
        }
    
        return response()->json([
            'status' => true,
            'data'   => $result,
        ]);
    }




 
//     public function get_category_wise_product(Request $request)
// {
//     // Fetch only parent categories
//     $parentCategories = Category::with('subcategory')->whereNull('parent_id')->get();

//     $result = [];

//     foreach ($parentCategories as $category) {
//         // Get category + child category IDs
//         $categoryIds = collect([$category->id])
//             ->merge($category->subcategory->pluck('id'));

//         // Fetch products for this parent + children
//         $query = Product::whereHas('categories', function ($query) use ($categoryIds) {
//                 $query->whereIn('categories.id', $categoryIds);
//             })
//             ->with([
//                 'addons.products',
//                 'complamentary.product',
//                 'variations.options',
//             ])
//             ->where('is_visible', 1);

//         // Apply search filter
//         if ($request->filled('search')) {
//             $query->where('name', 'like', '%' . $request->search . '%');
//         }

//         $products = $query->get();

//         // Attach image URLs
//         $products->each(function ($product) {
//             $product->image_url = getProductMainImage($product->id);
//         });

//         // Add to response
//         $result[] = [
//             'category' => $category,
//             'products' => $products,
//         ];
//     }

//     return response()->json([
//         'status' => true,
//         'data' => $result,
//     ]);
// }

    
     public function search_product(Request $request)
    {
        $query = Product::with([
            'addons.products',
            'complamentary.product',
            'variations.options',
        ])->where('is_visible', 1);

        // Filter by product name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // // Filter by brand
        // if ($request->filled('brand_id')) {
        //     $query->where('brand_id', $request->brand_id);
        // }

        // // Filter by category
        // if ($request->filled('category_id')) {
        //     $query->whereHas('categories', function ($q) use ($request) {
        //         $q->where('categories.id', $request->category_id);
        //     });
        // }

        // // Optional: Price range
        // if ($request->filled('min_price')) {
        //     $query->where('price', '>=', $request->min_price);
        // }
        // if ($request->filled('max_price')) {
        //     $query->where('price', '<=', $request->max_price);
        // }

        $products = $query->get();

        $products->each(function ($product) {
            $product->image_url = getProductMainImage($product->id);
        });




        return response()->json([
            'response' => true,
            'message' => 'get product searching data',
            'data' => [
                'products' => $products,
            ],
        ]);
    }


    /*public function get_product_by_barcode(Request $request)
    {
        if (!$request->filled('barcode')) {
            return response()->json([
                'status' => false,
                'message' => 'Barcode is required',
            ]);
        }

        $product = Product::with([
                'addons.products',
                'complamentary.product',
                'variations.options',
                'categories.subcategory', // 🔹 eager load categories & subcategories
            ])
            ->where('is_visible', 1)
            ->where('barcode', $request->barcode)
            ->first();

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'No product found for this barcode',
            ]);
        }

        // Attach image URL
        $product->image_url = getProductMainImage($product->id);

        // Assuming each product belongs to at least one category
        $category = $product->categories->first();
        $subcategories = $category ? $category->subcategory : [];

        return response()->json([
            'status' => true,
            'category' => $category,
            'subcategories' => $subcategories,
            'products' => [$product], // return as array for consistency
        ]);
    }*/

    public function get_product_by_barcode(Request $request)
    {
        if (!$request->filled('barcode')) {
            return response()->json([
                'status' => false,
                'message' => 'Barcode is required',
            ]);
        }

        $product_veriation_option = ProductVariationOption::where('barcode', $request->barcode)->first();
        if(!$product_veriation_option){
            return response()->json([
                'status' => false,
                'message' => 'No Product Found',
            ]);
        }

        $existingCartItem = Cart::where('user_id', $request->user()->id)
            ->where('product_id', $product_veriation_option->variation->product_id)
            ->where('variation_id', $product_veriation_option->variation->id)
            ->where('option_id', $product_veriation_option->id)
            ->first();

        if ($existingCartItem) {
            $existingCartItem->quantity += 1;
            $existingCartItem->save();
        } else {
            $product = $product_veriation_option->variation->product;
            $product_title = $product->name . '-' . $product_veriation_option->name;

            $existingCartItem = Cart::create([
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
                'variation_id' => $product_veriation_option->variation->id,
                'option_id' => $product_veriation_option->id,
                'quantity' => 1,
                'product_title' => $product_title,
            ]);
        }

        $existingCartItem->load('product');
        $existingCartItem->product->image_url = getProductMainImage($existingCartItem->product_id);
        $cartItems[] = $existingCartItem;

        return response()->json([
            'status' => true,
            'message' => 'Cart updated successfully.',
            'data' => $cartItems,
        ]);

    }

}
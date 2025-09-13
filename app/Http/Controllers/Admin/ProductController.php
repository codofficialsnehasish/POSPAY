<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Models\ProductVariation;
use App\Models\ProductVariationOption;
use App\Models\Brand;
use App\Models\Hsncode;
use App\Models\Unit;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use Illuminate\Support\Facades\Auth;

class ProductController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Product View', only: ['index']),
            new Middleware('permission:Product Basic Info Create', only: ['basic_info_create','basic_info_process']),
            new Middleware('permission:Product Basic Info Edit', only: ['basic_info_edit','basic_info_edit_process']),
            new Middleware('permission:Product Price Edit', only: ['price_edit','price_edit_process']),
            new Middleware('permission:Product Variation Edit', only: ['product_variation','add_product_variation','edit_product_variation','add_variation_option','store_variation_option','view_variation_option']),
            new Middleware('permission:Product Images Edit', only: ['productGalleryStore','productTempImages','delete_product_media','set_main_product_image']),
            new Middleware('permission:Product Addons & Complementary Edit', only: ['product_addons_edit','product_addons_update']),
            new Middleware('permission:Product Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Super Admin')) {
            $products = Product::all(); 
        } elseif ($user->hasRole('Vendor')) {
            $products = Product::where('vendor_id', $user->id)->get(); 
        } else {
            $products = collect(); 
        }
        $brands=  Brand::where('is_visible',1)->where('vendor_id',auth()->user()->id)->get();  
        $categories = Category::where('is_visible',1)->where('vendor_id',auth()->user()->id)->get();

        return view('admin.products.index', compact('products','brands','categories'));
    }


    public function product_filter(Request $request)
    {
        $query = Product::query();
        if ($request->has('brand_id') && $request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->has('category_id') && $request->category_id) {

            $query->whereHas('categories', function ($categoryQuery) use ($request) {
                $categoryQuery->where('category_id', $request->category_id);
            });
        }

 
        if ($request->has('hsn_id') && $request->hsn_id) {

            $query->where('hsn_id', $request->hsn_id);
        }
        $products = $query->orderBy('created_at', 'desc')
        ->get();
        $categories = Category::where('is_visible',1)->where('vendor_id',auth()->user()->id)->get();
        $brands = Brand::where('is_visible', 1)->where('vendor_id',auth()->user()->id)->get();
 
    
        return view('admin.products.index', compact('products', 'categories', 'brands',));
    }

    public function basic_info_create(){
        $categories = Category::where('is_visible',1)->where('parent_id',null)->where('vendor_id',auth()->user()->id)->get();
        $categories = $this->getCategoryTree();
        $parent_categories = Category::where('parent_id',null)->pluck('name','id');
        $brands=  Brand::where('is_visible',1)->where('vendor_id',auth()->user()->id)->get();
        $hsn_codes= Hsncode::where('is_visible',1)->where('vendor_id',auth()->user()->id)->get();
        // echo "<pre>";
        // print_r($categories);
        // die;
        return view('admin.products.basic_info',compact('categories','parent_categories','brands','hsn_codes'));
    }

    public function basic_info_process(Request $request){
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_type' => 'nullable|in:simple,attribute',
        ]);
         $vendor_id =Auth::guard('web')->user()->id;
        $product = new Product();
        $product->name = $request->product_name;
        $product->vendor_id = $vendor_id;
        // $product->product_type = $request->product_type ?? 'simple';
        $product->product_type = 'attribute';
        if($product->product_type == 'simple'){
            $product->barcode = $request->barcode;
            $product->measure = $request->measure;
        }else{
            $product->barcode = null;
            $product->measure = null;
        }
        $product->slug = createSlug($request->product_name, Product::class);
        $product->sort_description = $request->sort_description;
        $product->long_description = $request->long_description;
        $product->price = 0.00;
        $product->product_price = 0.00;
        $product->discount_rate = 0;
        $product->discount_price = 0.00;
        $product->gst_rate = 0;
        $product->gst_amount = 0.00;
        $product->total_price = 0.00;
        $product->veg = $request->veg_non_veg ?? 0;
        $product->is_available = $request->is_available;
        $product->is_special = $request->is_special ?? 0;
        $product->is_visible = $request->is_visible;
        $product->brand_id = $request->brand_id;
        $product->hsncode_id = $request->hsncode_id;
        $product->is_gst_included = $request->is_gst_included;

        
        $res = $product->save();

        if ($request->has('categories')) {  
            $product->categories()->sync($request->categories);
        }

        $variation= ProductVariation::create([
            'product_id'=>$product->id,
            'name'=> 'Measure',
            'variation_type'=>'radio_button',
            'option_display_type'=>'text',
            'show_images_on_slider'=>NULL,
            'use_different_price'=>0 ,
            'is_visible'=>1,
        ]);

        if($res){
            // return redirect(route('products.price-edit',$product->id))->with(['success'=>'Basic Information Added Successfully']);
            return redirect(route('products.variation',$product->id))->with(['success'=>'Basic Information Added Successfully']);
        }else{
            return redirect()->back()->with(['error'=>'Some error occurs!']);
        }
    }

    public function basic_info_edit(Request $request){
        // $categorys = Category::where('is_visible',1)->where('parent_id',null)->get();
        $product = Product::find($request->id);
        $selectedCategories = $product->categories->pluck('id')->toArray();
        $categories = Category::where('is_visible',1)->where('parent_id',null)->where('vendor_id',auth()->user()->id)->get();
        $categories = $this->getCategoryTree();
        $parent_categories = Category::where('parent_id',null)->where('vendor_id',auth()->user()->id)->pluck('name','id');
        $brands=  Brand::where('is_visible',1)->where('vendor_id',auth()->user()->id)->get();
        $hsn_codes= Hsncode::where('is_visible',1)->where('vendor_id',auth()->user()->id)->get();

        return view('admin.products.basic_info_edit',compact('categories','product','selectedCategories','parent_categories','brands','hsn_codes'));
    }



    
    public function getCategoryTree($parent_id = null, $sub_mark = '', $level = 0)
    {
        $categories = Category::where('parent_id', $parent_id)->where('vendor_id',auth()->user()->id)->get();
        $category_tree = [];

        foreach ($categories as $category) {
            $category->level = $level;
            $category_tree[] = $category;
            
            // Recursive call for subcategories
            $subcategories = $this->getCategoryTree($category->id, $sub_mark . '--', $level + 1);
            $category_tree = array_merge($category_tree, $subcategories);
        }

        return $category_tree;
    }

    public function basic_info_edit_process(Request $request){
        $product = Product::find($request->product_id);
        if($product->name != $request->product_name){
            $product->name = $request->product_name;
            $product->slug = createSlug($request->product_name, Product::class);
        }
        $product->product_type = $request->product_type;
        $product->product_type = 'attribute';
        if($product->product_type == 'simple'){
            $product->barcode = $request->barcode;
            $product->measure = $request->measure;
        }else{
            $product->barcode = null;
            $product->measure = null;
        }
        $product->sort_description = $request->sort_description;
        $product->long_description = $request->long_description;
        $product->veg = $request->veg_non_veg ?? 0;
        $product->is_available = $request->is_available;
        $product->is_special = $request->is_special?? 0;
        $product->is_visible = $request->is_visible;
        $product->brand_id = $request->brand_id;
        $product->hsncode_id = $request->hsncode_id;
        $product->is_gst_included = $request->is_gst_included;

        $res = $product->update();

        if ($request->has('categories')) {  
            $product->categories()->sync($request->categories);
        }

        if($res){
            // return redirect(route('products.price-edit',$product->id))->with(['success'=>'Basic Information Added Successfully']);
            return redirect(route('products.variation',$product->id))->with(['success'=>'Basic Information Added Successfully']);
        }else{
            return redirect()->back()->with(['error'=>'Some error occurs!']);
        }
    }

    public function price_edit(Request $request){
        if(request()->segment(4) == ''){
			return redirect(route('products.basic-info-create'))->with(['error'=>'Please Fill Basic Information']);
		}
        $product = Product::find($request->id);
        $hsn_codes= Hsncode::where('is_visible',1)->get();

        return view('admin.products.price_edit',compact('product','hsn_codes'));
    }

    public function price_edit_process(Request $request){
        $product = Product::find($request->product_id);
        if($product->product_type == 'simple'){
     
            $product->price = $request->product_price;
            $product->discount_rate = $request->discount_rate;
            // $product->discounted_price = $request->product_price - (($request->discount_rate / 100) * $request->product_price);
            $product->gst_rate = $request->gst_rate;
            $product->total_price = $request->total_price;
            // $product->gst_amount = ($request->gst_rate / 100) * $product->discounted_price;
            $product->discount_price = ($request->discount_rate / 100) * $request->product_price;
            $gstRate = $request->gst_rate/100;
            $product->gst_amount = ($request->total_price * $gstRate) / (1 + $gstRate);
            $product->product_price = $request->total_price - $product->gst_amount;
            $product->hsncode_id = $request->hsncode_id;
            $res = $product->update();
            if($res){
                return redirect(route('products.product-images-edit',$product->id))->with(['success'=>'Price Details Updated Successfully']);
            }else{
                return redirect()->back()->with(['error'=>'Some error occurs!']);
            }
        }else{
        
            $gstRate = $request->gst_rate/100;
            $product->gst_amount = ($request->total_price * $gstRate) / (1 + $gstRate);
            $product->product_price = $request->total_price - $product->gst_amount;
            $product->hsncode_id = $request->hsncode_id;
            $res = $product->update();

            return redirect(route('products.product-images-edit',$product->id))->with(['success'=>'Price Details Updated Successfully']);
        }
    }

    public function product_images_edit(Request $request){
        if(request()->segment(4) == ''){
			return redirect(route('products.basic-info-create'))->with('error','Please Fill Basic Information');
		}
        $product = Product::find($request->id);
        $product_images = $product->getMedia('products-media');
        return view('admin.products.product_images_edit',compact('product','product_images'));
    }

    public function product_images_process(Request $request){
        return redirect(route('products.product-addons-edit',$request->id))->with(['success'=>'Updated Successfully']);
    }


    
    public function product_variation(Request $request){
       
        if(request()->segment(4) == ''){
			return redirect(route('products.basic-info-create'))->with(['error'=>'Please Fill Basic Information']);
		}
        $product = Product::find($request->id);
        $units = Unit::all();

        $variations= ProductVariation::where('product_id',$product->id)->get();

        return view('admin.products.product_variations',compact('product','variations','units'));
    }

    
    public function add_product_variation(Request $request){
       

        $product = Product::find($request->product_id);

        // echo "<pre>";
        // print_r($request->all());
        // die;

         
        $validator = Validator::make($request->all(), [
            'name' => 'required','string','unique:product_variations,name',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }else{
           $variation= ProductVariation::create([
                'product_id'=>$product->id,
                'name'=>$request->name,
                'variation_type'=>$request->variation_type,
                'option_display_type'=>$request->option_display_type,
                'show_images_on_slider'=>$request->show_images_on_slider,
                'use_different_price'=>$request->use_different_price ? $request->use_different_price : 0 ,
                'is_visible'=>$request->is_visible,
            ]);

            if ($variation) {
                return response()->json([
                    'success' => true,
                    'message' => 'Variation created successfully.',
                ]);
            }

        }


        return view('admin.products.product_variations',compact('product','variations'));
    }



    public function edit_product_variation(Request $request)
    {
        $variation = ProductVariation::findOrFail($request->id);
        $product = Product::findOrFail($request->product_id);
        $existing_variations= ProductVariation::where('product_id',$product->id)->get();
        $html = view('admin.products.variations.edit_product_variation_form', compact('variation','existing_variations'))->render();
   
        return response()->json(['html' => $html]);
    }

    public function add_variation_option(Request $request)
    {
        $variation = ProductVariation::findOrFail($request->id);
        $product = Product::findOrFail($variation->product_id);
        $units = Unit::all();

        $html = view('admin.products.variations.add_variation_option_form', compact('variation','units'))->render();
   
        return response()->json(['html' => $html]);
    }

    public function store_variation_option(Request $request){
        $validator = Validator::make($request->all(), [
            'option_name' => 'nullable','string',
            'unit' => 'required',
            'measure' => 'required',
            'barcode' => 'required|unique:product_variation_options,barcode'
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }else{
           $option= ProductVariationOption::create([
                'variation_id'=>$request->variation_id,
                // 'name'=>$request->option_name,
                'name'=>$request->unit . ' ' . $request->measure,
                'quantity'=>$request->quantity,
                'barcode'=>$request->barcode,
                'mrp'=>$request->mrp,
                'price'=>$request->price,
                'discount_rate'=>$request->discount_rate,
                'discount_amount'=>$request->discount_amount,
                'no_discount'=>$request->no_discount ? $request->no_discount : 0 ,
            ]);

            if ($option) {
                return response()->json([
                    'success' => true,
                    'message' => 'Variation Option created successfully.',
                ]);
            }

        }



    }

    public function view_variation_option(Request $request)
    {
        $variation = ProductVariation::findOrFail($request->id);
        $options = ProductVariationOption::where('variation_id',$request->id)->get();


        $html = view('admin.products.variations.view_variation_options', compact('variation','options'))->render();
   
        return response()->json(['html' => $html]);
    }


    public function edit_variation_option(Request $request)
    {
        $option = ProductVariationOption::findOrFail($request->option_id);
        $variation = ProductVariation::findOrFail($request->variation_id);
        $units = Unit::all();

        $html = view('admin.products.variations.edit_variation_option_form', compact('option','variation','units'))->render();
   
        return response()->json(['html' => $html]);
    }


    public function update_variation_option(Request $request){
        $option= ProductVariationOption::findOrFail($request->option_id);
        $validator = Validator::make($request->all(), [
            'option_name' => 'nullable','string',
            'unit' => 'required',
            'measure' => 'required',
            'barcode' => 'required|unique:product_variation_options,barcode,'.$option->id,
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }else{

           
        //    $option->name =$request->option_name;
           $option->name =$request->unit . ' ' . $request->measure;
           $option->quantity =$request->quantity;
            $option->barcode=$request->barcode;
           $option->mrp =$request->mrp;
           $option->price =$request->price;
           $option->discount_rate =$request->discount_rate;
           $option->discount_amount =$request->discount_amount;
           $option->no_discount =$request->no_discount ? $request->no_discount : 0;
           $option->save();


            if ($option) {
                return response()->json([
                    'success' => true,
                    'message' => 'Variation Option updated successfully.',
                ]);
            }

        }



    }

    public function delete_variation_option(string $id){
        $option= ProductVariationOption::findOrFail($id);
        if (!$option) {
            return response()->json(['error' => 'Variation not found']);
        }

        $option->delete();
    
        return response()->json(['success' => 'Variation Option deleted successfully']);
    }

    public function delete_variation_and_options(string $id)
    {
        $variation = ProductVariation::with('options')->findOrFail($id);
    
        if (!$variation) {
            return response()->json(['error' => 'Variation not found']);
        }
    

        foreach ($variation->options as $option) {
            $option->delete();
        }

        $variation->delete();
    
        return response()->json(['success' => 'Variation and all its options deleted successfully']);
    }
    


    public function delete_variation(string $id){
        $option= ProductVariationOption::findOrFail($id);
        if (!$option) {
            return response()->json(['error' => 'Option not found']);
            
        }

        if($option){
            $option->delete();
            return response()->json(['success' => 'Option Deleted Successfully']);
     
        }else{
            return response()->json(['error' => 'You cannot delete this option because it has associated orders.'], 422);
        }
    }

    public function variation_edit_process(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        return redirect(route('products.product-images-edit',$request->product_id))->with(['success'=>'Variation Updated Successfully']);
    }
    
    


    /**
     * Store a newly created resource in storage.
     */
    public function productGalleryStore(Request $request)
    {
      
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ], [
            'file.required' => 'Please upload an image file.',
            'file.image' => 'The file must be an image.',
            'file.mimes' => 'The file must be a type of: jpeg, png, jpg, gif, svg, webp.',
            'file.max' => 'The file size must not exceed 2MB.',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('file')) {

            $file = $request->file('file');

            // Retrieve the Service model
            $product = Product::findOrFail($request->product_id);

            // Add the file to Spatie Media Library
            $media = $product
                ->addMedia($file)
                ->toMediaCollection('products-media');

            // Associate the custom file ID
            $media->setCustomProperty('file_id', $request->file_id);

            // Check if there is already a main image
            $mediaItems = $product->getMedia('products-media');
            $hasMain = $mediaItems->contains(function ($item) {
                return $item->getCustomProperty('is_main', false) === true;
            });

            // If no main image, set this one as main
            if (!$hasMain) {
                $media->setCustomProperty('is_main', true);
            }

            $media->save();

            return response()->json(['success' => 'File uploaded successfully!']);
        }
        return response()->json(['error' => 'File not uploaded!'], 500);
    
        // return response()->json(['paths' => $filepath, 'message' => 'Images uploaded successfully']);

    }
    /**
     * Store a newly created resource in storage.
     */
    public function productTempImages(Request $request)
    {
        $file_id = $request->file_id;
		$product_id = $request->product_id;

        $product = Product::find($product_id);
        $mediaItems = $product->getMedia('products-media');
        $media = $mediaItems->firstWhere('custom_properties.file_id', $file_id);
        
        if ($media) {
            $html = '<img src="' . $media->getUrl() . '" alt="">' .
                '<a href="javascript:void(0)" class="btn-img-delete btn-delete-product-img" data-file-id="' . $media->getCustomProperty('file_id') . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove this Item"><iconify-icon icon="mingcute:delete-2-line"></iconify-icon></a>';
    
            // Check if this is the main image
            if ($media->getCustomProperty('is_main', false)) {
                $html .= '<a href="javascript:void(0)" class="float-start btn btn-subtle-success btn-sm waves-effect btn-set-image-main badge bg-primary" style="padding: 0 4px;" data-file-id="' . $media->getCustomProperty('file_id') . '">Main</a>';
            } else {
                $html .= '<a href="javascript:void(0)" class="float-start btn btn-subtle-secondary btn-sm waves-effect btn-set-image-main badge bg-secondary" style="padding: 0 4px;" data-file-id="' . $media->getCustomProperty('file_id') . '">Main</a>';
            }
    
            return response()->json(['html' => $html]);
        }
    
        return response()->json(['error' => 'Media not found.'], 404);
    }

    public function delete_product_media(Request $request){
        $file_id=$request->file_id;
        $product_id=$request->product_id;
        $product = Product::findOrFail($product_id);
        $mediaItem = $product->getMedia('products-media')->firstWhere('custom_properties.file_id', $file_id);

        // Check if media exists
        if ($mediaItem) {
            // Prevent deleting the main image
            if ($mediaItem->getCustomProperty('is_main', false)) {
                return response()->json(['error' => 'You cannot delete the main image. Please set another image as main first.'], 403);
            }
            $mediaItem->delete(); // Delete the media
            return response()->json('Media deleted successfully!');
        }
        return response()->json('Media Not Found');
    }

    public function set_main_product_image(Request $request){
        $file_id = $request->file_id;
        $product_id = $request->product_id;

        // Fetch the product
        $product = Product::findOrFail($product_id);

        // Reset 'is_main' for all images in the gallery
        $mediaItems = $product->getMedia('products-media');
        foreach ($mediaItems as $media) {
            $media->setCustomProperty('is_main', false)->save();
        }

        // Set 'is_main' for the selected file
        $mainImage = $mediaItems->firstWhere('custom_properties.file_id', $file_id);
        if ($mainImage) {
            $mainImage->setCustomProperty('is_main', true)->save();
        }

        // Generate HTML for all media items
        $html = '';
        foreach ($mediaItems as $media) {
            $html .= '<li class="media" id="uploaderFile' . $media->getCustomProperty('file_id') . '">
                        <img src="' . $media->getUrl() . '" alt="">' .
                        '<a href="javascript:void(0)" class="btn-img-delete btn-delete-product-img" data-file-id="' . $media->getCustomProperty('file_id') . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove this Item"><iconify-icon icon="mingcute:delete-2-line"></iconify-icon></a>';
            if ($media->getCustomProperty('is_main')) {
                $html .= '<a href="javascript:void(0)" class="float-start btn btn-subtle-success btn-sm waves-effect btn-set-image-main badge bg-primary" style="padding-bottom: 0px;padding-top: 0px;padding-right: 4px;padding-left: 4px;" data-file-id="' . $media->getCustomProperty('file_id') . '">Main</a>';
            } else {
                $html .= '<a href="javascript:void(0)" class="float-start btn btn-subtle-secondary btn-sm waves-effect btn-set-image-main badge bg-secondary" style="padding-bottom: 0px;padding-top: 0px;padding-right: 4px;padding-left: 4px;" data-file-id="' . $media->getCustomProperty('file_id') . '">Main</a>';
            }
            $html .= '</li>';
        }

        return response()->json(['html' => $html]);
    }

    public function product_addons_edit(Request $request){
        if(request()->segment(4) == ''){
			return redirect(route('products.basic-info-create'))->with('error','Please Fill Basic Information');
		}
        $all_products = Product::where('is_visible',1)->get();
        $product = Product::find($request->id);
        return view('admin.products.product_addons_edit',compact('all_products','product'));
    }

    public function product_addons_update(Request $request){
        $product = Product::find($request->product_id);
        if ($request->has('addons')) {  
            $product->addon_products()->sync($request->addons);
        }

        if ($request->has('complamentary')) {  
            $product->complamentary_products()->sync($request->complamentary);
        }

        // products.product-addons-edit
        // return redirect()->back()->with('success','Updated Successfully');
        return redirect()->route('product.index')->with('success','Updated Successfully');
    }

    public function destroy(string $id){
        $product = Product::findOrFail($id);
        if($product){
            $res = $product->delete();
            if($res){
                return back()->with('success','Deleted Successfully');
            }else{
                return back()->with('error','Not Deleted');
            }
        }else{
            return back()->with('error','Not Found');
        }
    }
}

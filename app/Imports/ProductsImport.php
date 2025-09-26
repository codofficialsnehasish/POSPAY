<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationOption;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\HsnCode;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Illuminate\Support\Facades\Auth;

class ProductsImport implements ToModel, WithHeadingRow
{
    private $importedCount = 0;

    public function model(array $row)
    {
        // Skip if name is missing
        if (empty($row['name'])) {
            return null;
        }

        // find or create category
        $category = Category::firstOrCreate(['name' => $row['category']]);
        $brand    = Brand::firstOrCreate(['name' => $row['brand']]);
        $hsn_code = HsnCode::where('hsncode', $row['hsn_code'])->first();

        $product = Product::where('name', $row['name'])
                ->where('vendor_id', Auth::guard('web')->user()->id)
                ->where('brand_id', $brand?->id)
                ->where('hsncode_id',$hsn_code->id)
                ->first();

        if (!$product) {
            // Create product
            $product = Product::create([
                'name' => $row['name'],
                'slug' => createSlug($row['name'], Product::class),
                'product_type' => 'attribute',
                'vendor_id' => Auth::guard('web')->user()->id,
                'hsncode_id' => $hsn_code->id,
                'brand_id' => $brand->id,
                'brand_owner' => $row['brand_owner'],
                'sort_description' => $row['sort_description'],
                'is_available' => $row['product_availability'] === 'Available' ? 1 : 0,
                'is_gst_included' => $row['gst_included'] === 'Included' ? 1 : 0,
                'is_visible' => $row['visibility'] === 'Show' ? 1 : 0,
            ]);

            $variation= ProductVariation::create([
                'product_id'=>$product->id,
                'name'=> 'Measure',
                'variation_type'=>'radio_button',
                'option_display_type'=>'text',
                'show_images_on_slider'=>NULL,
                'use_different_price'=>0 ,
                'is_visible'=>1,
            ]);
        }else{
            $variation = ProductVariation::where('product_id',$product->id)->first();
        }

        if ($category) {  
            $product->categories()->sync($category->id);
        }

        // Create variation (if columns K–S exist)
        if (!empty($row['measure'])) {
            $option= ProductVariationOption::create([
                'variation_id'=>$variation->id,
                'name'=>$row['unit'] . ' ' . $row['measure'],
                'quantity'=>$row['quantity'],
                'barcode'=>$row['barcode'],
                'mrp'=>$row['mrp'],
                'price'=>$row['price'],
                'discount_rate'=>$row['discount_rate'],
                'discount_amount'=>$row['discount_amount'],
                'no_discount'=>$row['no_discount'] === 'Yes' ? 1 : 0 ,
            ]);
        }

        $this->importedCount++; // ✅ count this row as imported

        return $product;
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }
}

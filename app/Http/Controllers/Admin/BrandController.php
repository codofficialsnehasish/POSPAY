<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BrandController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Brand View', only: ['index','show']),
            new Middleware('permission:Brand Create', only: ['create','store']),
            new Middleware('permission:Brand Edit', only: ['edit','update']),
            new Middleware('permission:Brand Delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands=  Brand::latest()->where('vendor_id',auth()->user()->id)->get();
        return view('admin.brand.index',compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return view('admin.brand.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'is_visible' => 'required|in:0,1'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $brand=  Brand::create([
            'name'=>$request->name,
            'slug'=>createSlug($request->name, Brand::class),
            'vendor_id' => auth()->user()->id,
            'is_visible'=>$request->is_visible,
        ]);

        if ($brand) {
            return redirect()->route('brand.index')->with('success','Brand Created Successfully');
        } else {
            return redirect()->back()->with('error','Brand Not Added, try again!');
        }
        
    }


    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
        $data = Brand::findOrFail($id);

        return view('admin.brand.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,string $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'is_visible' => 'required|in:0,1'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
       $brand= Brand::findOrFail($id);
       $brand->name = $request->name ;
       $brand->slug = createSlug($request->name, Brand::class) ;
       $brand->vendor_id = auth()->user()->id;
       $brand->is_visible = $request->is_visible ;

        if ($brand->save()) {
            return redirect()->back()->with('success','Brand Updated Successfully');
        } else {
            return redirect()->back()->with('error','Brand Not Updated, try again!');
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand= Brand::findOrFail($id);

        if (!$brand) {
         return response()->json(['error' => 'Brand not found.'], 404);
        }
        $brand->delete();

        return response()->json(['success' => 'Brand deleted successfully.']);
        
    }
}

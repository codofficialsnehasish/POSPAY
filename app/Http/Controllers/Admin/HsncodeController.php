<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Hsncode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class HsncodeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Hsncode View', only: ['index']),
            new Middleware('permission:Hsncode Create', only: ['create','store']),
            new Middleware('permission:Hsncode Edit', only: ['edit','update']),
            new Middleware('permission:Hsncode Delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hsncodes=  Hsncode::latest()->where('vendor_id',auth()->user()->id)->get();
        return view('admin.hsncode.index',compact('hsncodes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('admin.hsncode.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'hsncode' => 'required',
            'gst_rate' => 'required',
            'is_visible' => 'required|in:0,1'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $hsncode=  Hsncode::create([
            'hsncode'=>$request->hsncode,
            'gst_rate'=>$request->gst_rate,
            'vendor_id' => auth()->user()->id,
            'is_visible'=>$request->is_visible,
        ]);

        if ($hsncode) {
            return redirect()->route('hsncode.index')->with('success','Hsncode Created Successfully');
        } else {
            return redirect()->back()->with('error','Hsncode Not Added, try again!');
        }
        
    }


    public function edit(string $id)
    {
        
        $data = Hsncode::findOrFail($id);

        return view('admin.hsncode.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,string $id)
    {

        $validator = Validator::make($request->all(), [
            'hsncode' => 'required|unique:hsncodes,hsncode,' . $request->id,
            'gst_rate' => 'required',
            'is_visible' => 'required|in:0,1'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
       $hsncode= Hsncode::findOrFail($id);
       $hsncode->hsncode = $request->hsncode ;
       $hsncode->gst_rate = $request->gst_rate ;
       $hsncode->vendor_id = auth()->user()->id;
       $hsncode->is_visible = $request->is_visible ;

        if ($hsncode->save()) {
            return redirect()->back()->with('success','Hsncode Updated Successfully');
        } else {
            return redirect()->back()->with('error','Hsncode Not Updated, try again!');
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $hsncode= Hsncode::findOrFail($id);

        if (!$hsncode) {
         return response()->json(['error' => 'Hsncode not found.'], 404);
        }
        $hsncode->delete();

        return response()->json(['success' => 'Hsncode deleted successfully.']);
        
    }
}

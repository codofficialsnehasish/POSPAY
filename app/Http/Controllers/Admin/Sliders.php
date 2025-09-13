<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Stores;
use App\Models\Slider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class Sliders extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Sliders View', only: ['index','show']),
            new Middleware('permission:Sliders Create', only: ['create','store']),
            new Middleware('permission:Sliders Edit', only: ['edit','update']),
            new Middleware('permission:Sliders Delete', only: ['destroy']),
        ];
    }
    
    public function index()
    {
        $sliders = Slider::all();
        return view("admin.slider.index",compact('sliders'));
    }

    public function create()
    {
        return view("admin.slider.create");
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:3000',
            'is_visible' => 'required|in:1,0',
        ], [
            'image.required' => 'Please Choose a Slider Image.',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $slider = new Slider();
        $slider->title = $request->title;
        $slider->description = $request->description;
        // $slider->store_id = $r->store_id;

        if ($request->hasFile('image')) {
            $slider->addMedia($request->file('image'))->toMediaCollection('slider');
        }

        $slider->is_visible = $request->is_visible;
        $res = $slider->save();
        if($res){
            return redirect()->back()->with('success','Data Added Successfully');
        }else{
            return redirect()->back()->with('error','Data Not Added');
        }
    }

    public function edit(string $id)
    {
        $slider = Slider::findOrFail($id);
        return view("admin.slider.edit",compact('slider'));
    }

    public function update(Request $request, string $id)
    {
        // return $request->file('image');die;
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:3000',
            'is_visible' => 'required|in:1,0',
        ], [
            'image.required' => 'Please Choose a Slider Image.',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $slider = Slider::findOrFail($id);
        $slider->title = $request->title;
        $slider->description = $request->description;
        // $slider->store_id = $r->store_id;

        if ($request->hasFile('image')) {
            $slider->clearMediaCollection('slider');
            $slider->addMedia($request->file('image'))->toMediaCollection('slider');
        }

        $slider->is_visible = $request->is_visible;
        $res = $slider->update();
        if($res){
            return redirect()->back()->with('success','Data Added Successfully');
        }else{
            return redirect()->back()->with('error','Data Not Added');
        }
    }

    public function destroy(string $id)
    {
        $slider = Slider::findOrFail($id);
        $res = $slider->delete();
        if($res){
            return redirect()->back()->with(['success'=>'Data Deleted Successfully']);
        }else{
            return redirect()->back()->with(['error'=>'Data Not Deleted']);
        }
    }

}

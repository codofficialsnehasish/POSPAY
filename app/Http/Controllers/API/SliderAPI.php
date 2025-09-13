<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Stores;
use App\Models\Slider;

class SliderAPI extends Controller
{
    public function index(){
        // $sliders = Slider::where('is_visible',1)->with('media')->get();
        $sliders = Slider::where('is_visible',1)->get();

        $sliders->each(function($slider) {
            $slider->image_url = $slider->getFirstMediaUrl('slider');
        });

        return response()->json([
            'status' => 'true',
            'data' =>  $sliders,
        ]);
    }
}
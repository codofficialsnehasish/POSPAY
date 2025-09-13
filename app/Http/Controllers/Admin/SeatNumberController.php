<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\SeatNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Coach;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SeatNumberController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Seat Number View', only: ['index','show']),
            new Middleware('permission:Seat Number Create', only: ['create','store']),
            new Middleware('permission:Seat Number Edit', only: ['edit','update']),
            new Middleware('permission:Seat Number Delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coaches = Coach::with(['vendor'])
        ->get();

        return view('admin.seat_number.index',compact('coaches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = User::role('Vendor')->latest()->get();
        $coaches = Coach::get();

    
        return view('admin.seat_number.create',compact('vendors','coaches'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:users,id',
            'method' => 'required|in:auto,manual',
        ]);

        if ($request->coach_option == 'new') {
            $request->validate([
                'new_coach_name' => 'required|string|max:10|unique:coaches,name',
            ]);


            $coach = Coach::create([
                'vendor_id' => $request->vendor_id,
                'name' => $request->new_coach_name
            ]);
            $coach_id = $coach->id;
        } else {
            $request->validate([
                'coach_id' => 'required|exists:coaches,id',
            ]);
            $coach_id = $request->coach_id;
            $coach = Coach::find($coach_id);
            $coach->vendor_id =$request->vendor_id;
            $coach->save();
        }


        if ($request->method == 'auto') {
            $request->validate([
                'seat_prefix' => 'required|string|max:5',
                'start' => 'required|integer|min:1',
                'end' => 'required|integer|gte:start',
            ]);

            for ($i = $request->start; $i <= $request->end; $i++) {
                $seatName = $request->seat_prefix . $i;
                SeatNumber::firstOrCreate([
                    'coach_id' => $coach_id,
                    'name' => $seatName
                ], [
                    'is_visible' => 1
                ]);
            }
        } else {
            $request->validate([
                'manual_seats' => 'required|string'
            ]);

            $seats = explode(',', $request->manual_seats);
            foreach ($seats as $seat) {
                $seatName = trim($seat);
                if (!empty($seatName)) {
                    SeatNumber::firstOrCreate([
                        'coach_id' => $coach_id,
                        'name' => $seatName
                    ], [
                        'is_visible' => 1
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Seat numbers added successfully!');
    }


 

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($coach_id)
    {
        $vendors = User::role('Vendor')->latest()->get();
        $coach = Coach::findOrFail($coach_id);
        $seats = SeatNumber::where('coach_id', $coach_id)->get();

        return view('admin.seat_number.edit', compact('coach', 'seats','vendors'));
    }

    /**
     * Update the specified resource in storage.
     */


    // public function update(Request $request)
    // {
    //     $request->validate([
    //         'method' => 'required|in:auto,manual',
    //         'vendor_id' => 'required|exists:users,id',
    //         'coach_name' => 'required|string|max:20',
    //     ]);
    //     $coach = Coach::findOrFail($request->coach_id);
    //     $coach->name = $request->coach_name;
    //     $coach->save();
    //     $vendor_id=  $request->vendor_id;
    //     $coach_id=  $coach->id;


    //     // Delete old seats
    //     SeatNumber::where('coach_id', $coach_id)
    //             ->delete();

    //     if ($request->method === 'auto') {
    //         $request->validate([
    //             'seat_prefix' => 'required|string|max:5',
    //             'start' => 'required|integer|min:1',
    //             'end' => 'required|integer|gte:start',
    //         ]);

    //         for ($i = $request->start; $i <= $request->end; $i++) {
    //             SeatNumber::create([
    //                 'coach_id' => $coach_id,
    //                 'name' => $request->seat_prefix . $i,
    //                 'is_visible' => 1,
    //             ]);
    //         }
    //     } else {
    //         $request->validate([
    //             'manual_seats' => 'required|string'
    //         ]);

    //         $seats = explode(',', $request->manual_seats);
    //         foreach ($seats as $seatName) {
    //             $seat = trim($seatName);
    //             if (!empty($seat)) {
    //                 SeatNumber::create([
    //                     'coach_id' => $coach_id,
    //                     'name' => $seat,
    //                     'is_visible' => 1,
    //                 ]);
    //             }
    //         }
    //     }

    //     return redirect()->route('seatnumber.index')->with('success', 'Seat numbers updated successfully.');
    // }

    public function update(Request $request)
    {
        $request->validate([
            'method' => 'required|in:auto,manual',
            'vendor_id' => 'required|exists:users,id',
            'coach_id' => 'required|exists:coaches,id',
            'coach_name' => 'required|string|max:20',
        ]);

        $coach = Coach::findOrFail($request->coach_id);
        $coach->name = $request->coach_name;
        $coach->vendor_id = $request->vendor_id;
        $coach->save();

        $vendor_id = $request->vendor_id;
        $coach_id = $coach->id;

        $hasSeatInput = false;

        if ($request->method === 'auto') {
            if ($request->filled(['seat_prefix', 'start', 'end'])) {
                $request->validate([
                    'seat_prefix' => 'required|string|max:5',
                    'start' => 'required|integer|min:1',
                    'end' => 'required|integer|gte:start',
                ]);

                $hasSeatInput = true;
                SeatNumber::where('coach_id', $coach_id)->delete();

                for ($i = $request->start; $i <= $request->end; $i++) {
                    SeatNumber::create([
                        'coach_id' => $coach_id,
                        'name' => $request->seat_prefix . $i,
                        'is_visible' => 1,
                    ]);
                }
            }
        } elseif ($request->method === 'manual') {
            if (!empty($request->manual_seats)) {
                $request->validate([
                    'manual_seats' => 'required|string',
                ]);

                $hasSeatInput = true;

                SeatNumber::where('coach_id', $coach_id)->delete();

                $seats = explode(',', $request->manual_seats);
                foreach ($seats as $seatName) {
                    $seat = trim($seatName);
                    if (!empty($seat)) {
                        SeatNumber::create([
                            'coach_id' => $coach_id,
                            'name' => $seat,
                            'is_visible' => 1,
                        ]);
                    }
                }
            }
        }

        if ($hasSeatInput) {
            return redirect()->route('seatnumber.index')->with('success', 'Coach and seat numbers updated successfully.');
        } else {
            return redirect()->route('seatnumber.index')->with('success', 'Only coach name updated. No seat numbers changed.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        
        $coach= Coach::findOrFail($id);
        if (!$coach) {
         return response()->json(['error' => 'Coach not found.'], 404);
        }
        $coach->delete();

        return response()->json(['success' => 'Coach and seat numbers deleted successfully.']);
    }
}
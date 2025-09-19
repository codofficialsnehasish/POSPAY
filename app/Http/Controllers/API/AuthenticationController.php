<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\User;

use Illuminate\Support\Facades\Hash;

use App\Services\SMSService;
use App\Models\UserVendor;
class AuthenticationController extends Controller
{
    public function __construct(protected SMSService $smsService){
        $this->smsService = $smsService;
    }

    public function login(Request $request)
    {

       
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|digits:10|regex:/^[6789]/',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }else{
            
            $user = User::where('phone', $request->phone_number)->first();
            
  

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found with this phone number.'
                ], 404);
            }
      
            if ($user) {
                
                if (!$user->hasRole('User') || $user->status != 1) {
                    return response()->json([
                        'status' => false,
                        'message' => 'User not authorized or inactive.'
                    ], 403);
                }
        
                if (!Hash::check($request->password, $user->password)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Incorrect password.'
                    ], 401);
                }
                $token = $user->createToken('auth_token')->plainTextToken;
                $user->load('vendor');
                return response()->json([
                    'status' => true,
                    'message' => 'Login successful.',
                    'token' => $token,
                    'user' => $user,
                    'vendor' => $user->vendor,
                ]);
            }
        }
    }

    protected function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|digits:10|regex:/^[6789]/',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::where('phone', $request->phone_number)->first();

        if (!$user) {
            return response()->json(['status' => 'false','message' => 'Please register your account.'], 401);
        }

        // If User exists then verify OTP
        $otp = Otp::where('user_id', $user->id)->latest()->first();

        // if(!$otp || $otp->otp != $request->otp){
        //     return response()->json(['status' => 'false','message' => 'Invalid OTP'], 401);
        // }

        // Check if token matches
        $token = $token = $user->createToken('Personal Access Token')->plainTextToken;
        if (!$token) {
            return response()->json(['status' => 'false','message' => 'Token not found'], 401);
        }

        // Token is valid, proceed with login
        $otpCreatedAt = Carbon::parse($otp->created_at);
        $currentTime = Carbon::now(); 
        $otpValidityDuration = 5; // OTP valid for 5 minute

        if ($otpCreatedAt->diffInMinutes($currentTime) > $otpValidityDuration) {
            return response()->json(['status' => 'false','message' => 'OTP has expired.'], 401);
        }

        $user->image = $user->getFirstMediaUrl('user-image');
        return response()->json([
            'status' => 'true',
            'message' => 'Login successful', 
            'token' => $token,
            'user'=>$user
        ]);
    }

    protected function register($phoneNumber)
    {
        // Check if user with this phone number already exists
        $existingUser = User::where('phone', $phoneNumber)->first();
        if ($existingUser) {
            return response()->json(['status' => 'false','message' => 'User already exists.'], 400);
        }
    
        // Generate OTP
        $user = User::create(['phone' => $phoneNumber,'role' => 'user','status' => 1]);

        if($phoneNumber == '8967464432'){
            $otp = 1234;
        }else{
            // $otp = generateOTP();
            $otp = 1234;
        }

        Otp::create(['user_id' => $user->id, 'otp' => $otp, 'created_at' => now()]);
        // $this->smsService->sendSMS('91'.$phoneNumber,$otp);

        return response()->json([
            'status' => 'true',
            'message' => 'Your account has been created.',
            'sent' => 'OTP sent to your phone number.',
            'note' => 'OTP is valid for 5 minute.',
            // 'token' => $token,
        ]);
    }
    

    protected function sendNewOTP($phoneNumber)
    {
        if($phoneNumber == '8967464432'){
            $otp = 1234;
        }else{
            // $otp = generateOTP();
            $otp = 1234;
        }
        $user = User::where('phone', $phoneNumber)->first();
        Otp::where('user_id', $user->id)->update(['otp' => $otp, 'created_at' => now()]);
        // $this->smsService->sendSMS('91'.$phoneNumber,$otp);
    
        return response()->json([
            'status' => 'true',
            'message' => 'New OTP generated successfully.',
            'sent' => 'An OTP has been sent to your phone number.',
            'note' => 'OTP is valid for 1 minute.',
            // 'token' => $token, // Send updated token in the response
        ]);
    }

    // public function update_profile(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'nullable|email|unique:users,email,' . $request->user()->id,
    //         'gender' => 'required|string|in:male,female,others',
    //         'address' => 'nullable|string',
    //         'latitude' => 'nullable|string',
    //         'longitude' => 'nullable|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 422);
    //     }

    //     $user = User::find($request->user()->id);
    //     $user->name = $request->name;
    //     $user->email = $request->email;
    //     $user->gender = $request->gender;
    //     $user->address = $request->address;
    //     $user->latitude = $request->latitude;
    //     $user->longitude = $request->longitude;

    //     // Update profile image if provided
    //     if ($request->has('image') && !empty($request->input('image'))) {
    //         $base64Image = $request->input('image');
    //         $user->clearMediaCollection('user-image');
    //         $user->addMediaFromBase64($base64Image)
    //         ->usingFileName(now()->format('Y-m-d_H-i-s') . '.png')
    //         ->toMediaCollection('user-image');
    //     }        

    //     $res = $user->update();
    //     $user->getFirstMediaUrl('user-image');

    //     if ($res) {
    //         return response()->json([
    //             'status' => 'true',
    //             'message' => 'Profile updated successfully.',
    //             'data' =>  $user,
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => 'false',
    //             'message' => 'Failed to update profile.',
    //         ]);
    //     }
    // }
    
    //  public function update_profile(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
    //         'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
    //         'email' => 'nullable|email|unique:users,email,' . $request->user()->id,
    //         'profile_image' => ['nullable', function ($attribute, $value, $fail) {
    //             if (!empty($value)) {
    //                 $size = (int)(strlen($value) * 3 / 4); // estimate size in bytes
    //                 if ($size > 2 * 1024 * 1024) {
    //                     $fail('The Profile Image must not be larger than 2 MB.');
    //                 }
    //             }
    //         }],
    //     ],[
    //         'profile_image.regex' => 'The Profile Image must be a valid base64 encoded JPEG, PNG, or JPG.',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 422);
    //     }

    //     $user = User::find($request->user()->id);
    //     $user->first_name = $request->first_name;
    //     $user->last_name = $request->last_name;
    //     $user->name = $request->first_name.' '.$request->last_name;
    //     $user->email = $request->email;

    //     if ($request->has('image') && !empty($request->input('image'))) {
    //         $base64Image = $request->input('image');
    //         $user->clearMediaCollection('user-image');
    //         $user->addMediaFromBase64($base64Image)
    //         ->usingFileName(now()->format('Y-m-d_H-i-s') . '.png')
    //         ->toMediaCollection('user-image');
    //     }        

    //     $res = $user->save();
    //     $user->getFirstMediaUrl('user-image');

    //     if ($res) {
    //         return response()->json([
    //             'status' => 'true',
    //             'message' => 'Profile updated successfully.',
    //               'data' => [
    //                 'data' =>  $user,
    //                 'profile_image'=> $user->getFirstMediaUrl('user-image') ? $user->getFirstMediaUrl('user-image') : NULL,
    //             ],
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => 'false',
    //             'message' => 'Failed to update profile.',
    //         ]);
    //     }
    // }


    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'nullable|email|unique:users,email,' . $request->user()->id,
            'phone' => 'nullable|digits:10|regex:/^[6789]/|unique:users,phone,'. $request->user()->id,
            'profile_image' => ['nullable', function ($attribute, $value, $fail) {
                if (!empty($value)) {
                    $size = (int)(strlen($value) * 3 / 4); // estimate size in bytes
                    if ($size > 2 * 1024 * 1024) {
                        $fail('The Profile Image must not be larger than 2 MB.');
                    }
                }
            }],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::find($request->user()->id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if ($request->has('profile_image') && !empty($request->input('profile_image'))) {
            $base64Image = $request->input('profile_image');
            $user->clearMediaCollection('user-image');
            $user->addMediaFromBase64($base64Image)
                ->usingFileName(now()->format('Y-m-d_H-i-s') . '.png')
                ->toMediaCollection('user-image');
        }

        $res = $user->save();
        $profileImage = $user->getFirstMediaUrl('user-image');

        if ($res) {
            return response()->json([
                'status' => 'true',
                'message' => 'Profile updated successfully.',
                'data' => [
                    'data' => $user,
                    'profile_image' => $profileImage ?: null,
                ],
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'Failed to update profile.',
            ]);
        }
    }


    // public function get_user_data(Request $request){
    //     $user= $request->user();
    //      $profileImage = $user->getFirstMediaUrl('user-image');
        
    //     return response()->json([
    //         'status' => 'true',
    //         'message' => 'get user details.',
    //         'data' => [
    //             'data' => $user,
    //             'profile_image' => $profileImage ?: null,
    //         ],
    //     ]);
    // }

    public function get_user_data(Request $request)
    {
        $user = $request->user();
    
        // ✅ User profile image
        $profileImage = $user->getFirstMediaUrl('user-image');
    
        // ✅ Get vendors linked to the user
        $vendor = UserVendor::with('vendor')->where('user_id', $user->id)->first();
        // return $vendor->vendor->getFirstMediaUrl('vendor-image');
    
        $vendor->image_url = $vendor->vendor->getFirstMediaUrl('vendor-image');
        
        // ✅ Add vendor image URL for each vendor
        // $vendors->each(function ($userVendor) {
        //     if ($userVendor->vendor) {
        //         $userVendor->vendor->image_url = $userVendor->vendor->getFirstMediaUrl('vendor-image');
        //     }
        // });
    
        return response()->json([
            'status' => 'true',
            'message' => 'get user details.',
            'data' => [
                'data' => $user,
                'profile_image' => $profileImage ?: null,
                'vendor' => $vendor,
            ],
        ]);
    }
    

    
    public function get_vendors(Request $request){
        $user= $request->user();
        $vendors = UserVendor::with('vendor')->where('user_id', $user->id)->get();

        // append image url for each vendor
        $vendors->each(function ($userVendor) {
            if ($userVendor->vendor) {
                $userVendor->vendor->image_url = $userVendor->vendor->getFirstMediaUrl('vendor-image');
            }
        });
        
        return response()->json([
            'status' => 'true',
            'message' => 'get user details.',
            'data' => [
                'vendors' => $vendors,
                
            ],
        ]);
    }
    
    public function update_vendor(Request $request){
        $user= $request->user();
        $user->vendor_id =  $request->vendor_id;
        $user->save();
        $profileImage = $user->getFirstMediaUrl('user-image');
        $vendor = $user->vendor->name;
        return response()->json([
            'status' => 'true',
            'message' => 'add vendor successfully.',
            'data' => [
                'data' => $user,
                'profile_image' => $profileImage ?: null,
                'vendor'=>$vendor
            ],
        ]);
    }
    
    
    public function add_pos_details(Request $request){
        $userId= $request->user()->id;
        
        $user = User::find($userId);
        $user->pos_number = $request->pos_number;
        $user->pos_password = $request->pos_password;
        $user->prod_app_key = $request->prod_app_key;
        $user->merchant_name = $request->merchant_name;
        $user->save();

        return response()->json([
            'status' => 'true',
            'message' => 'add pos details successfully.',
            'data' => [
                'data' => $user,

            ],
        ]);
    }
}

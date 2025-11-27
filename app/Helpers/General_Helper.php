<?php
    use Illuminate\Support\Str;
    use Carbon\Carbon;
    use App\Models\Setting;
    use App\Models\SeatNumber;

    if(!function_exists('general_settings')){
        function general_settings(){
            $setting = Setting::find(1);
            return $setting ?: null;
        }
    }

    
    if (!function_exists('check_status')){
        function check_status($status){
            if($status == 1){
                $str='<span class="bg-success-focus text-success-main px-24 py-4 rounded-pill fw-medium text-sm">Active</span>';
            }else{
                $str='<span class="bg-danger-focus text-danger-main px-24 py-4 rounded-pill fw-medium text-sm">Inactive</span>';
            }
            return $str;
        }
    }

    if (!function_exists('check_verified')){
        function check_verified($status){
            if($status == 1){
                $str = '<span class="text-success" title="Verified"><i class="fas fa-check-circle"></i></span></p>';
            }else{
                $str = '<span class="text-danger" title="Not Verified"><i class="fas fa-times-circle"></i></span></p>';
            }
            return $str;
        }
    }

    if (!function_exists('check_visibility')) {
        function check_visibility($val)
        {
            if($val==1){
                $str='<span class="bg-success-focus text-success-main px-24 py-4 rounded-pill fw-medium text-sm">Visible</span>';
            }else{
                $str='<span class="bg-danger-focus text-danger-main px-24 py-4 rounded-pill fw-medium text-sm">Invisible</span>';
            }
            return $str;
        }
    }

    if (!function_exists('check_uncheck')) {
        function check_uncheck($val1,$val2)
        {
            if($val1==$val2){
                $str='checked';
            }else{
                $str='';
            }
            return $str;
        }
    }

    if (!function_exists('generateToken')) {
        function generateToken($length = 32) {
            $bytes = random_bytes($length);
            $apiKey = base64_encode($bytes);
            $urlSafeApiKey = str_replace(['+', '/', '='], ['-', ''], $apiKey);
            return $urlSafeApiKey;
        }
    }

    if (!function_exists('get_user_name')) {
        function get_user_name($field, $id){
            $user = DB::table('users')->where($field, $id)->first();
            if ($user) {
                return $user->name;
            } else {
                return null;
            }
        }
    }

    if (!function_exists('get_category_name')) {
        function get_category_name($id){
            $category = DB::table('categories')->where('id', $id)->first();
            if ($category) {
                return $category->name;
            } else {
                return null;
            }
        }
    }



    if (!function_exists('get_join_green_date')) {
        function get_join_green_date($datetime)
        {
            if($datetime != ''){
                return format_datetime($datetime);
            }else{
                return '';
            }
        }
    }
    
    

    if(!function_exists('createSlug')) {
        function createSlug($name, $model)
        {
            $slug = Str::slug($name);
            $originalSlug = $slug;

            $count = 1;
            while ($model::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            return $slug;
        }
    }

    if (!function_exists('is_have_image')) {
        function is_have_image($image) {
            if($image){
                // return 'block';
                return '';
            }else{
                // return 'none';
                return 'd-none';
            }
        }
    }

    if (!function_exists('get_product_by_id')) {
        function get_product_by_id($product_id){
            $product = Products::find($product_id);
            return $product;
        }
    }

    if (!function_exists('get_product_price_by_id')) {
        function get_product_price_by_id($product_id){
            $product = Products::find($product_id);
            return $product->price;
        }
    }

    if (!function_exists('get_product_name')) {
        function get_product_name($product_id){
            $product = Products::find($product_id);
            if($product){
                return $product->title;
            }else{
                return '';
            }
        }
    }


    if (!function_exists('is_disabled')) {
        function is_disabled($value){
            if($value){
                return 'disabled';
            }else{
                return '';
            }
        }
    }

    if (!function_exists('get_country_name')){
        function get_country_name($id){
            $country = DB::table('location_countries')->where('id',$id)->value('name');
            return $country;
        }
    }

    if (!function_exists('get_state_name')){
        function get_state_name($id){
            $state = DB::table('location_states')->where('id',$id)->value('name');
            return $state;
        }
    }

    if (!function_exists('get_city_name')){
        function get_city_name($id){
            $city = DB::table('location_cities')->where('id',$id)->value('name');
            return $city;
        }
    }

    if (!function_exists('get_cgst')) {
        function get_cgst($gst_price) {
            if ($gst_price == 0) {
                return 0;
            }
    
            $cgst = $gst_price / 2;
            return round($cgst, 2);
        }
    }

    if (!function_exists('get_sgst')) {
        function get_sgst($gst_price) {
            if ($gst_price == 0) {
                return 0;
            }
    
            $sgst = $gst_price / 2;
            return round($sgst, 2);
        }
    }
    
    if (!function_exists('formatGSTRate')) {
        function formatGSTRate($rate,$is_csgst = 0)
        {
            if ($rate == 0) {
                return 0;
            }
            if($is_csgst){ return number_format($rate/2, 0) . '%'; }
            return number_format($rate, 0) . '%';
        }
    }

    if (!function_exists('format_price')) {
        function format_price($amount, $currency = '₹')
        {
            return $currency . number_format((float) $amount, 2, '.', ',');
        }
    }

    if (!function_exists('format_price_indian')) {
        function format_price_indian($amount, $currency = '₹')
        {
            $amount = number_format((float)$amount, 2, '.', '');
            $exploded = explode('.', $amount);
            $intPart = $exploded[0];
            $decimalPart = $exploded[1];

            $len = strlen($intPart);
            if ($len > 3) {
                $last3 = substr($intPart, -3);
                $rest = substr($intPart, 0, $len - 3);
                $rest = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $rest);
                $intPart = $rest . "," . $last3;
            }

            return $currency . $intPart . '.' . $decimalPart;
        }
    }
    
    if (!function_exists('get_seat_numbers')) {
        function get_seat_numbers($coach_id){
            $seats = SeatNumber::where('coach_id', $coach_id)->get();
            return $seats;
        }
    }

    function format_amount($amount)
    {
        // normalize input: remove commas/spaces, keep sign and dot
        $s = trim((string)$amount);
        $s = str_replace(',', '', $s);
        $s = preg_replace('/\s+/', '', $s);

        // handle empty or non-numeric input
        if ($s === '' || !is_numeric($s)) {
            return '0.00';
        }

        // keep sign
        $sign = '';
        if ($s[0] === '-' || $s[0] === '+') {
            $sign = $s[0];
            $s = substr($s, 1);
        }

        // Use sprintf with large precision to get a stable decimal string for the float value
        // (F gives non-locale formatting)
        $expanded = sprintf('%.14F', (float)$s); // 14 digits of fractional precision is usually enough

        // remove trailing zeros right of the decimal (but keep at least one 0 if integer)
        $expanded = rtrim($expanded, '0');
        if (substr($expanded, -1) === '.') {
            $expanded .= '0';
        }

        // split integer & decimal
        $parts = explode('.', $expanded);
        $integer = $parts[0];
        $decimal = $parts[1] ?? '';

        // Truncate decimal to 2 digits WITHOUT rounding
        $decimal = substr($decimal, 0, 2);
        $decimal = str_pad($decimal, 2, '0');

        // Format integer part with commas (thousands grouping)
        // handle possible leading zeros
        $integer = ltrim($integer, '0');
        if ($integer === '') {
            $integer = '0';
        }
        $integerFormatted = preg_replace('/\B(?=(\d{3})+(?!\d))/', ',', $integer);

        return $sign . $integerFormatted . '.' . $decimal;
    }




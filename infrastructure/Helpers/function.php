<?php

use Api\Options\Models\Option;
use Illuminate\Support\Str;
use Api\Users\Models\User;
use Illuminate\Support\Facades\Cache;

if (!function_exists('uuid')) {
    function uuid()
    {
        return (string)Str::uuid();
    }
}

if (!function_exists('domain')) {
    function domain()
    {
        return env('APP_URL', request()->getSchemeAndHttpHost());
    }
}

if (!function_exists('decode_file_json')) {
    function decode_file_json($path, $decode = true)
    {
        $content = file_get_contents($path);
        return json_decode($content, $decode);
    }
}

if (!function_exists('concatenation')) {
    function concatenation()
    {
        return \Config('config.concatenation');
    }
}

if (!function_exists('client')) {
    function client()
    {
        return \Config('config.client.main');
    }
}

if (!function_exists('getUser')) {
    function getUser()
    {
        return User::getUser();
    }
}

if (!function_exists('token')) {
    function token()
    {
        return request()->header('authorization');
    }
}

if (!function_exists('constants')) {
    function constants($key = null)
    {
        $value = config('constants.' . $key);
        if (isset($value)) {
            $filter = function ($items) use (&$filter) {
                if (is_array($items)) {
                    $data = [];
                    foreach ($items as $key => $value) {
                        $data[$key] = is_array($value) ? call_user_func($filter, $value) : __($value, [], $value);
                    }
                    return $data;
                }
                return __($items, [], $items);
            };
            return $filter($value);
        }
        return null;
    }
}

if (!function_exists('version_seeder')) {
    function version_seeder()
    {
        $version = Cache::get('version_seeder');
        if (!$version) {
            $version = Option::where('key', 'version')->value('value');
            Cache::set('version_seeder', $version);
        }
        return $version;
    }
}

if (!function_exists('lang')) {
    function lang()
    {
        $lang = @request()->header()['lang'][0];
        if (!$lang) {
            $lang = 'vi';
        };
        return $lang;
    }
}

if (!function_exists('format_number')) {
    function format_number($number = null)
    {
        if ($number) {
            return number_format($number);
        }
        return $number;
    }
}

if (!function_exists('show_cli')) {
    function show_cli(string $message, string $color = 'green')
    {
        switch ($color) {
            case 'green':
                $message ="\033[01;33m$message\033[0m" . PHP_EOL;
                break;
            case 'yellow':
                $message ="\033[01;33m$message\033[0m" . PHP_EOL;
                break;
            default:
                $message ="\033[01;33m$message\033[0m" . PHP_EOL;
                break;
        }
        echo $message;
    }
}

if (!function_exists('get_base64_size')) {
    function get_base64_size($base64)
    {
        //return memory size in B, KB, MB
        try {
            $size_in_bytes = (int) (strlen(rtrim($base64, '=')) * 3 / 4);
            $size_in_kb    = $size_in_bytes / 1024;
            $size_in_mb    = $size_in_kb / 1024;
    
            return $size_in_mb;
        } catch (Exception $e) {
            return $e;
        }
    }
}

if (!function_exists('render_channel')) {
    function render_channel($code = 'AWS001', $data = null, $message = null)
    {
        return [
            'status' => 'success',
            'code' => $code,
            'data' => $data,
        ];
    }
}


if (!function_exists('array_group')) {
    function array_group($data, string $key): array
    {
        $array = [];
        foreach ($data as $item) {
            $array[$item->$key][] = $item;
        }
        return $array;
    }
}

if (!function_exists('array_group')) {
    function gen_random_string(int $length = 10, string $prefix = ''): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVXYZ0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
        }
        return $prefix . $randomString;
    }
}

if (!function_exists('cal_days_in_year')) {
    function cal_days_in_year($year){
        $days=0; 
        for($month=1;$month<=12;$month++){ 
            $days = $days + cal_days_in_month(CAL_GREGORIAN,$month,$year);
        }
        return $days;
    }
}

if (!function_exists('is_real_date')) {
    function is_real_date($date) {
        $array = [];
        if (strpos($date, '/')) {
            $array = explode('/', $date);
        } else {
            $array = explode('-', $date);
        }
        if (count($array) !=3) {
            return false;
        }
        list($year, $month, $day) = $array;
        return checkdate($month, $day, $year);
    }
}

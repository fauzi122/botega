<?php

namespace App\Library;


use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class APIAccurate
{
    const TOKEN = 'aat.NTA.eyJ2IjoxLCJ1Ijo0ODI2NDAsImQiOjYzMjM4MiwiYWkiOjQ0MTU3LCJhayI6IjkxMDgwNDcxLWQ0OGUtNDUzYi1hMjgyLTRkOWI0ZGJmYWUzNSIsImFuIjoiQ3VzdG9tZXIgUmVsYXRpb25zaGlwIE1hbmFnZW1lbnQiLCJhcCI6ImE0MTY1NDNhLWVjZDYtNGE2Zi04N2UxLWZmZDcxMGM2ZDQ5YSIsInQiOjE3MDU5OTMwODMwMTd9.MD75db0GEidxk3At554Whh4GWfY1GgkdGdsV4jFZ//qxf37oWDCk77v35I5iyuGlJjyPRatiS2b0+rI/BWFIF5BSE8yCJ7S5CCu2EtHNlW14wP/T6mXxliKhj07sWeYurU+ZWtC/Lvl5EgK5X3dikYsGxDRXVc6fb/1RzgLQF+R6k6kUG75hI9uJvs88+aNwUgbdBMoDQkY=./BwM76/so3zrEdzV6BsXvGfeN4K0UxrEHQQ6WhK1uT4';
    const SIGNATURE_SECRET = 'HlMCdisl2TMYZU8ykhXicYODtwXrsjGTM2E0PCZKofItYVSUbZjlviAdMRGJARRc';
    const BASEURL = "https://zeus.accurate.id/accurate";
    /**
     * @param $url
     * @return \Illuminate\Http\Client\Response
     */
    public function get($url): \Illuminate\Http\Client\Response | string
    {

        $timestamp = Carbon::now('Asia/Jakarta')->getTimestampMs();
        try {
            return Http::withHeaders([
                'Authorization' => 'Bearer ' . self::TOKEN,
                'X-Api-Timestamp' => $timestamp,
                'X-Api-Signature' => $this->XAPISignature($timestamp)
            ])->withOptions(['verify' => false])->get(self::BASEURL . $url);
        } catch (\Exception $e) {
            echo "Kesalahan " . $e->getMessage() . ' ' . $e->getFile();
        }
        return  "";
    }

    public function post($url, $data): \Illuminate\Http\Client\Response
    {
        $timestamp = Carbon::now('Asia/Jakarta')->getTimestampMs();
        try {
            return Http::withHeaders([
                'Authorization' => 'Bearer ' . self::TOKEN,
                'X-Api-Timestamp' => $timestamp,
                'X-Api-Signature' => $this->XAPISignature($timestamp)
            ])->withOptions(['verify' => false])->post(self::BASEURL . $url, $data);
        } catch (\Exception $e) {
        }
        return "";
    }

    private function XAPISignature($timestamp)
    {
        $hmac = hash_hmac('sha256', $timestamp, self::SIGNATURE_SECRET, true);
        return base64_encode($hmac);
    }
}

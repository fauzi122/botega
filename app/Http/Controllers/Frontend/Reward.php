<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\GiftModel;
use App\Models\LogsModel;
use App\Models\MemberRewardModel;
use App\Models\RewardModel;
use App\Models\RewardRiwayat;
use App\Models\SliderModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Reward extends Controller
{
    public function index(){

        $cek = UserModel::query()
            ->select('users.*', 'b.level_name')
            ->leftJoin('level_member as b', 'users.level_member_id', '=', 'b.id')
            ->where('users.id', session('user')->id)
            ->first();

        $reward = RewardModel::query()
            ->leftJoin('member_rewards', function ($join) {
                $join->on('rewards.id', '=', 'member_rewards.reward_id')
                    ->where('member_rewards.user_id', '=', session('user')->id);
            })
            ->select('rewards.*', \DB::raw("COALESCE(member_rewards.status, NULL) AS status"), \DB::raw("COALESCE(member_rewards.user_id, NULL) AS user_id"))
            ->where('rewards.expired_at', '>=', Carbon::now('Asia/Jakarta'))
            ->orderBy('rewards.id','desc')
            ->get();

        // $reward = RewardModel::query()
        //     ->leftJoin('reward_riwayat', function ($join) {
        //         $join->on('rewards.id', '=', 'reward_riwayat.reward_id')
        //             ->where('reward_riwayat.user_id', '=', session('user')->id);
        //     })
        //     ->select('rewards.*', \DB::raw("COALESCE(reward_riwayat.status, NULL) AS status"), \DB::raw("COALESCE(reward_riwayat.user_id, NULL) AS user_id"))
        //     ->where('rewards.expired_at', '>=', Carbon::now('Asia/Jakarta'))
        //     ->orderBy('rewards.id','desc')
        //     ->get();

//        var_dump($reward);die();

        $gifts = GiftModel::query()
            ->select('gifts.*', 'b.name', 'b.price AS pricegift', 'b.description')
            ->leftJoin('gift_types as b', 'gifts.gift_type_id', '=', 'b.id')
            ->where('gifts.user_id', '=', session('user')->id)
            ->orderBy('gifts.id', 'desc')
            ->get();
//        test

        $data = [
            'title'=> 'Reward',
            'reward'=>$reward,
            'cek'=>$cek,
            'gifts'=>$gifts
        ];
//tes
        return view('frontend.home.reward',$data);
    }

    public function showQrCode($url)
    {
        return QrCode::format('png')->generate($url);
    }
    public function klaimReward($rewardId)
    {
        try {
            $poinakun = UserModel::query()->where('id', session('user')->id)->first();
            $poinreward = RewardModel::query()->where('id', $rewardId)->first();
            $cek = MemberRewardModel::query()->where('reward_id', $rewardId)->where('user_id', session('user')->id)->get();
            if ($cek->isNotEmpty()) {
                return response()->json(['message' => 'Maaf, Anda sudah klaim reward ini.'], 500);
            }

            if ($poinakun->points >= $poinreward->point) {
                $reward = RewardModel::find($rewardId);
                if ($reward) {
                    $data = [
                        'reward_id' => $rewardId,
                        'user_id' => session('user')->id,
                        'point' => $poinreward->point,
                        'status' => 1,
                        'created_at' => now()->setTimezone('Asia/Jakarta')
                    ];

                    $poinakun->points -= $poinreward->point;
                    $poinakun->save();

                    $isi = [
                        'description' => 'Melakukan klaim reward berupa '. $poinreward->name,
                        'url' => url('reward'),
                    ];
                    $title = 'Klaim reward '.$poinreward->name;
//            dispatch(new UpdateProfileJob($isi, $user_id, $title));
                    $log = [
                        'actions' => $title,
                        'payload' => json_encode($isi),
                        'user_id' => session('user')->id,
                        'created_at' => Carbon::now('Asia/Jakarta')
                    ];

                    LogsModel::query()->insert($log);



                    MemberRewardModel::query()->insert($data);
                    // RewardRiwayat::query()->insert($data);
                    return response()->json(['message' => 'Reward berhasil diklaim.']);
                } else {
                    return response()->json(['message' => 'Gagal melakukan klaim reward.'], 500);
                }
            } else {
                return response()->json(['message' => 'Maaf, poin Anda tidak cukup.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal melakukan klaim reward.'], 500);
        }
    }

    public function image($id){
        $pi = RewardModel::find($id);
        if($pi == null) abort(404);

        $fn =  $pi->path_image;
//        var_dump($fn);die();
        if(!Storage::exists($fn) && $fn != ''){
            abort(404);
        }
        $content = Storage::get($fn);
        return response($content, headers: [
            'Content-type'=>'image/png'
        ]);
    }


}

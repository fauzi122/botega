<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResumeDashboardModel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return view("admin.dashboard.dashboard", [
            'resume' => $this->getResume()
        ]);
    }

    private function getResume(){
        $r = ResumeDashboardModel::get();
        $ret = [];
        foreach ($r as $k=>$c){
            $ret[$c->kunci] = $c->nilai;
        }
        return $ret;
    }

}

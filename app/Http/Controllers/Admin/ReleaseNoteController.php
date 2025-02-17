<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\ValidatedPermission;
use App\Models\ReleaseNote;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReleaseNoteController extends Controller
{
    public function statusSolved(Request $request)
    {
        // $request->id => array of ID
        $ids = $request->input('id');
        $updated = ReleaseNote::whereIn('id', $ids)
            ->whereIn('tipe', [1, 2])
            ->update(['tipe' => 3]);

        return response()->json([
            'data' => $updated
        ]);
    }

    /**
     * Hitung total record di setiap step.
     * step = 1 (Improvement),
     * step = 2 (Bug),
     * step = 3 (Solved)
     */
    public function countTab($step = 0)
    {
        $count = ReleaseNote::query()
            ->where('tipe', $step)
            ->count();

        return $count;
    }
    
    public function dataSource()
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_REWARD)) {
            return [];
        }
        $id = \request('id');
        return datatables(ReleaseNote::where('tipe', "3"))->toJson();
    }
    
    public function dataSourceImprovement()
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_REWARD)) {
            return [];
        }
        $id = \request('id');
        return datatables(ReleaseNote::where('tipe', "2"))->toJson();
    }

    public function dataSourceBug()
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_REWARD)) {
            return [];
        }
        $id = \request('id');
        return datatables(ReleaseNote::where('tipe', "1"))->toJson();
    }
    
    public function index() {
        return view('admin.release.table');
    }
    
}

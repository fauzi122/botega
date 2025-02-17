<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\ValidatedPermission;
use App\Models\ReleaseNote;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReleaseNoteController extends Controller
{
    public function datasource()
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_REWARD)) {
            return [];
        }
        $id = \request('id');
        return datatables(ReleaseNote::query())->toJson();
    }
    
    public function index() {
        return view('admin.release.table');
    }
    
}

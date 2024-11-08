<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumeDashboardModel extends Model
{
    use HasFactory;
    protected $table = 'resume_dashboard';
    protected $fillable = ['*'];
    protected $guarded = [];

    public const JML_ARTIKEL = "JML_ARTIKEL";
    public const JML_ARTIKEL_BARU = "JML_ARTIKEL_BARU";
    public const JML_MEMBER = "JML_MEMBER";
    public const JML_MEMBER_BARU = "JML_MEMBER_BARU";
    public const JML_PENJUALAN = "JML_PENJUALAN";
    public const JML_PENJUALAN_2PEKAN_LALU = "JML_PENJUALAN_2PEKAN_LALU";
    public const JML_PENJUALAN_PEKAN_LALU = "JML_PENJUALAN_PEKAN_LALU";
    public const TOTAL_FEE = "TOTAL_FEE";
    public const TOTAL_FEE_BELUM_DIBAYAR = "TOTAL_FEE_BELUM_DIBAYAR";
    public const TOTAL_PENJUALAN = "TOTAL_PENJUALAN";
    public const TOTAL_PENJUALAN_TAHUN_INI = "TOTAL_PENJUALAN_TAHUN_INI";

}

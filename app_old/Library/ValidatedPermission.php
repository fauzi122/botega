<?php

namespace App\Library;

use App\Models\AccessRightModel;
use App\Models\RoleAccessRightModel;
use App\Models\UserModel;
use Illuminate\Validation\ValidationException;

class ValidatedPermission
{
    const TAMBAH_DATA_BANK = 'Bank.Store';
    const LIHAT_DATA_BANK = 'Bank.Read';
    const UBAH_DATA_BANK = 'Bank.Update';
    const HAPUS_DATA_BANK = 'Bank.Delete';

    const TAMBAH_DATA_CABANG = 'Cabang.Store';
    const LIHAT_DATA_CABANG = 'Cabang.Read';
    const UBAH_DATA_CABANG = 'Cabang.Update';
    const HAPUS_DATA_CABANG = 'Cabang.Delete';

    const TAMBAH_DATA_LEVEL_MEMBER = 'LevelMember.Store';
    const LIHAT_DATA_LEVEL_MEMBER = 'LevelMember.Read';
    const UBAH_DATA_LEVEL_MEMBER = 'LevelMember.Update';
    const HAPUS_DATA_LEVEL_MEMBER = 'LevelMember.Delete';

    const LIHAT_DATA_PERUBAHAN_DATA_PROFILE = 'RequestMember.Read';
    const UBAH_DATA_PERUBAHAN_DATA_PROFILE = 'RequestMember.Update';
    const HAPUS_DATA_PERUBAHAN_DATA_PROFILE = 'RequestMember.Delete';

    const TAMBAH_DATA_MEMBER = 'Member.Store';
    const LIHAT_DATA_MEMBER = 'Member.Read';
    const UBAH_DATA_MEMBER = 'Member.Update';
    const HAPUS_DATA_MEMBER = 'Member.Delete';

    const TAMBAH_DATA_PROMO = 'Promo.Store';
    const LIHAT_DATA_PROMO = 'Promo.Read';
    const UBAH_DATA_PROMO = 'Promo.Update';
    const HAPUS_DATA_PROMO = 'Promo.Delete';

    const TAMBAH_DATA_JENIS_HADIAH = 'GiftType.Store';
    const LIHAT_DATA_JENIS_HADIAH = 'GiftType.Read';
    const UBAH_DATA_JENIS_HADIAH = 'GiftType.Update';
    const HAPUS_DATA_JENIS_HADIAH = 'GiftType.Delete';

    const TAMBAH_DATA_HADIAH = 'Gift.Store';
    const LIHAT_DATA_HADIAH = 'Gift.Read';
    const UBAH_DATA_HADIAH = 'Gift.Update';
    const HAPUS_DATA_HADIAH = 'Gift.Delete';

    const TAMBAH_DATA_REWARD = 'Reward.Store';
    const LIHAT_DATA_REWARD = 'Reward.Read';
    const UBAH_DATA_REWARD = 'Reward.Update';
    const HAPUS_DATA_REWARD = 'Reward.Delete';

    const TAMBAH_DATA_REDEEM_POINT = 'Redeem.Store';
    const LIHAT_DATA_REDEEM_POINT = 'Redeem.Read';
    const UBAH_DATA_REDEEM_POINT = 'Redeem.Update';
    const HAPUS_DATA_REDEEM_POINT = 'Redeem.Delete';

    const TAMBAH_DATA_KATEGORI_ARTIKEL = 'Kategori.Store';
    const LIHAT_DATA_KATEGORI_ARTIKEL = 'Kategori.Read';
    const UBAH_DATA_KATEGORI_ARTIKEL = 'Kategori.Update';
    const HAPUS_DATA_KATEGORI_ARTIKEL = 'Kategori.Delete';

    const TAMBAH_DATA_ARTIKEL = 'Artikel.Store';
    const LIHAT_DATA_ARTIKEL = 'Artikel.Read';
    const UBAH_DATA_ARTIKEL = 'Artikel.Update';
    const HAPUS_DATA_ARTIKEL = 'Artikel.Delete';

    const TAMBAH_DATA_EVENT = 'Event.Store';
    const LIHAT_DATA_EVENT = 'Event.Read';
    const UBAH_DATA_EVENT = 'Event.Update';
    const HAPUS_DATA_EVENT = 'Event.Delete';

    const TAMBAH_DATA_SLIDER = 'Slider.Store';
    const LIHAT_DATA_SLIDER = 'Slider.Read';
    const UBAH_DATA_SLIDER = 'Slider.Update';
    const HAPUS_DATA_SLIDER = 'Slider.Delete';

    const TAMBAH_DATA_VIDEO_YOUTUBE = 'Video.Store';
    const LIHAT_DATA_VIDEO_YOUTUBE = 'Video.Read';
    const UBAH_DATA_VIDEO_YOUTUBE = 'Video.Update';
    const HAPUS_DATA_VIDEO_YOUTUBE = 'Video.Delete';

    const TAMBAH_DATA_KATALOG = 'Katalog.Store';
    const LIHAT_DATA_KATALOG = 'Katalog.Read';
    const UBAH_DATA_KATALOG = 'Katalog.Update';
    const HAPUS_DATA_KATALOG = 'Katalog.Delete';

    const TAMBAH_DATA_KATEGORI_PRODUK = 'KategoriProduk.Store';
    const LIHAT_DATA_KATEGORI_PRODUK = 'KategoriProduk.Read';
    const UBAH_DATA_KATEGORI_PRODUK = 'KategoriProduk.Update';
    const HAPUS_DATA_KATEGORI_PRODUK = 'KategoriProduk.Delete';

    const TAMBAH_DATA_PRODUK = 'Produk.Store';
    const LIHAT_DATA_PRODUK = 'Produk.Read';
    const UBAH_DATA_PRODUK = 'Produk.Update';
    const HAPUS_DATA_PRODUK = 'Produk.Delete';

    const LIHAT_DATA_PENJUALAN = 'Penjualan.Read';
    const HAPUS_DATA_PENJUALAN = 'Penjualan.Delete';
    const DETAIL_DATA_PENJUALAN = 'Penjualan.Update';

    const TAMBAH_DATA_FEE = 'Fee.Store';
    const LIHAT_DATA_FEE = 'Fee.Read';
    const UBAH_DATA_FEE = 'Fee.Update';
    const HAPUS_DATA_FEE = 'Fee.Delete';

    const TAMBAH_DATA_HAK_AKSES = 'HakAkses.Store';
    const LIHAT_DATA_HAK_AKSES = 'HakAkses.Read';
    const UBAH_DATA_HAK_AKSES = 'HakAkses.Update';
    const HAPUS_DATA_HAK_AKSES = 'HakAkses.Delete';

    const TAMBAH_DATA_PENGGUNA = 'Pengguna.Store';
    const LIHAT_DATA_PENGGUNA = 'Pengguna.Read';
    const UBAH_DATA_PENGGUNA = 'Pengguna.Update';
    const HAPUS_DATA_PENGGUNA = 'Pengguna.Delete';

    const LIHAT_DATA_LOG_AKTIFITAS = 'Log.Read';
    const HAPUS_DATA_LOG_AKTIFITAS = 'Log.Delete';


    public static function authorize($module){
        $admin = session('admin');
        $u = UserModel::query()->find( $admin?->id );
        if($u == null) {
            session()->flash('error', 'Pengguna tidak valid');
            return false;
        }
        $ac = AccessRightModel::query()->where('module', $module)->first();
        if($ac == null)return true;

        $ras = RoleAccessRightModel::query()->where(['role_id' => $u->role_id, 'access_right_id' => $ac->id ])->first();
        if($ras == null){
            session()->flash('error', 'Tidak ada akses');
            return false;
        }
        if($ras->grant == 0){
            session()->flash('error', 'Pengguna Tidak memiliki akses');
            return false;
        }
        return true;
    }
}

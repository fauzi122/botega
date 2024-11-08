<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessRightModel extends Model
{
    use HasFactory;
    protected $table = 'access_rights';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];

    public static function initData(){
        return [
            [ 'name' => 'Tambah data Bank', 'module'  => 'Bank.Store' ],
            [ 'name' => 'Lihat data bank', 'module'  => 'Bank.Read' ],
            [ 'name' => 'Ubah data bank', 'module'  => 'Bank.Update' ],
            [ 'name' => 'Hapus data bank', 'module'  => 'Bank.Delete' ],

            [ 'name' => 'Tambah data Cabang', 'module'  => 'Cabang.Store' ],
            [ 'name' => 'Lihat data Cabang', 'module'  => 'Cabang.Read' ],
            [ 'name' => 'Ubah data Cabang', 'module'  => 'Cabang.Update' ],
            [ 'name' => 'Hapus data Cabang', 'module'  => 'Cabang.Delete' ],

            [ 'name' => 'Tambah data Level Member', 'module'  => 'LevelMember.Store' ],
            [ 'name' => 'Lihat data Level Member', 'module'  => 'LevelMember.Read' ],
            [ 'name' => 'Ubah data Level Member', 'module'  => 'LevelMember.Update' ],
            [ 'name' => 'Hapus data Level Member', 'module'  => 'LevelMember.Delete' ],

            [ 'name' => 'Lihat data Perubahan data profile', 'module'  => 'RequestMember.Read' ],
            [ 'name' => 'Ubah data Perubahan data profile', 'module'  => 'RequestMember.Update' ],
            [ 'name' => 'Hapus data Perubahan data profile', 'module'  => 'RequestMember.Delete' ],

            [ 'name' => 'Tambah data Member', 'module'  => 'Member.Store' ],
            [ 'name' => 'Lihat data Member', 'module'  => 'Member.Read' ],
            [ 'name' => 'Ubah data Member', 'module'  => 'Member.Update' ],
            [ 'name' => 'Hapus data Member', 'module'  => 'Member.Delete' ],

            [ 'name' => 'Tambah data Promo', 'module'  => 'Promo.Store' ],
            [ 'name' => 'Lihat data Promo', 'module'  => 'Promo.Read' ],
            [ 'name' => 'Ubah data Promo', 'module'  => 'Promo.Update' ],
            [ 'name' => 'Hapus data Promo', 'module'  => 'Promo.Delete' ],

            [ 'name' => 'Tambah data Jenis Hadiah', 'module'  => 'GiftType.Store' ],
            [ 'name' => 'Lihat data Jenis Hadiah', 'module'  => 'GiftType.Read' ],
            [ 'name' => 'Ubah data Jenis Hadiah', 'module'  => 'GiftType.Update' ],
            [ 'name' => 'Hapus data Jenis Hadiah', 'module'  => 'GiftType.Delete' ],

            [ 'name' => 'Tambah data  Hadiah', 'module'  => 'Gift.Store' ],
            [ 'name' => 'Lihat data  Hadiah', 'module'  => 'Gift.Read' ],
            [ 'name' => 'Ubah data  Hadiah', 'module'  => 'Gift.Update' ],
            [ 'name' => 'Hapus data  Hadiah', 'module'  => 'Gift.Delete' ],

            [ 'name' => 'Tambah data  Reward', 'module'  => 'Reward.Store' ],
            [ 'name' => 'Lihat data  Reward', 'module'  => 'Reward.Read' ],
            [ 'name' => 'Ubah data  Reward', 'module'  => 'Reward.Update' ],
            [ 'name' => 'Hapus data  Reward', 'module'  => 'Reward.Delete' ],

            [ 'name' => 'Tambah data Redeem Point ', 'module'  => 'Redeem.Store' ],
            [ 'name' => 'Lihat data  Redeem Point', 'module'  => 'Redeem.Read' ],
            [ 'name' => 'Ubah data  Redeem Point', 'module'  => 'Redeem.Update' ],
            [ 'name' => 'Hapus data  Redeem Point', 'module'  => 'Redeem.Delete' ],

            [ 'name' => 'Tambah data Kategori Artikel', 'module'  => 'Kategori.Store' ],
            [ 'name' => 'Lihat data  Kategori Artikel', 'module'  => 'Kategori.Read' ],
            [ 'name' => 'Ubah data  Kategori Artikel', 'module'  => 'Kategori.Update' ],
            [ 'name' => 'Hapus data  Kategori Artikel', 'module'  => 'Kategori.Delete' ],

            [ 'name' => 'Tambah data Artikel', 'module'  => 'Artikel.Store' ],
            [ 'name' => 'Lihat data  Artikel', 'module'  => 'Artikel.Read' ],
            [ 'name' => 'Ubah data  Artikel', 'module'  => 'Artikel.Update' ],
            [ 'name' => 'Hapus data  Artikel', 'module'  => 'Artikel.Delete' ],

            [ 'name' => 'Tambah data Event', 'module'  => 'Event.Store' ],
            [ 'name' => 'Lihat data  Event', 'module'  => 'Event.Read' ],
            [ 'name' => 'Ubah data  Event', 'module'  => 'Event.Update' ],
            [ 'name' => 'Hapus data  Event', 'module'  => 'Event.Delete' ],

            [ 'name' => 'Tambah data Slider', 'module'  => 'Slider.Store' ],
            [ 'name' => 'Lihat data  Slider', 'module'  => 'Slider.Read' ],
            [ 'name' => 'Ubah data  Slider', 'module'  => 'Slider.Update' ],
            [ 'name' => 'Hapus data  Slider', 'module'  => 'Slider.Delete' ],

            [ 'name' => 'Tambah data Video Youtube', 'module'  => 'Video.Store' ],
            [ 'name' => 'Lihat data  Video Youtube', 'module'  => 'Video.Read' ],
            [ 'name' => 'Ubah data  Video Youtube', 'module'  => 'Video.Update' ],
            [ 'name' => 'Hapus data  Video Youtube', 'module'  => 'Video.Delete' ],

            [ 'name' => 'Tambah data  Katalog', 'module'  => 'Katalog.Store' ],
            [ 'name' => 'Lihat data   Katalog', 'module'  => 'Katalog.Read' ],
            [ 'name' => 'Ubah data   Katalog', 'module'  => 'Katalog.Update' ],
            [ 'name' => 'Hapus data   Katalog', 'module'  => 'Katalog.Delete' ],

            [ 'name' => 'Tambah data  Kategori Produk', 'module'  => 'KategoriProduk.Store' ],
            [ 'name' => 'Lihat data   Kategori Produk', 'module'  => 'KategoriProduk.Read' ],
            [ 'name' => 'Ubah data   Kategori Produk', 'module'  => 'KategoriProduk.Update' ],
            [ 'name' => 'Hapus data   Kategori Produk', 'module'  => 'KategoriProduk.Delete' ],

            [ 'name' => 'Tambah data Produk', 'module'  => 'Produk.Store' ],
            [ 'name' => 'Lihat data  Produk', 'module'  => 'Produk.Read' ],
            [ 'name' => 'Ubah data   Produk', 'module'  => 'Produk.Update' ],
            [ 'name' => 'Hapus data  Produk', 'module'  => 'Produk.Delete' ],

            [ 'name' => 'Lihat data  Penjualan', 'module'  => 'Penjualan.Read' ],
            [ 'name' => 'Detail data Penjualan', 'module'  => 'Penjualan.Update' ],

            [ 'name' => 'Tambah data Fee', 'module'  => 'Fee.Store' ],
            [ 'name' => 'Lihat data  Fee', 'module'  => 'Fee.Read' ],
            [ 'name' => 'Ubah data   Fee', 'module'  => 'Fee.Update' ],
            [ 'name' => 'Hapus data  Fee', 'module'  => 'Fee.Delete' ],

            [ 'name' => 'Tambah data Hak Akses', 'module'  => 'HakAkses.Store' ],
            [ 'name' => 'Lihat data  Hak Akses', 'module'  => 'HakAkses.Read' ],
            [ 'name' => 'Ubah data   Hak Akses', 'module'  => 'HakAkses.Update' ],
            [ 'name' => 'Hapus data  Hak Akses', 'module'  => 'HakAkses.Delete' ],

            [ 'name' => 'Tambah data Pengguna', 'module'  => 'Pengguna.Store' ],
            [ 'name' => 'Lihat data  Pengguna', 'module'  => 'Pengguna.Read' ],
            [ 'name' => 'Ubah data   Pengguna', 'module'  => 'Pengguna.Update' ],
            [ 'name' => 'Hapus data  Pengguna', 'module'  => 'Pengguna.Delete' ],

            [ 'name' => 'Lihat data  Log Aktifitas', 'module'  => 'Log.Read' ],
            [ 'name' => 'Hapus data  Log Aktifitas', 'module'  => 'Log.Delete' ],

        ];
    }

    public static function seed(){
        AccessRightModel::query()->insert(self::initData());
    }
}

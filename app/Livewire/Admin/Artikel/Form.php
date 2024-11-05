<?php

namespace App\Livewire\Admin\Artikel;

use App\Library\ValidatedPermission;
use App\Models\ArticleModel;
use App\Models\LevelMemberModel;
use App\Models\RequestUpdateModel;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public $judul = '';
    public $keyword = '';
    public $article = '';
    public $published_at = null;
    public $expired_at = null;
    public $article_category_id = null;
    public $product_id = null;
    public $pengelola_user_id = null;
    public $editform = false;
    public $lm = null;
    public $kode;
    public $product;
    public $category;
    public $gambar_artikel;
    public $path_images;
    public $id;

    public function edit($id) {
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_ARTIKEL)){
            return ;
        }

        // Replace this with your actual logic to fetch data based on id
        $this->lm = ArticleModel::view()->find($id); // Replace 'YourActualModel' with the appropriate model name
        $this->editform = $this->lm != null;
        $this->id  = $this->lm?->id;
        $this->judul = $this->lm?->judul ?? '';
        $this->keyword = $this->lm?->keyword ?? '';
        $this->article = $this->lm?->article ?? '';
        $this->published_at = $this->lm?->getPubslihedAs();
        $this->expired_at = $this->lm?->getExpiredtat();
        $this->article_category_id = $this->lm?->article_category_id;
        $this->product_id = $this->lm?->product_id;
        $this->kode = $this->lm?->kode;
        $this->product = $this->lm?->product;
        $this->category = $this->lm?->category;
        $this->path_images = '';
        if(\Storage::exists($this->lm?->path_images ?? '--')){
            $this->path_images = url('admin/artikel/image/'.$id.'.png');
        }
    }

    private function validasi(){
        $v =   $this->validate([
            'judul' => 'required|string|max:200',
            'keyword' => 'required|string|max:200',
            'article' => 'required|string',
            'published_at' => 'nullable|date',
            'expired_at' => 'nullable|date',
            'article_category_id' => 'required|exists:article_categories,id',
            'product_id' => 'nullable|exists:products,id',

        ],[
            'judul' => [
                'required' => 'Judul harus diisikan',
                'string' => 'Judul harus berupa string',
                'max' => 'Judul maksimal 255 karakter'
            ],
            'keyword' => [
                'required' => 'Keyword harus diisikan',
                'string' => 'Keyword harus berupa string',
                'max' => 'Keyword maksimal 255 karakter'
            ],
            'article' => [
                'required' => 'Article harus diisikan',
                'string' => 'Article harus berupa string',
                'max' => 'Article maksimal 255 karakter'
            ],
            'published_at' => [
                'nullable' => 'Tanggal terbit dapat dikosongkan',
                'date' => 'Tanggal terbit harus berupa tanggal'
            ],
            'expired_at' => [
                'nullable' => 'Tanggal kedaluwarsa dapat dikosongkan',
                'date' => 'Tanggal kedaluwarsa harus berupa tanggal'
            ],
            'article_category_id' => [
                'required' => 'Kategori artikel harus diisikan',
                'integer' => 'Kategori artikel harus berupa integer'
            ],
            'product_id' => [
                'nullable' => 'Produk dapat dikosongkan',
                'integer' => 'Produk harus berupa integer'
            ],

        ]);
        $admin = session("admin");
        $v["pengelola_user_id"] = $admin?->id;
        if($v['published_at'] === ''){ unset($v['published_at']); }
        if($v['expired_at'] === ''){ unset($v['expired_at']); }
        return $v;
    }

    public function newForm(){
        $this->edit(0);
    }

    public function save(){
        if($this->editform){
            $this->update();
        }else{
            $this->store();
        }
    }

    public function store(){
        if(!ValidatedPermission::authorize(ValidatedPermission::TAMBAH_DATA_ARTIKEL)){
            return ;
        }

        $v = $this->validasi();
        $v['created_at'] = Carbon::now();

        try {
            $lastid = ArticleModel::query()->insertGetId($v);

            $dec = decodeBase64Image($this->gambar_artikel);
            if( $dec !== false ){
                $path = 'artikel/'.$lastid.'.png';
                if(\Storage::put( $path, $dec)) {
                    $arc = ArticleModel::find($lastid);
                    $arc->path_images = $path;
                    $arc->save();
                }
            }

            session()->flash('success', 'Data berhasil di simpan ');
            $this->edit($lastid);
            $this->dispatch('refresh');
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan');
        }
    }

    public function update(){
        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_ARTIKEL)){
            return ;
        }

        $v = $this->validasi();
        $v['updated_at'] = Carbon::now();

        try {
            ArticleModel::query()->where('id', $this->lm->id)->update($v);
            $dec = decodeBase64Image($this->gambar_artikel);
            if( $dec !== false ){
                $path = 'artikel/'.$this->lm->id.'.png';
                if(\Storage::put( $path, $dec)) {
                    $arc = ArticleModel::find($this->lm->id);
                    $arc->path_images = $path;
                    $arc->save();
                }
            }

            $this->edit($this->lm->id);
            session()->flash('success', 'Data berhasil diubah ' );
            $this->dispatch('refresh');
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah ');
        }
    }

    public function hapusgambar($id){
        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_ARTIKEL)){
            return ;
        }

        $arc = ArticleModel::find($id);
        if($arc == null)return false;
        if(\Storage::exists($arc->path_images)) {
            \Storage::delete($arc->path_images);
        }
        $arc->path_images = null;
        $arc->save();
    }

    public function render()
    {
        return view('livewire.admin.artikel.form');
    }
}

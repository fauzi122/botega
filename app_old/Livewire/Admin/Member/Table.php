<?php

namespace App\Livewire\Admin\Member;

use App\Libraries\QueryBuilderExt;
use App\Models\LevelMemberModel;
use App\Models\RequestUpdateModel;
use App\Models\UserModel;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;
    public $ids = [];
    protected $listeners = ['refreshData'];
    public $currentPage = 1;
    public $no = 1;
    public $keyword;
    public $limit = 10;



    public function cari(){
        $this->no = 1;
        $this->currentPage = 1;
        $this->setStatePaginate();
    }

    public function showConfirmDelete(){
        $this->dispatch('confirmDelete', len:count($this->ids));
    }

    public function delete(){
        UserModel::query()->where('user_type','member')->whereIn('id', $this->ids)->delete();
        $this->ids = [];
    }

    public function edit($id){
        $this->dispatch('edit', id:$id);
        $this->setStatePaginate();
    }

    public function hapus($id){
        $this->dispatch('hapus', id:$id);
    }

    public function refresData(){
        $this->setStatePaginate();
        return QueryBuilderExt::whereFilter(UserModel::view(), ['first_name', 'last_name', 'level_name', 'email', 'home_addr'], $this->keyword)
            ->where('user_type','member')->paginate($this->limit);
    }

    public function setPage($url){
        $this->currentPage = (int)explode('page=', $url)[1];
        $this->no = 1+(($this->currentPage - 1) * $this->limit);
        $this->setStatePaginate();
    }

    private function setStatePaginate(){
        Paginator::currentPageResolver(function($page){
            return $this->currentPage;
        });
    }

    public function render()
    {
        return view('livewire.admin.member.table', [
            'data' => $this->refresData()
        ]);
    }


}

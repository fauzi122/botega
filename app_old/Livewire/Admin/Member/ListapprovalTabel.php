<?php

namespace App\Livewire\Admin\Member;

use App\Libraries\QueryBuilderExt;
use App\Models\LevelMemberModel;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class ListapprovalTabel extends Component
{
    use WithPagination;
    public $ids = [];
    protected $listeners = ['refreshData'];
    public $currentPage = 1;
    public $no = 1;
    public $keyword;
    public $limit = 10;

    public function mount(){
        $this->refresData();
    }


    public function cari(){
        $this->no = 1;
        $this->currentPage = 1;
        $this->setStatePaginate();
    }

    public function showConfirmDelete(){
        $this->dispatch('confirmDelete', len:count($this->ids));
    }

    public function delete(){
        LevelMemberModel::query()->whereIn('id', $this->ids)->delete();
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
        return QueryBuilderExt::whereFilter(LevelMemberModel::query(), ['level_name'], $this->keyword)
            ->paginate($this->limit);
    }

    public function setPage($url){
        $this->currentPage = (int)explode('page=', $url)[1];
        $this->no = 1+(($this->currentPage - 1) * $this->limit);
        $this->setStatePaginate();
    }

    public function publishlevel($id, $nilai){
        $r = LevelMemberModel::query()->find($id);
        if($r != null){
            $r->publish = $nilai == 1 ? 0 : 1;
            $r->save();
        }
    }

    private function setStatePaginate(){
        Paginator::currentPageResolver(function($page){
            return $this->currentPage;
        });
    }
    public function render()
    {
        return view('livewire.admin.member.listapproval-tabel', [
            'data'=>$this->refresData()
        ]);
    }
}

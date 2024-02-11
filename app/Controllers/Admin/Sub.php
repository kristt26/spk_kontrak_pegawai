<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Sub extends BaseController
{
    protected $sub;
    protected $kriteria;
    public function __construct() {
        $this->sub = new \App\Models\SubModel();
        $this->kriteria = new \App\Models\KriteriaModel();
    }
    
    public function read($id = null)
    {
        try {
            $kriteria = $this->kriteria->where("id", $id)->first();
            $kriteria['sub'] = $this->sub->where('kriteria_id', $kriteria['id'])->findAll();
            return $this->respond($kriteria);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    public function range($id = null)
    {
        return view('admin/range');
    }

    public function post()
    {
        try {
            if($this->sub->insert($this->request->getJSON())){
                return $this->respondCreated($this->sub->getInsertID());
            }
            throw new \Exception("Gagal menyimpan", 1);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }
    public function put()
    {
        try {
            $data = $this->request->getJSON();
            if($this->sub->update($data->id, $data)){
                return $this->respondUpdated(true);
            }
            throw new \Exception("Gagal mengubah", 1);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }
    public function deleted($id = null)
    {
        try {
            if($this->sub->delete($id));
            return $this->respondDeleted(true);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }
}

<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Range extends BaseController
{
    use ResponseTrait;
    protected $range;
    protected $sub;
    public function __construct() {
        $this->sub = new \App\Models\SubModel();
        $this->range = new \App\Models\RangeModel();
    }
    
    public function read($id = null)
    {
        try {
            $sub = $this->sub->where("id", $id)->first();
            $sub['range'] = $this->range->where('sub_id', $sub['id'])->findAll();
            return $this->respond($sub);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    public function post()
    {
        try {
            if($this->range->insert($this->request->getJSON())){
                return $this->respondCreated($this->range->getInsertID());
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
            if($this->range->update($data->id, $data)){
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
            if($this->range->delete($id));
            return $this->respondDeleted(true);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }
}

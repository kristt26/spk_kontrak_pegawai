<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Pegawai extends BaseController
{
    protected $pegawai;
    protected $periode;
    public function __construct() {
        $this->pegawai = new \App\Models\PegawaiModel();
        $this->periode = new \App\Models\PeriodeModel();
    }
    public function index()
    {
        return view('admin/pegawai');
    }

    public function read()
    {
        try {
            $data = $this->pegawai->select("karyawan.*")->join('periode', 'periode.id=karyawan.periode_id', 'left')->where('status', '1')->findAll();
            return $this->respond($data);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    public function post()
    {
        try {
            $periode = $this->periode->where('status', '1')->first();
            $data = $this->request->getJSON();
            $data->periode_id = $periode['id'];
            if($this->pegawai->insert($data)){
                return $this->respondCreated($this->pegawai->getInsertID());
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
            if($this->pegawai->update($data->id, $data)){
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
            if($this->pegawai->delete($id));
            return $this->respondDeleted(true);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }
}

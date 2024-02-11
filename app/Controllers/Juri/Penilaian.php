<?php

namespace App\Controllers\Juri;

use App\Controllers\BaseController;
use App\Models\PegawaiModel;
use App\Models\KriteriaModel;
use App\Models\RangeModel;
use App\Models\PendaftaranModel;
use App\Models\PenilaianModel;
use App\Models\SubModel;

class Penilaian extends BaseController
{
    public function index()
    {
        return view('admin/penilaian');
    }

    public function read()
    {
        $range = new RangeModel();
        $pegawai = new PegawaiModel();
        $kriteria = new KriteriaModel();
        $sub = new SubModel();
        $dataRange = $range->asObject()->findAll();
        $dataSub = $sub->asObject()->findAll();
        $data['pegawai'] = $pegawai->select("karyawan.*")->join('periode', 'periode.id=karyawan.periode_id')->where('status', '1')->findAll();
        $data['kriteria'] = $kriteria->asObject()->findAll();
        foreach ($data['kriteria'] as $key => $value) {
            $value->sub = [];
            foreach ($dataSub as $key1 => $itemSub) {
                if($value->id==$itemSub->kriteria_id){
                    $itemSub->range = [];
                    foreach ($dataRange as $key2 => $itemRange) {
                        if ($itemSub->id==$itemRange->sub_id) $itemSub->range[] = $itemRange;
                    }
                    $value->sub[] = $itemSub;
                }
            }
        }
        return $this->respond($data);
    }

    public function getNilai($id)
    {
        $kriteria = new KriteriaModel();
        $sub = new SubModel();
        $penilaian = new PenilaianModel();
        $juri = new JuriModel();
        $dtJuri = $juri->where('users_id', session()->get('uid'))->first();
        $data = $kriteria->asObject()->findAll();
        foreach ($data as $key => $kriteria) {
            $kriteria->sub = $sub->asObject()->where('kriteria_id', $kriteria->id)->findAll();
            foreach ($kriteria->sub as $key => $value) {
                $nilai = $penilaian->where('sub_id', $value->id)->where('pendaftaran_id', $id)->where('juri_id', $dtJuri['id'])->first();
                if($nilai){
                    $value->nilai = $nilai['nilai'];
                    $value->penilaian_id = $nilai['id'];
                } 
            }
            // $sub->select('penilaian.*, sub.nama, sub.code, sub.bobot')->join('penilaian', 'penilaian.sub_id=sub.id', 'LEFT')->findAll();
        }
        return $this->respond($data);
    }

    public function post()
    {
        $data = $this->request->getJSON();
        $penilaian = new PenilaianModel();
        $juri = new JuriModel();
        $conn = \Config\Database::connect();
        try {
            $conn->transBegin();
            $dtJuri = $juri->where('users_id', session()->get('uid'))->first();
            foreach ($data->kriteria as $keyKriteria => $kriteria) {
                foreach ($kriteria->sub as $keySub => $sub) {
                    if(!isset($sub->penilaian_id)){
                        $item = [
                            'sub_id'=>$sub->id,
                            'pendaftaran_id'=>$data->id,
                            'nilai'=>$sub->nilai,
                            'juri_id' => $dtJuri['id']
                        ];
                        $penilaian->insert($item);
                        $item['id']=$penilaian->getInsertID();
                    }else{
                        $penilaian->update($sub->penilaian_id, ['nilai'=>$sub->nilai]);
                    }
                }
            }
            if($conn->transStatus()){
                $conn->transCommit();
                return $this->respondCreated(true);
            }else{
                $conn->transRollback();
                throw new \Exception("Gagal Simpan", 1);
                
            }
            //code...
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
        return $this->respond($data);
    }

    public function put()
    {

    }

    public function delete($id)
    {

    }
}

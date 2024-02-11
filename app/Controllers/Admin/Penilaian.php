<?php

namespace App\Controllers\Admin;

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
        $data['pegawai'] = $pegawai->select("karyawan.*, (select penilaian.nilai from penilaian where penilaian.karyawan_id=karyawan.id limit 1) as statusNilai")
        ->join('periode', 'periode.id=karyawan.periode_id')->where('status', '1')->findAll();
        $data['kriteria'] = $kriteria->asObject()->findAll();
        foreach ($data['kriteria'] as $key => $value) {
            $value->sub = [];
            foreach ($dataSub as $key1 => $itemSub) {
                if ($value->id == $itemSub->kriteria_id) {
                    $itemSub->range = [];
                    foreach ($dataRange as $key2 => $itemRange) {
                        if ($itemSub->id == $itemRange->sub_id) $itemSub->range[] = $itemRange;
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
        $range = new RangeModel();
        $penilaian = new PenilaianModel();
        $data = $kriteria->asObject()->findAll();
        foreach ($data as $key => $kriteria) {
            $kriteria->sub = $sub->asObject()->where('kriteria_id', $kriteria->id)->findAll();
            foreach ($kriteria->sub as $key => $value) {
                $nilai = $penilaian->where('sub_id', $value->id)->where('karyawan_id', $id)->first();
                $value->range = $range->where('sub_id', $value->id)->findAll();
                if ($nilai) {
                    $value->nilai = $nilai['nilai'];
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
        $conn = \Config\Database::connect();
        try {
            $conn->transBegin();
            foreach ($data->kriteria as $keyKriteria => $kriteria) {
                foreach ($kriteria->sub as $keySub => $sub) {
                    if (!isset($sub->penilaian_id)) {
                        $item = [
                            'sub_id' => $sub->id,
                            'karyawan_id' => $data->id,
                            'nilai' => $sub->nilai,
                        ];
                        $penilaian->insert($item);
                        $item['id'] = $penilaian->getInsertID();
                    } else {
                        $penilaian->update($sub->penilaian_id, ['nilai' => $sub->nilai]);
                    }
                }
            }
            if ($conn->transStatus()) {
                $conn->transCommit();
                return $this->respondCreated(true);
            } else {
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

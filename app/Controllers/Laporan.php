<?php

namespace App\Controllers;

use ocs\spklib\ProfileMatchingNew as PM;
use App\Models\JuriModel;
use App\Models\KriteriaModel;
use App\Models\LombaModel;
use App\Models\PegawaiModel;
use App\Models\PendaftaranModel;
use App\Models\PenilaianModel;
use App\Models\SubModel;

class Laporan extends BaseController
{
    public function index()
    {
        return view('laporan');
    }
    public function hitung()
    {
        try {
            $kri = new KriteriaModel();
            $sub = new SubModel();
            $penilaian = new PenilaianModel();
            $pegawai = new PegawaiModel();
            $dataPegawai = $pegawai->findAll();
            $data = [];
            $dtKriteria = $kri->findAll();
            foreach ($dtKriteria as $keyKriteria => $kriteria) {
                $dtKriteria[$keyKriteria]['bobot'] = $dtKriteria[$keyKriteria]['bobot'] / 100;
                $dtKriteria[$keyKriteria]['sub'] = $sub->where('kriteria_id', $kriteria['id'])->findAll();
                foreach ($dtKriteria[$keyKriteria]['sub'] as $keySub => $subKriteria) {
                    $dtKriteria[$keyKriteria]['sub'][$keySub]['bobot'] = $dtKriteria[$keyKriteria]['sub'][$keySub]['bobot'] / 100;
                }
            }
            foreach ($dataPegawai as $key => $itemPegawai) {
                $alt = [
                    "nama" => $itemPegawai['nama'],
                    "nilai" => []
                ];
                foreach ($dtKriteria as $keyKriteria => $kriteria) {
                    $itemKriteria = [
                        "code" => $kriteria['code'],
                        "sub" => []
                    ];
                    foreach ($kriteria['sub'] as $key => $subKriteria) {
                        $nilai = $penilaian->where('sub_id', $subKriteria['id'])->where('karyawan_id', $itemPegawai['id'])->first();
                        $itemSub = [
                            "code" => $subKriteria['code'],
                            "nilai" => (int)$nilai['nilai'],
                            "profileKriteria" => $subKriteria['profileKriteria'],
                            'status' => $subKriteria['status']

                        ];
                        $itemKriteria['sub'][] = $itemSub;
                    }
                    $alt['nilai'][] = $itemKriteria;
                }
                $itemJuri[] = $alt;
            }
            $data = new PM($dtKriteria, $itemJuri, 0, true, 2);
            $result = $data;
            usort($result->nilaiAkhir, function ($a, $b) {
                $retval = $b['nilaiAkhir'] <=> $a['nilaiAkhir'];
                return $retval;
            });
            return $this->respond($data);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }
}

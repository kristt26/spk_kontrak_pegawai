angular.module('adminctrl', [])
    // Admin
    .controller('dashboardController', dashboardController)
    .controller('periodeController', periodeController)
    .controller('kriteriaController', kriteriaController)
    .controller('subKriteriaController', subKriteriaController)
    .controller('rangeController', rangeController)
    .controller('alternatifController', alternatifController)
    .controller('laporanController', laporanController)
    // Peserta
    .controller('pendaftaranController', pendaftaranController)
    .controller('pengumumanController', pengumumanController)
    .controller('historyController', historyController)
    // Juri
    .controller('penilaianController', penilaianController)
    ;

function dashboardController($scope, dashboardServices) {
    $scope.setTitle = "Dashboard";
    $scope.$emit("SendUp", $scope.setTitle);
    $scope.datas = {};
    $scope.paragraph = "Sistem Pendukung Keputusan Penentuan Perpanjangan Kontrak Pegawai pada Dinas Pekerjaan Umum Kota Jayapura Tahapan";
    // dashboardServices.get().then(res=>{
    //     $scope.datas = res;
    // })
}

function periodeController($scope, periodeServices, pesan, helperServices) {
    $scope.setTitle = "Periode Penilaian";
    $scope.$emit("SendUp", $scope.setTitle);
    $scope.datas = {};
    $scope.model = {};
    periodeServices.get().then((res) => {
        $scope.datas = res;
    })
    $scope.save = () => {
        pesan.dialog('Yakin ingin?', 'Yes', 'Tidak').then(res => {
            if ($scope.model.id) {
                periodeServices.put($scope.model).then(res => {
                    $scope.model = {};
                    pesan.Success("Berhasil mengubah data");
                })
            } else {
                periodeServices.post($scope.model).then(res => {
                    $scope.model = {};
                    pesan.Success("Berhasil menambah data");
                })
            }
        })
    }

    $scope.edit = (item) => {
        $scope.model = angular.copy(item);
        $scope.model.mulai = new Date($scope.model.mulai);
        $scope.model.selesai = new Date($scope.model.selesai);
        document.getElementById("periode").focus();
    }

    $scope.delete = (param) => {
        pesan.dialog('Yakin ingin?', 'Ya', 'Tidak').then(res => {
            periodeServices.deleted(param).then(res => {
                pesan.Success("Berhasil menghapus data");
            })
        });
    }

    $scope.subKlasifikasi = (param) => {
        document.location.href = helperServices.url + "admin/sub_klasifikasi/data/" + param.id;
    }

    $scope.hasil = (param) => {
        param.hasil = "1";
        pesan.dialog('Yakin mengumumkan hasil lomba?', 'Yes', 'Tidak').then(res => {
            lombaServices.put(param).then(res => {
                $scope.model = {};
                pesan.Success("Berhasil mengubah data");
            })
        })
    }
}

function kriteriaController($scope, kriteriaServices, pesan, helperServices, subServices) {
    $scope.setTitle = "Kriteria";
    $scope.$emit("SendUp", $scope.setTitle);
    $scope.datas = {};
    $scope.model = {};
    kriteriaServices.get().then((res) => {
        $scope.datas = res;
    })
    $scope.save = () => {
        pesan.dialog('Yakin ingin?', 'Yes', 'Tidak').then(res => {
            if ($scope.model.id) {
                kriteriaServices.put($scope.model).then(res => {
                    $scope.model = {};
                    pesan.Success("Berhasil mengubah data");
                })
            } else {
                kriteriaServices.post($scope.model).then(res => {
                    $scope.model = {};
                    pesan.Success("Berhasil menambah data");
                })
            }
        })
    }

    $scope.edit = (item) => {
        item.bobot = parseInt(item.bobot);
        item.profile_kriteria = parseInt(item.profile_kriteria);
        $scope.model = angular.copy(item);
        document.getElementById("kriteria").focus();
    }

    $scope.showSub = (param) => {
        $.LoadingOverlay("show");
        setTimeout(() => {
            $.LoadingOverlay("hide");
            $scope.$applyAsync(x => {
                $scope.kriteria = param;
                $scope.setTitle = "Sub";
            })
        }, 200);
    }

    $scope.saveSub = () => {
        pesan.dialog('Yakin ingin?', 'Yes', 'Tidak').then(res => {
            $scope.model.kriteria_id = $scope.kriteria.id;
            if ($scope.model.id) {
                subServices.put($scope.model).then(res => {
                    var kri = $scope.datas.find(x => x.id == $scope.model.kriteria_id);
                    if (kri) {
                        var data = kri.sub.find(x=>x.id == $scope.model.id);
                        if(data){
                            data.nama = $scope.model.nama;
                            data.kode = $scope.model.kode;
                            data.bobot = $scope.model.bobot;
                            data.profileKriteria = $scope.model.profileKriteria;
                            data.status = $scope.model.status;
                        }
                    }
                    $scope.model = {};
                    pesan.Success("Berhasil mengubah data");
                })
            } else {
                subServices.post($scope.model).then(res => {
                    $scope.model.id = res;
                    if (!$scope.kriteria.sub) $scope.kriteria.sub = [];
                    $scope.kriteria.sub.push($scope.model);
                    $scope.model = {};
                    $scope.model.kriteria_id = $scope.kriteria.id;
                    pesan.Success("Berhasil menambah data");
                })
            }
        })
    }

    $scope.delete = (param) => {
        pesan.dialog('Yakin ingin?', 'Ya', 'Tidak').then(res => {
            kriteriaServices.deleted(param).then(res => {
                pesan.Success("Berhasil menghapus data");
            })
        });
    }
    $scope.deleteSub = (param) => {
        pesan.dialog('Yakin ingin?', 'Ya', 'Tidak').then(res => {
            subServices.deleted(param).then(res => {
                var index = $scope.kriteria.range.indexOf(param);
                $scope.kriteria.range.splice(index, 1);
                pesan.Success("Berhasil menghapus data");
            })
        });
    }
    $scope.back = () => {
        $.LoadingOverlay("show");
        setTimeout(() => {
            $.LoadingOverlay("hide");
            $scope.$applyAsync(x => {
                $scope.kriteria = {};
                $scope.setTitle = "Kriteria";
            })
        }, 200);
    }
}

function subKriteriaController($scope, subServices, pesan, helperServices) {
    $scope.datas = {};
    $scope.model = {};
    subServices.get(helperServices.lastPath).then((res) => {
        $scope.datas = res;
        $scope.setTitle = "Kriteria " + $scope.datas.kriteria + "(" + $scope.datas.code + ")";
        $scope.$emit("SendUp", $scope.setTitle);
    })
    $scope.save = () => {
        $scope.model.kriteria_id = $scope.datas.id;
        pesan.dialog('Yakin ingin?', 'Yes', 'Tidak').then(res => {
            if ($scope.model.id) {
                subServices.put($scope.model).then(res => {
                    $scope.model = {};
                    pesan.Success("Berhasil mengubah data");
                })
            } else {
                subServices.post($scope.model).then(res => {
                    $scope.model = {};
                    pesan.Success("Berhasil menambah data");
                })
            }
        })
    }

    $scope.edit = (item) => {
        $scope.model = angular.copy(item);
        $scope.model.mulai = new Date($scope.model.mulai);
        $scope.model.selesai = new Date($scope.model.selesai);
        document.getElementById("juri").focus();
    }

    $scope.delete = (param) => {
        pesan.dialog('Yakin ingin?', 'Ya', 'Tidak').then(res => {
            subServices.deleted(param).then(res => {
                pesan.Success("Berhasil menghapus data");
            })
        });
    }

    $scope.subKlasifikasi = (param) => {
        document.location.href = helperServices.url + "admin/sub_klasifikasi/data/" + param.id;
    }
}

function rangeController($scope, rangeServices, pesan, helperServices) {
    $scope.datas = {};
    $scope.model = {};
    rangeServices.get(helperServices.lastPath).then((res) => {
        $scope.datas = res;
        $scope.setTitle = "Sub Kriteria " + $scope.datas.nama + "(" + $scope.datas.code + ")";
        $scope.$emit("SendUp", $scope.setTitle);
    })
    $scope.save = () => {
        $scope.model.sub_id = $scope.datas.id;
        pesan.dialog('Yakin ingin?', 'Yes', 'Tidak').then(res => {
            if ($scope.model.id) {
                rangeServices.put($scope.model).then(res => {
                    $scope.model = {};
                    pesan.Success("Berhasil mengubah data");
                })
            } else {
                rangeServices.post($scope.model).then(res => {
                    $scope.model = {};
                    pesan.Success("Berhasil menambah data");
                })
            }
        })
    }

    $scope.edit = (item) => {
        $scope.model = angular.copy(item);
        $scope.model.value = parseFloat($scope.model.value);
        document.getElementById("indikator").focus();
    }

    $scope.delete = (param) => {
        pesan.dialog('Yakin ingin?', 'Ya', 'Tidak').then(res => {
            rangeServices.deleted(param).then(res => {
                pesan.Success("Berhasil menghapus data");
            })
        });
    }

    $scope.subKlasifikasi = (param) => {
        document.location.href = helperServices.url + "admin/sub_klasifikasi/data/" + param.id;
    }
}

function alternatifController($scope, alternatifServices, pesan, helperServices) {
    $scope.setTitle = "Pegawai Kontrak";
    $scope.$emit("SendUp", $scope.setTitle);
    $scope.datas = {};
    $scope.model = {};
    $scope.setShow = 'select';
    $scope.dataExcel = [];

    alternatifServices.get().then((res) => {
        $scope.datas = res;
    })
  
    $scope.save = () => {
        pesan.dialog('Yakin ingin?', 'Yes', 'Tidak').then(res => {
            $.LoadingOverlay("show");
            if($scope.model.id){
                alternatifServices.put($scope.model).then(res => {
                    $.LoadingOverlay("hide");
                    $scope.model = {};
                    pesan.Success("Berhasil menambah data");
                })
            }else{
                alternatifServices.post($scope.model).then(res => {
                    $.LoadingOverlay("hide");
                    $scope.model = {};
                    pesan.Success("Berhasil menambah data");
                })

            }
        })
    }

    $scope.edit = (item) => {
        $scope.model = angular.copy(item);
        document.getElementById("periode").focus();
    }

    $scope.delete = (param) => {
        pesan.dialog('Yakin ingin?', 'Ya', 'Tidak').then(res => {
            klasifikasiServices.deleted(param).then(res => {
                pesan.Success("Berhasil menghapus data");
            })
        });
    }
}

function laporanController($scope, pesan, helperServices, laporanServices) {
    $scope.setTitle = "Laporan";
    $scope.$emit("SendUp", $scope.setTitle);
    $scope.datas = {};
    $scope.lomba = {};
    $scope.model = {};
    $.LoadingOverlay('show');
    laporanServices.hitung().then((res) => {
        $scope.datas = res;
        $.LoadingOverlay('hide');
    })
}


// Peserta

function pendaftaranController($scope, pendaftaranServices, pesan, helperServices) {
    $scope.setTitle = "Pendaftaran Lomba";
    $scope.$emit("SendUp", $scope.setTitle);
    $scope.datas = {};
    $scope.model = {};
    $.LoadingOverlay('show');
    pendaftaranServices.get().then((res) => {
        $scope.datas = res;
        if ($scope.datas.lomba.length > 0) {
            $scope.datas.lomba.forEach(element => {
                var item = $scope.datas.daftar.find((x) => x.lomba_id == element.id);
                if (item) {
                    element.daftar = true;
                    element.nomor = item.nomor;
                }
            });
        } else pesan.info('Tidak ada lomba yang diselenggarakan');
        $.LoadingOverlay('hide');
        // console.log(res);
    })
    $scope.daftar = (param) => {
        pesan.dialog('Yakin ingin mendaftar?', 'Yes', 'Tidak').then(res => {
            pendaftaranServices.post(param).then(res => {
                param.nomor = res.nomor;
                $scope.datas.daftar.push(res);
                param.daftar = true;
                pesan.Success("Berhasil menambah data");
            })
        })
    }
}

function pengumumanController($scope, pengumumanServices, pesan, helperServices) {
    $scope.setTitle = "Pengumuman Hasil Lomba";
    $scope.$emit("SendUp", $scope.setTitle);
    $scope.datas = {};
    $scope.model = {};
    $.LoadingOverlay('show');
    pengumumanServices.get().then((res) => {
        $scope.datas = res;
        $.LoadingOverlay('hide');
        console.log(res);
    })
}

function historyController($scope, historyServices, pesan, helperServices) {
    $scope.setTitle = "History Lomba";
    $scope.$emit("SendUp", $scope.setTitle);
    $scope.datas = {};
    $scope.model = {};
    $.LoadingOverlay('show');
    historyServices.get().then((res) => {
        $scope.datas = res;
        $.LoadingOverlay('hide');
        console.log(res);
    })
}

// Penilaian

function penilaianController($scope, penilaianServices, pesan, helperServices) {
    $scope.setTitle = "Penilaian Karyawan Kontrak";
    $scope.$emit("SendUp", $scope.setTitle);
    $scope.datas = {};
    $scope.model = {};
    $scope.show = 'pegawai';
    penilaianServices.get().then((res) => {
        $scope.datas = res;
        console.log(res);
    })
    // $scope.daftar = (param) => {
    //     pesan.dialog('Yakin ingin mendaftar?', 'Yes', 'Tidak').then(res => {
    //         penilaianServices.post(param).then(res => {
    //             $scope.datas.daftar.push(res);
    //             param.daftar = true;
    //             pesan.Success("Berhasil menambah data");
    //         })
    //     })
    // }

    $scope.nilai = (param) => {
        $scope.model = param;
        $.LoadingOverlay('show');
        penilaianServices.getNilai(param.id).then((res) => {
            $scope.model.kriteria = res;
            $scope.show = 'penilaian';
            console.log(res);
            $.LoadingOverlay('hide');

        })
        // $scope.model.kriteria = angular.copy($scope.datas.kriteria);
    }

    $scope.save = () => {
        console.log($scope.model);
        $scope.model.kriteria
        pesan.dialog('Yakin ingin menyimpan penilaian?', 'Ya', 'Tidak').then((x) => {
            penilaianServices.post($scope.model).then((res) => {
                pesan.Success("Proses berhasil");
            })
        })
    }

    $scope.back = () => {
        $scope.show = 'pegawai';
    }
}
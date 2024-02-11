<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div ng-controller="rangeController">
    <h1 class="h3 mb-4 text-gray-800">{{setTitle}}</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-warning">
                    <h3>Input Indikator</h3>
                </div>
                <form ng-submit="save()">
                    <div class="card-body">
                        <div ng-class="{'form-group pmd-textfield pmd-textfield-floating-label': !model.id, 'form-group pmd-textfield': model.id}">
                            <label class="control-label">Indikator Penilaian</label>
                            <textarea class="form-control" ng-model="model.indikator"  rows="3" required></textarea>
                            <!-- <input type="text" id="indikator" ng-model="model.indikator" required> -->
                        </div>
                        <!-- <div ng-class="{'form-group pmd-textfield pmd-textfield-floating-label': !model.id, 'form-group pmd-textfield': model.id}">
                            <label class="control-label">Profile Kriteria</label>
                            <input type="number" class="form-control" ng-model="model.profileKriteria" required>
                        </div> -->
                        <div ng-class="{'form-group pmd-textfield pmd-textfield-floating-label': !model.id, 'form-group pmd-textfield': model.id}">
                            <label class="control-label">Nilai</label>
                            <input type="number" class="form-control" id="indikator" ng-model="model.value" required>
                        </div>
                        <!-- <div ng-class="{'form-group pmd-textfield pmd-textfield-floating-label': !model.id, 'form-group pmd-textfield': model.id}">
                            <label class="control-label">Status</label>
                            <input type="text" class="form-control" ng-model="model.status" required>
                        </div> -->
                    </div>
                    <div class="card-footer d-flex justify-content-end bg-warning">
                        <button type="submit" class="btn btn-primary pmd-ripple-effect btn-sm">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-lg-between bg-warning">
                    <h3>Daftar Indikator</h3>
                    <button class="btn btn-secondary btn-sm" onclick="history.back()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Kembali</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table pmd-table table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Indikator</th>
                                    <th>Nilai</th>
                                    <th><i class="fas fa-cogs"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="item in datas.range">
                                    <td>{{$index+1}}</td>
                                    <td>{{item.indikator}}</td>
                                    <td>{{item.value}}</td>
                                    <td>
                                        <button type="submit" class="btn btn-warning pmd-ripple-effect btn-sm" ng-click="edit(item)"><i class="fas fa-edit fa-sm fa-fw"></i></button>
                                        <button type="submit" class="btn btn-danger pmd-ripple-effect btn-sm" ng-click="deleteRange(item)"><i class="fas fa-trash-alt fa-sm fa-fw"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
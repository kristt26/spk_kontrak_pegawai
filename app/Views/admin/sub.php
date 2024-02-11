<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div ng-controller="subKriteriaController">
    <h1 class="h3 mb-4 text-gray-800">{{setTitle}}</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-warning">
                    <h3>Input Sub</h3>
                </div>
                <form ng-submit="save()">
                    <div class="card-body">
                        <div ng-class="{'form-group pmd-textfield pmd-textfield-floating-label': !model.id, 'form-group pmd-textfield': model.id}">
                            <label class="control-label">Sub Kriteria</label>
                            <input type="text" class="form-control" id="nama" ng-model="model.nama" required>
                        </div>
                        <div ng-class="{'form-group pmd-textfield pmd-textfield-floating-label': !model.id, 'form-group pmd-textfield': model.id}">
                            <label class="control-label">Kode</label>
                            <input type="text" class="form-control" ng-model="model.code" required>
                        </div>
                        <div ng-class="{'form-group pmd-textfield pmd-textfield-floating-label': !model.id, 'form-group pmd-textfield': model.id}">
                            <label class="control-label">Profile Kriteria</label>
                            <input type="number" class="form-control" ng-model="model.profileKriteria" required>
                        </div>
                        <!-- <div ng-class="{'form-group pmd-textfield pmd-textfield-floating-label': !model.id, 'form-group pmd-textfield': model.id}">
                            <label class="control-label">Bobot</label>
                            <input type="number" class="form-control" ng-model="model.bobot" placeholder="Bobot dalam %" required>
                        </div> -->
                        <div ng-class="{'form-group pmd-textfield pmd-textfield-floating-label': !model.id, 'form-group pmd-textfield': model.id}">
                            <label class="control-label">Status</label>
                            <input type="text" class="form-control" ng-model="model.status" required>
                        </div>
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
                    <h3>Daftar Sub Kriteria {{kriteria.kriteria}}</h3>
                    <button class="btn btn-secondary btn-sm" onclick="history.back()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Kembali</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table pmd-table table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Sub Kriteria</th>
                                    <th>Kode</th>
                                    <th>Profile Target</th>
                                    <!-- <th>Bobot</th> -->
                                    <th>Status</th>
                                    <th><i class="fas fa-cogs"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="item in datas.sub">
                                    <td>{{$index+1}}</td>
                                    <td>{{item.nama}}</td>
                                    <td>{{item.code}}</td>
                                    <td>{{item.profileKriteria}}</td>
                                    <!-- <td>{{item.bobot}}</td> -->
                                    <td>{{item.status}}</td>
                                    <td>
                                        <button type="submit" class="btn btn-warning pmd-ripple-effect btn-sm" ng-click="edit(item)"><i class="fas fa-edit fa-sm fa-fw"></i></button>
                                        <button type="submit" class="btn btn-danger pmd-ripple-effect btn-sm" ng-click="deleteRange(item)"><i class="fas fa-trash-alt fa-sm fa-fw"></i></button>
                                        <a href="/sub/range/{{item.id}}" class="btn btn-info pmd-ripple-effect btn-sm"><i class="fas fa-list"></i></a>
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
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('perhitungan', 'Perhitungan::index');
$routes->group('auth', function ($routes) {
    $routes->get('/', 'Auth::index');
    $routes->post('login', 'Auth::login');
    $routes->get('registration', 'Auth::regis');
    $routes->post('post', 'Auth::post');
    $routes->get('logout', 'Auth::logout');
});

$routes->group('periode', function($routes){
    $routes->get('', 'Admin\Periode::index');
    $routes->get('read', 'Admin\Periode::read');
    $routes->post('post', 'Admin\Periode::post');
    $routes->put('put', 'Admin\Periode::put');
    $routes->delete('delete/(:any)', 'Admin\Periode::delete/$1');
});

$routes->group('pegawai', function($routes){
    $routes->get('', 'Admin\Pegawai::index');
    $routes->get('read', 'Admin\Pegawai::read');
    $routes->post('post', 'Admin\Pegawai::post');
    $routes->put('put', 'Admin\Pegawai::put');
    $routes->delete('delete/(:any)', 'Admin\Pegawai::delete/$1');
});

$routes->group('kriteria', function($routes){
    $routes->get('', 'Admin\Kriteria::index');
    $routes->get('read', 'Admin\Kriteria::read');
    $routes->get('sub/(:any)', 'Admin\Kriteria::sub/$1');
    $routes->post('post', 'Admin\Kriteria::post');
    $routes->put('put', 'Admin\Kriteria::put');
    $routes->delete('delete/(:any)', 'Admin\Kriteria::deleted/$1');
});

$routes->group('sub', function ($routes) {
    $routes->get('read/(:any)', 'Admin\Sub::read/$1');
    $routes->get('range/(:any)', 'Admin\Sub::range/$1');
    $routes->post('post', 'Admin\Sub::post');
    $routes->put('put', 'Admin\Sub::put');
    $routes->delete('delete/(:num)', 'Admin\Sub::deleted/$1');
});

$routes->group('range', function ($routes) {
    $routes->get('read/(:any)', 'Admin\Range::read/$1');
    $routes->post('post', 'Admin\Range::post');
    $routes->put('put', 'Admin\Range::put');
    $routes->delete('delete/(:num)', 'Admin\Range::deleted/$1');
});

// Peserta
$routes->group('pendaftaran', function ($routes) {
    $routes->get('', 'Peserta\Pendaftaran::index');
    $routes->get('read', 'Peserta\Pendaftaran::read');
    $routes->post('post', 'Peserta\Pendaftaran::post');
    $routes->put('put', 'Peserta\Pendaftaran::put');
    $routes->delete('delete/(:num)', 'Peserta\Pendaftaran::deleted/$1');
});

$routes->group('pengumuman', function ($routes) {
    $routes->get('', 'Peserta\Pengumuman::index');
    $routes->get('read', 'Peserta\Pengumuman::read');
});

$routes->group('history', function ($routes) {
    $routes->get('', 'Peserta\History::index');
    $routes->get('read', 'Peserta\History::read');
});

// Juri
$routes->group('penilaian', function ($routes) {
    $routes->get('', 'Admin\Penilaian::index');
    $routes->get('getnilai/(:num)', 'Admin\Penilaian::getNilai/$1');
    $routes->get('read', 'Admin\Penilaian::read');
    $routes->post('post', 'Admin\Penilaian::post');
    $routes->put('put', 'Admin\Penilaian::put');
    $routes->delete('delete/(:num)', 'Admin\Penilaian::deleted/$1');
});

$routes->group('laporan', function ($routes) {
    $routes->get('', 'Laporan::index');
    $routes->get('hitung', 'Laporan::hitung');
    $routes->get('read', 'Laporan::read');
    $routes->post('post', 'Laporan::post');
    $routes->put('put', 'Laporan::put');
    $routes->delete('delete/(:num)', 'Laporan::deleted/$1');
});
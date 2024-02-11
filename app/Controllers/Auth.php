<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PesertaModel;

class Auth extends BaseController
{
    public function index()
    {
        $user = new UserModel();
        if ($user->countAllResults() == 0) {
            $user->insert(['username' => 'Administrator', 'password' => password_hash('Administrator#1', PASSWORD_DEFAULT)]);
        }
        return view('login');
    }

    public function login()
    {
        $user = new UserModel();
        $data = $this->request->getJSON();
        $q = $user->where('username', $data->username)->first();
        if ($q) {
            if (password_verify($data->password, $q['password'])) {
                session()->set(['uid'=>$q['id'],'nama' => 'Administrator', 'isRole' => true]);
                return $this->respond(true);
            } else return $this->fail("Password salah");
        } else return $this->fail("Username tidak ditemukan");
    }

    public function regis()
    {
        return view('regis');
    }

    public function post()
    {
        $data = $this->request->getJSON();
        $conn = \Config\Database::connect();
        $user = new UserModel();
        $peserta = new PesertaModel();
        try {
            $conn->transBegin();
            if($user->where('username', $data->username)->countAllResults()==0){
                $user->insert(['username'=>$data->username, 'password'=>password_hash($data->password, PASSWORD_DEFAULT), 'role'=>'Peserta']);
                $data->users_id = $user->getInsertID();
                $peserta->insert($data);
                $data->id = $peserta->getInsertID();
                if($conn->transStatus()){
                    $conn->transCommit();
                    return $this->respondCreated($data);
                }else{
                    throw new \Exception("Gagal Registrasi", 1);
                }
            }else throw new \Exception("User yang anda masukkan telah terdaftar", 1);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('auth'));
    }
}

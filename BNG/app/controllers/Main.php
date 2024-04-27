<?php

namespace bng\Controllers;

use bng\Controllers\BaseController;
use bng\Models\Agents;

class Main extends BaseController
{
    public function index()
    {
        if (!check_session()) {
            $this->login_frm();
            return;
        }

        $data['user'] = $_SESSION['user'];

        $this->view('layouts/html_header');
        $this->view('navbar',$data);
        $this->view('homepage',$data);
        $this->view('footer');
        
        $this->view('layouts/html_footer');
    }
    //================================================================
    //login
    //=============================================================
    public function login_frm()
    {
        if (check_session()) {
            $this->index();
            return;
        }
        $data = [];
        if (!empty($_SESSION['validation_errors'])) {
            $data['validation_errors'] = $_SESSION['validation_errors'];
            unset($_SESSION['validation_errors']);
        }
        if (!empty($_SESSION['server_error'])) {
            $data['server_error'] = $_SESSION['server_error'];
            unset($_SESSION['server_error']);
        }

        $this->view('layouts/html_header');
        $this->view('login_frm', $data);
        $this->view('layouts/html_footer');
    }
    public function login_submit()
    {
        if (check_session()) {
            $this->index();
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->index();
            return;
        }

        $validation_errors = [];
        if (empty($_POST['text_username']) || empty($_POST['text_password'])) {
            $validation_errors[] = "Username e Password são obrigatórios";
        }
        $username = $_POST['text_username'];
        $password = $_POST['text_password'];

        if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $validation_errors[] = 'O username tem que ser um email válido.';
        }
        if (strlen($username) < 5 || strlen($username) > 50) {
            $validation_errors[] = 'O username tem que ter entre 5 e 50 caracteres.';
        }
        if (strlen($password) < 6 || strlen($password) > 12) {
            $validation_errors[] = 'O password tem que ter entre 6 e 12 caracteres.';
        }





        if (!empty($validation_errors)) {
            $_SESSION['validation_errors'] = $validation_errors;
            $this->login_frm();
            return;
        }
        $model = new Agents();
        $result = $model->check_login($username, $password);
        if (!$result['status']) {
            //Logger
            Logger("$username - Login inválido",'error');

            $_SESSION['server_error'] = 'Login inválido';
            $this->login_frm();
            return;
        }
         //Logger
         Logger("$username - Login com sucesso");

        $results = $model->get_user_data($username);
    //    printData($results);
       

$_SESSION['user'] = $results['data'];
        $results = $model->set_user_last_login($_SESSION['user']->id);

      $this->index();
    }
    public function logout()
    {
        // desabilitar acesso direto ao logout
        if(!check_session()){
            $this->index();
            return;
        }

        Logger($_SESSION['user']->name.' - fez logout');

        unset($_SESSION['user']);
        $this->index();
    }

}
/* 


aula 447 encriptaçãod e dados
admin@bng.com   Aa123456
agente1@bng.com   Aa123456
agente2@bng.com   Aa123456
aula 452

*/
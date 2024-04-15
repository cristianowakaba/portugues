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
        $this->view('layouts/html_header');
        echo '<h3 class="text-white text-center">ola mundo</h3>';
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
        if (!empty($validation_errors)) {
            $_SESSION['validation_errors'] = $validation_errors;
            $this->login_frm();
            return;
            $username = $_POST['text_username'];
            $password = $_POST['text_password'];
            echo $username . '<br>. ' . $password;
        }
    }
}

<?php

namespace bng\Controllers;

use bng\Controllers\BaseController;
use bng\Models\Agents;

class Agent extends BaseController
{
    public function my_clients()
    {
        if (!check_session() || $_SESSION['user']->profile != 'agent') {
            header('Location: index.php');
        }

        $id_agent = $_SESSION['user']->id;
        $model = new Agents();
        $results = $model->get_agent_clients($id_agent);



        $data['user'] = $_SESSION['user'];
        $data['clients'] = $results['data'];


        $this->view('layouts/html_header');
        $this->view('navbar', $data);
        $this->view('agent_clients', $data);
        $this->view('footer');

        $this->view('layouts/html_footer');
    }
    //================================================================
    public function new_client_frm()
    {
        if (!check_session() || $_SESSION['user']->profile != 'agent') {
            header('Location: index.php');
        }



        $data['user'] = $_SESSION['user'];
        $data['flatpickr'] = true;
        
        //checar se tem erros na sessão
        if (!empty($_SESSION['validation_errors'])) {
            $data['validation_errors'] = $_SESSION['validation_errors'];

            unset($_SESSION['validation_errors']);
            
        }
        

        $this->view('layouts/html_header', $data);
        $this->view('navbar', $data);
        $this->view('insert_client_frm', $data);
        $this->view('footer');

        $this->view('layouts/html_footer');
    }
    //================================================================
    public function new_client_submit()
    {
        if (!check_session() || $_SESSION['user']->profile != 'agent' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
        }

        //validação do formulario
        $validation_errors = [];

        //text_name
        if (empty($_POST['text_name'])) {
            $validation_errors[] = "Nome é obrigatório";
        } else {
            if (strlen($_POST['text_name']) < 3 || strlen($_POST['text_name']) > 50) {
                $validation_errors[] = "O nome tem que ter entre 3 e 50 caracteres";
            }
        }
        // gender

        if (empty($_POST['radio_gender'])) {
            $validation_errors[] = "é obrigatórioS definir o gênero";
        }

        //text_birthdate
        if (empty($_POST['text_birthdate'])) {
            $validation_errors[] = "Data de nascimento é obrigatória";
        } else {
            $birthdate = \DateTime::createFromFormat('d/m/Y', $_POST['text_birthdate']);
            if (!$birthdate) {
                $validation_errors[] = "Data de nascimento tem que ter o formato correto.";
            } else {
                $today = new \DateTime();
                if ($birthdate >= $today) {
                    $validation_errors[] = "Data de nascimento tem que ser anterior a data atual.";
                }
            }
        }
        //email
        if (empty($_POST['text_email'])) {
            $validation_errors[] = "Email é obrigatório";
        } else {
            if (!filter_var($_POST['text_email'], FILTER_VALIDATE_EMAIL)) {
                $validation_errors[] = "Email tem que ser um email válido";
            }
        }
        //phone
        if (empty($_POST['text_phone'])) {
            $validation_errors[] = "Telefone é obrigatório";
        } else {
            if (!preg_match("/^9{1}\d{8}$/", $_POST['text_phone'])) {
                $validation_errors[] = "Telefone deve começar por 9 e ter 9 algarismos no total.";
            }
        }

        if (!empty($validation_errors)) {
            $_SESSION['validation_errors'] = $validation_errors;
           
           $this->new_client_frm();
            
        }
    }
    //================================================================
    public function edit_client($id)
    {
        echo "editar $id";
    }
    //================================================================
    public function delete_client($id)
    {
        echo "deletar $id";
    }
}

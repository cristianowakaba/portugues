<?php

namespace bng\Models;

use bng\Models\BaseModel;

class Agents extends BaseModel
{
    //=============================================================
    public function check_login($username, $password)
    {
        $params = [
            ':username' => $username
        ];
        $this->db_connect();
        $results = $this->query(
            "SELECT id, passwrd FROM agents" . " WHERE AES_ENCRYPT(:username,'" . MYSQL_AES_KEY . "') = name",
            $params
        );
        if ($results->affected_rows == 0) {
            return [
                'status' => false,
            ];
        }
        if (!password_verify($password, $results->results[0]->passwrd)) {
            return [
                'status' => false,
            ];
        }
        return [
            'status' => true,
        ];
    }
    //=============================================================

    public function get_user_data($username)
    {
        $params = [
            ':username' => $username
        ];
        $this->db_connect();
        $results = $this->query(
            "SELECT " .
                "id, " .
                "AES_DECRYPT(name, '" . MYSQL_AES_KEY . "')  name, " .
                "profile " .
                "FROM agents " .
                "WHERE AES_ENCRYPT(:username, '" . MYSQL_AES_KEY . "') = name",
            $params
        );


        return [
            'status' => 'success',
            'data' => $results->results[0]
        ];
    }
    //=============================================================

    public function set_user_last_login($id)
    {
        $params = [
            ':id' => $id
        ];
        $this->db_connect();
        $results = $this->non_query(
            "UPDATE agents SET " .
                "last_login = NOW() " .
                "WHERE id = :id",
            $params
        );

        return $results;
    }
    //=============================================================

/* *
busca todos os clientes com id passado */
    public function get_agent_clients($id_agent)
    {
        $params = [
            ':id_agent' => $id_agent
        ];
        $this->db_connect();
        $results = $this->query(
            "SELECT " .
                "id, " .
                "AES_DECRYPT(name, '" . MYSQL_AES_KEY . "')  name, " .
                "gender, " .
                "birthdate, " .
                "AES_DECRYPT(email, '" . MYSQL_AES_KEY . "') email, " .
                "AES_DECRYPT(phone, '" . MYSQL_AES_KEY . "')phone, " .
                "interests, " .
                "created_at, " .
                "updated_at " .
                "FROM persons " .
                "WHERE id_agent = :id_agent " .
                "AND deleted_at IS NULL ",
            $params
        );
       
        return [
           'status' =>'success',
            'data' => $results->results
        ];
    }
    //=============================================================

    //verifica se o cliente existe
    public function check_if_client_exists($post_data)
    {
        $params=[
            ':id_agent'=>$_SESSION['user']->id,
            ':client_name'=>$post_data['text_name']

        ];
        // var_dump($_SESSION['user']->id);
        // var_dump($post_data['text_name']);
        $this->db_connect();
        $results = $this->query(
            "SELECT id FROM persons " .
            "WHERE AES_ENCRYPT(:client_name,'" .MYSQL_AES_KEY ."') = name " . 
            "AND id_agent = :id_agent",
            $params
        );
        
        // var_dump( $results);
       if ($results->affected_rows == 0) {
            return [
               'status' => false,
            ];
        }else {
            return [
               'status' => true,
            ];
        } 
               
    } 


     // =======================================================
     public function add_new_client_to_database($post_data)
     {
         // add new client to database
 
         $birthdate = new \DateTime($post_data['text_birthdate']);
 
         $params = [
             ':name' => $post_data['text_name'],
             ':gender' => $post_data['radio_gender'],
             ':birthdate' => $birthdate->format('Y-m-d H:i:s'),
             ':email' => $post_data['text_email'],
             ':phone' => $post_data['text_phone'],
             ':interests' => $post_data['text_interests'],
             ':id_agent' => $_SESSION['user']->id
         ];
 
         $this->db_connect();
         $this->non_query(
             "INSERT INTO persons VALUES(" .
                 "0, " .
                 "AES_ENCRYPT(:name, '" . MYSQL_AES_KEY . "'), " .
                 ":gender, " .
                 ":birthdate, " .
                 "AES_ENCRYPT(:email, '" . MYSQL_AES_KEY . "'), " .
                 "AES_ENCRYPT(:phone, '" . MYSQL_AES_KEY . "'), " .
                 ":interests, " .
                 ":id_agent, " .
                 "NOW(), " .
                 "NOW(), " .
                 "NULL" .
                 ")",
             $params
         );
        }
}

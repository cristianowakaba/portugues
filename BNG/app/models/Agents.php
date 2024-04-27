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
}

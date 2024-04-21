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
        "SELECT id, passwrd FROM agents"." WHERE AES_ENCRYPT(:username,'".MYSQL_AES_KEY."') = name",$params);
   if($results->affected_rows==0){
    return [
        'status'=>false,
    ];
   }
   if(!password_verify($password,$results->results[0]->passwrd)){
    return[
        'status'=>false,
    ];
   }
   return[
    'status'=>true,
   ];
}
}
<?php

namespace App\Models\Setting;

use CodeIgniter\Model;
use Exception;

class User extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['username', 'email', 'password', 'name', 'token', 'active', 'deleted_at', 'expiry_token'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    protected function hashPassword(String $password) {
        if ($password != '') {
            $password = password_hash($password, PASSWORD_DEFAULT);
        }
        return $password;
    }

    function get_list_user($limit, $start, Array $search) {
        $q = '';
        // $limit = " limit $start , $limit";

        if ($search['search'] != '') {
            $q .= "AND u.username LIKE '%".$search['search']."%' ";
        }
        if ($search['name'] != '') {
            $q .= "AND u.name LIKE '%".$search['name']."%' ";
        }
        if ($search['active'] != '') {
            $q .= "AND u.active = '".$search['active']."' ";
        }

        
        $select = "SELECT u.*";
        $sql = " FROM users u 
                WHERE u.deleted_at is null 
                $q order by u.username asc";

        $query = $this->query($select.$sql);
        $result['data'] = $query->getResult();

        $count = "SELECT count(*) as count ";
        $result['jumlah'] = $this->query($count.$sql)->getRow()->count;

        return $result;
    }

    function get_user($id) {
        $sql = "SELECT u.* 
                FROM users u 
                WHERE u.deleted_at is null  
                    AND u.id = $id 
                LIMIT 1";

        
                
        return $this->query($sql)->getRow();
    }

    function update_user(Array $param) {
        $result = [
            'success' => false,
            'message' => '',
            'id' => null,
        ];
    
        $validation = service('validation'); 
        $validation->setRules([
            'username'  => 'required|is_unique[users.username,id,'          . (isset($param['id']) ? $param['id'] : 'NULL') . ']',
            'email'     => 'required|valid_email|is_unique[users.email,id,' . (isset($param['id']) ? $param['id'] : 'NULL') . ']'
        ]);
    
        if (!$validation->run($param)) {
            $result['message'] = implode('<br>', $validation->getErrors());
            return $result;
        }
        
        if(isset($param['id']) && !empty($param['id'])) {
            $data = array(
                "id" => $param['id'],
                "username" => $param['username'],
                "email" => $param['email'],
                "name" => $param['name'],
                "active" => $param['active']
            );
        } else {
            $data = $param;
        }

        if(isset($data['password']) && $data['password'] != null) {
            $data['password'] = $this->hashPassword($data['password']);
        }
    
        try {
            if (isset($data['id']) && !empty($data['id'])) {
                // Update
                $res = $this->update($data['id'], $data);
                if (!$res) {
                    throw new Exception($this->error()['message']);
                }
                $result['id'] = $data['id'];
                $result['message'] = "Berhasil mengubah data user";
            } else {
                // Insert
                $res = $this->insert($data);
                if (!$res) {
                    throw new Exception($this->error()['message']);
                }
                $result['id'] = $this->insertID();
                $result['message'] = "Berhasil menambahkan data user";
            }
    
            $result['success'] = true;
        } catch (Exception $e) {
            $result['message'] = $e->getMessage();
        }
    
        return $result;
    }
    
    function delete_user($id) {
        $data = array('deleted_at' => date('Y-m-d H:i:s'));
        
        $res = $this->where('id', $id)->update($id, $data);
        
        return $res;
    }


    // FUNCTION LOGIN LOGOUT
    function get_user_by_username_email($identity) {
        $data = (Object) [
            "status" => FALSE,
            "response" => NULL
        ];

        $q = '';

        if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
            $q .= "WHERE u.email = '".$identity."' ";
        } else {
            $q .= "WHERE u.username = '".$identity."' ";
        }

        $sql = "SELECT u.*
                FROM users u
                $q 
                AND u.deleted_at IS NULL
                LIMIT 1";
                
        $result = $this->query($sql)->getRow();

        if(!empty($result)) {
            $data->status = TRUE;
            $data->response = $result;
        }

        return $data;
    }

    function update_token($user_id, $token, $expiry) {
        return $this->update($user_id, [
            'token' => $token,
            'expiry_token' => $expiry
        ]);
    } 

    function clear_token($id) {
        return $this->update($id, [
            'token' => null, 
            'expiry_token' => null
        ]);
    }
}

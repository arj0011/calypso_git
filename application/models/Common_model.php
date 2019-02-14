<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common_model extends MY_Model {

    public function __construct() {
        parent::__construct();

        $this->load->config('ion_auth', TRUE);
        $this->load->helper('cookie');
        $this->load->helper('date');
        $this->load->library(array("ion_auth"));

        $this->identity_column = $this->config->item('identity', 'ion_auth');
        $this->store_salt = $this->config->item('store_salt', 'ion_auth');
        $this->salt_length = $this->config->item('salt_length', 'ion_auth');
        $this->join = $this->config->item('join', 'ion_auth');


        //initialize hash method options (Bcrypt)
        $this->hash_method = $this->config->item('hash_method', 'ion_auth');
        $this->default_rounds = $this->config->item('default_rounds', 'ion_auth');
        $this->random_rounds = $this->config->item('random_rounds', 'ion_auth');
        $this->min_rounds = $this->config->item('min_rounds', 'ion_auth');
        $this->max_rounds = $this->config->item('max_rounds', 'ion_auth');
    }

    /**
     * Hashes the password to be stored in the database.
     *
     * @return void
     * @author Developer
     * */
    public function hash_password($password, $salt = false) {
        if (empty($password)) {
            return FALSE;
        }

        //bcrypt
        if ($this->hash_method == 'bcrypt') {

            if ($this->random_rounds) {
                $rand = rand($this->min_rounds, $this->max_rounds);
                $rounds = array('rounds' => $rand);
            } else {
                $rounds = array('rounds' => $this->default_rounds);
            }

            $CI = & get_instance();

            $rounds['salt_prefix'] = '$2y$';
            $CI->load->library('frontbcrypt', $rounds);
            return $CI->frontbcrypt->hash($password);
        }


        if ($this->store_salt && $salt) {
            return sha1($password . $salt);
        } else {
            $salt = $this->salt();
            return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
        }
    }

    /**
     * This function takes a password and validates it
     * against an entry in the users table.
     *
     * @return void
     * @author Mathew
     * */
    public function hash_password_db($id, $password) {
        if (empty($id) || empty($password)) {
            return FALSE;
        }

        // $this->trigger_events('extra_where');

        $query = $this->db->select('id,password, salt')
                ->where('id', $id)
                ->limit(1)
                ->get('users');

        $hash_password_db = $query->row();

        if ($query->num_rows() !== 1) {
            return FALSE;
        }

        // bcrypt
        if ($this->hash_method == 'bcrypt') {
            $CI = & get_instance();
            $CI->load->library('frontbcrypt', null);

            if ($CI->frontbcrypt->verify($password, $hash_password_db->password)) {
                return TRUE;
            }
            return FALSE;
        }



        if ($this->store_salt) {
            return sha1($password . $hash_password_db->salt);
        } else {
            $salt = substr($hash_password_db->password, 0, $this->salt_length);

            return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
        }
    }

    /**
     * Generates a random salt value.
     *
     * @return void
     * @author developer
     * */
    public function salt() {
        return substr(md5(uniqid(rand(), true)), 0, $this->salt_length);
    }

    /**
     * encrypt value
     *
     * @return void
     * @author developer
     * */
    public function encryptPassword($password) {
        $salt = $this->store_salt ? $this->salt() : FALSE;
        return $this->hash_password($password, $salt);
    }

    /**
     * decript value.
     *
     * @return void
     * @author Mathew
     * */
    public function decryptPassword($id, $password) {

        return $this->hash_password_db($id, $password);
    }

    //Clear session data
    public function clearSessionData() {
        foreach ($this->session->userdata as $sess_var) {
            unset($sess_var);
        }
    }

    //Make the ID encrypted
    public function id_encrypt($str) {
        return $str * 55;
    }

    //Make the ID decrypted
    public function id_decrypt($str) {
        return $str / 55;
    }

    //Password 
    public function password_encrip($str) {
        return $str * 55;
    }

    function fetchSingleData($select, $table, $where) {
        $this->db->select($select);
        $this->db->from($table);
        $this->db->where($where);
        $res = $this->db->get()->row();
        return $res;
    }

    function fetchAllData($select, $table, $where) {
        $this->db->select($select);
        $this->db->from($table);
        $this->db->where($where);
        $res = $this->db->get()->result();
        return $res;
    }

    /* <!--INSERT RECORD FROM SINGLE TABLE--> */

    function insertData($table, $dataInsert) {
        $this->db->insert($table, $dataInsert);
        return $this->db->insert_id();
    }

    /* <!--UPDATE RECORD FROM SINGLE TABLE--> */

    function updateFields($table, $data, $where) {
        $this->db->update($table, $data, $where);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function deleteData($table, $where) {
        $this->db->where($where);
        $this->db->delete($table);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /* ---GET SINGLE RECORD--- */

    function getsingle($table, $where = '', $fld = NULL, $order_by = '', $order = '') {

        if ($fld != NULL) {
            $this->db->select($fld);
        }
        $this->db->limit(1);

        if ($order_by != '') {
            $this->db->order_by($order_by, $order);
        }
        if ($where != '') {
            $this->db->where($where);
        }

        $q = $this->db->get($table);
        $num = $q->num_rows();
        if ($num > 0) {
            return $q->row();
        }
    }

    /* <!--Join tables get single record with using where condition--> */

    function GetJoinRecord($table, $field_first, $tablejointo, $field_second, $field_val = '', $where = "", $group_by = '', $order_fld = '', $order_type = '', $limit = '', $offset = '') {
        $data = array();
        if (!empty($field_val)) {
            $this->db->select("$field_val");
        } else {
            $this->db->select("*");
        }
        $this->db->from("$table");
        $this->db->join("$tablejointo", "$tablejointo.$field_second = $table.$field_first", "inner");
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($group_by)) {
            $this->db->group_by($group_by);
        }

        $clone_db = clone $this->db;
        $total_count = $clone_db->count_all_results();

        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }
        if (!empty($order_fld) && !empty($order_type)) {
            $this->db->order_by($order_fld, $order_type);
        }
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
        }
        return array('total_count' => $total_count, 'result' => $data);
    }

    /* ---GET MULTIPLE RECORD--- */

    function getAllwhere($table, $where = '', $order_fld = '', $order_type = '', $select = 'all', $limit = '', $offset = '', $group_by = '') {
        $data = array();
        if ($order_fld != '' && $order_type != '') {
            $this->db->order_by($order_fld, $order_type);
        }
        if ($select == 'all') {
            $this->db->select('*');
        } else {
            $this->db->select($select);
        }
        $this->db->from($table);
        if ($where != '') {
            $this->db->where($where);
        }
        if (!empty($group_by)) {
            $this->db->group_by($group_by);
        }

        $clone_db = clone $this->db;
        $total_count = $clone_db->count_all_results();

        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }

        $q = $this->db->get();
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
        }
        return array('total_count' => $total_count, 'result' => $data);
    }

    /* ---GET MULTIPLE RECORD--- */

    function getAll($table, $order_fld = '', $order_type = '', $select = 'all', $limit = '', $offset = '', $group_by = '') {
        $data = array();
        if ($select == 'all') {
            $this->db->select('*');
        } else {
            $this->db->select($select);
        }
        if ($group_by != '') {
            $this->db->group_by($group_by);
        }
        $this->db->from($table);

        $clone_db = clone $this->db;
        $total_count = $clone_db->count_all_results();

        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }
        if ($order_fld != '' && $order_type != '') {
            $this->db->order_by($order_fld, $order_type);
        }
        $q = $this->db->get();
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
        }
        return array('total_count' => $total_count, 'result' => $data);
    }

    /* <!--GET ALL COUNT FROM SINGLE TABLE--> */

    function getcount($table, $where = "") {
        if (!empty($where)) {
            $this->db->where($where);
        }
        $q = $this->db->count_all_results($table);
        return $q;
    }

    function getTotalsum($table, $where, $data) {
        $this->db->where($where);
        $this->db->select_sum($data);
        $q = $this->db->get($table);
        return $q->row();
    }



    function GetJoinRecordNew($table, $field_first, $tablejointo, $field_second, $field, $value, $field_val, $group_by = '', $order_fld = '', $order_type = '', $limit = '', $offset = '') {
        $data = array();
        $this->db->select("$field_val");
        $this->db->from("$table");
        $this->db->join("$tablejointo", "$tablejointo.$field_second = $table.$field_first");
        $this->db->where("$table.$field", "$value");
        if (!empty($group_by)) {
            $this->db->group_by($group_by);
        }

        $clone_db = clone $this->db;
        $total_count = $clone_db->count_all_results();

        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }
        if (!empty($order_fld) && !empty($order_type)) {
            $this->db->order_by($order_fld, $order_type);
        }
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
        }
        return array('total_count' => $total_count, 'result' => $data);
    }

    function GetJoinRecordThree($table, $field_first, $tablejointo, $field_second, $tablejointhree, $field_three, $table_four, $field_four, $field_val = '', $where = "", $group_by = "", $order_fld = '', $order_type = '', $limit = '', $offset = '') {
        $data = array();
        if (!empty($field_val)) {
            $this->db->select("$field_val");
        } else {
            $this->db->select("*");
        }
        $this->db->from("$table");
        $this->db->join("$tablejointo", "$tablejointo.$field_second = $table.$field_first", 'inner');
        $this->db->join("$tablejointhree", "$tablejointhree.$field_three = $table_four.$field_four", 'inner');
        if (!empty($where)) {
            $this->db->where($where);
        }

        if (!empty($group_by)) {
            $this->db->group_by($group_by);
        }
        $clone_db = clone $this->db;
        $total_count = $clone_db->count_all_results();

        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }

        if (!empty($order_fld) && !empty($order_type)) {
            $this->db->order_by($order_fld, $order_type);
        }
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
        }
        return array('total_count' => $total_count, 'result' => $data);
    }

    function getAllwhereIn($table, $where = '', $column = '', $wherein = '', $order_fld = '', $order_type = '', $select = 'all', $limit = '', $offset = '', $group_by = '') {
        $data = array();
        if ($order_fld != '' && $order_type != '') {
            $this->db->order_by($order_fld, $order_type);
        }
        if ($select == 'all') {
            $this->db->select('*');
        } else {
            $this->db->select($select);
        }
        $this->db->from($table);
        if ($where != '') {
            $this->db->where($where);
        }
        if ($wherein != '') {
            $this->db->where_in($column, $wherein);
        }
        if ($group_by != '') {
            $this->db->group_by($group_by);
        }

        $clone_db = clone $this->db;
        $total_count = $clone_db->count_all_results();

        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }

        $q = $this->db->get();
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
        }
        return array('total_count' => $total_count, 'result' => $data);
    }

    public function clear_forgotten_password_code($code) {

        if (empty($code))
        {
            return FALSE;
        }

        $this->db->where('forgotten_password_code', $code);

        if ($this->db->count_all_results('users') > 0)
        {
            $data = array(
                'forgotten_password_code' => NULL,
                'forgotten_password_time' => NULL
            );

            $this->db->update('users', $data, array('forgotten_password_code' => $code));

            return TRUE;
        }

        return FALSE;
    }
    public function customQuery($query, $single = false, $updDelete = false, $noReturn = false) {
        $query = $this->db->query($query);

        if ($single) {
            return $query->row();
        } elseif ($updDelete) {
            return $this->db->affected_rows();
        } elseif (!$noReturn) {
            return $query->result();
        } else {
            return true;
        }
    }

    //Function for insert
    public function customInsert($options) {
        $table = false;
        $data = false;

        extract($options);


        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function customGet($options) {

        $select = false;
        $table = false;
        $join = false;
        $order = false;
        $limit = false;
        $offset = false;
        $where = false;
        $or_where = false;
        $single = false;
        $group_by = false;
        $where_not_in = false;
        $like = false;

        extract($options);

        if ($select != false)
            $this->db->select($select);

        if ($table != false)
            $this->db->from($table);

        if ($where != false)
            $this->db->where($where);

        if ($where_not_in != false) {
            if (is_array($where_not_in)) {
                foreach ($where_not_in as $key => $val) {
                    $this->db->where_not_in($key, $val);
                }
            }
        }

        if ($or_where != false)
            $this->db->or_where($or_where);

        if ($limit != false) {

            if (!is_array($limit)) {
                $this->db->limit($limit);
            } else {
                foreach ($limit as $limitval => $offset) {
                    $this->db->limit($limitval, $offset);
                }
            }
        }

        if ($like != false) {
            foreach ($like as $col => $keyword) {
                $this->db->like($col, $keyword);
            }
        }

        if ($group_by != false) {

            $this->db->group_by($group_by);
        }


        if ($order != false) {

            foreach ($order as $key => $value) {

                if (is_array($value)) {
                    foreach ($order as $orderby => $orderval) {
                        $this->db->order_by($orderby, $orderval);
                    }
                } else {
                    $this->db->order_by($key, $value);
                }
            }
        }




        if ($join != false) {

            foreach ($join as $key => $value) {

                if (is_array($value)) {
                    if (count($value) == 3) {
                        $this->db->join($value[0], $value[1], $value[2]);
                    } else {
                        foreach ($value as $key1 => $value1) {
                            $this->db->join($key1, $value1);
                        }
                    }
                } else {
                    $this->db->join($key, $value);
                }
            }
        }


        $query = $this->db->get();

        if ($single) {
            return $query->row();
        }


        return $query->result();
    }

    function insertBulkData($table, $dataInsert) {
        $this->db->insert_batch($table, $dataInsert);
    }

    function customCount($options) {
        $table = false;
        $join = false;
        $order = false;
        $limit = false;
        $offset = false;
        $where = false;
        $or_where = false;
        $where_not_in = false;
        $single = false;

        extract($options);

        if ($table != false)
            $this->db->from($table);

        if ($where != false)
            $this->db->where($where);

        if ($or_where != false)
            $this->db->or_where($or_where);
        
         if ($where_not_in != false) {
            if (is_array($where_not_in)) {
                foreach ($where_not_in as $key => $val) {
                    $this->db->where_not_in($key, $val);
                }
            }
        }

        if ($limit != false) {

            if (!is_array($limit)) {
                $this->db->limit($limit);
            } else {
                foreach ($limit as $limitval => $offset) {
                    $this->db->limit($limitval, $offset);
                }
            }
        }


        if ($order != false) {

            foreach ($order as $key => $value) {

                if (is_array($value)) {
                    foreach ($order as $orderby => $orderval) {
                        $this->db->order_by($orderby, $orderval);
                    }
                } else {
                    $this->db->order_by($key, $value);
                }
            }
        }


        if ($join != false) {

            foreach ($join as $key => $value) {

                if (is_array($value)) {
                    if (count($value) == 3) {
                        $this->db->join($value[0], $value[1], $value[2]);
                    } else {
                        foreach ($value as $key1 => $value1) {
                            $this->db->join($key1, $value1);
                        }
                    }
                } else {
                    $this->db->join($key, $value);
                }
            }
        }

        return $this->db->count_all_results();
    }


    function user_wallet_amount($user_id){
        $userdata = array();
        $userdata = $this->db->get_where(USERWALLET,array('user_id'=>$user_id))->row_array();
        if(!empty($userdata)){
            $amount = $userdata['amount'];
        }else{
            $amount = 0;
        }
        return $amount;
    }

    function send_wallet_notification($id,$user_id,$typeval,$amount,$type){
        $message = $amount." is ".$type." in wallet";  

        $insertArray = array(
          'type_id' => $id, // order id
          'sender_id' => ADMIN_ID,
          'reciever_id' => $user_id,
          'notification_type' => 'Wallet',
          'type'=> $typeval,
          'title' => ucfirst($type),
          'message' => $message,
          'is_read' => 0,
          'is_send' => 0,
          'sent_time' => date('Y-m-d H:i:s'),
        );
     
        $opt = array(
          'table'=>USER_NOTIFICATION,
          'data'=>$insertArray
        );  
        $unoti_id = $this->common_model->customInsert($opt);
        if($unoti_id){
            $params = array('order_id'=>$id,'type'=>$typeval,'noti_type'=>4);
            send_push_notifications($message,$user_id,$params);
        }
    }


    function get_user_badges($user_id) {
        $count = 0;
        $op = array(
            'table'=>USER_NOTIFICATION.' AS un',
            'select'=>'COUNT(un.id) AS badges',
            'join'=>array(USERS.' AS u'=>'u.id=un.reciever_id'),
            'where'=>array('un.reciever_id'=>$user_id,'is_read'=>0),
            'single'=>true
        );
        $countData = $this->common_model->customGet($op);
        if(!empty($countData)){
            $count = $countData->badges;
        }
        return $count;
    }

}

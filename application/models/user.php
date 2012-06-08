

<?php

class User extends CI_Model {

    var $data;

    function __construct()
    {
        $this->load->database();
        $this->load->helper('url');

        $this->set_default_data();

        parent::__construct();
    }

    function set_default_data(){
        $this->data->ID = 0;
        $this->data->name = 'User';
        $this->data->picture=$this->_get_profil_image();
        $this->data->picture_big = $this->_get_profil_image('big');
        $this->data->FbPicture = '';
        $this->data->useFbPicture = 0;
        $this->data->logged_in = false;
        $this->data->category = 0;
    }

    function clean(){
        $this->data = null;
        $this->set_default_data();
        return $this;
    }

    function load($id){
        $this->db->select('ID,rtime,email,cookie,session,ip,name,picture,FbPicture,useFbPicture,level,points,fb_post_submission,fb_post_badges');
        $this->db->from('users');
        $this->db->join('profiles', 'users.ID = profiles.IDUser', 'inner');
        $this->db->where('users.ID',$id);

        $query = $this->db->get();

        if ($query->num_rows() != 0){
            $user = $query->row();

            $this->data = $user;
            $this->data->picture = $this->_get_profil_image();
            $this->data->picture_big = $this->_get_profil_image('big');

            if($this->data->name == "" || $this->data->name == " ")
                $this->data->name="User";
        }

        return $this;
    }

    function prepareOutput(){
        $this->data->name = outputData($this->data->name);
        $this->data->email = outputData($this->data->email);
        return $this;
    }

    function getData(){
        return $this->data;
    }

    function getAlerts(){
        $this->db->select('newAlerts');
        $this->db->from('profiles');
        $this->db->where('IDUser',$this->data->ID);

        $query = $this->db->get();

        if ($query->num_rows() != 0){
            $result = $query->row();
            return $result->newAlerts;
        }
        return 0;
    }

    private function _get_profil_image($size='small'){
        if($this->data->ID==0){
            if ($size=='small') return base_url("media/images/avatars/default.png");
            else return base_url("media/images/avatars/default_big.png");
        }

    	if ($this->data->useFbPicture==1 && $this->data->FbPicture != ''){
            if($size=='small')
                return $this->data->FbPicture;
            return $this->data->FbPicture.'?type=large';

    	}
    	elseif ($this->data->picture != ''){
            if($size){
                $picture = str_replace('.jpg','_big.jpg',$this->data->picture);
                $picture = str_replace('.gif','_big.gif',$picture);
                $picture = str_replace('.png','_big.png',$picture);
                $url=@getimagesize(base_url('/media/images/avatars/'.$this->data->ID.'/'.$picture));
                if(is_array($url))
                    return base_url('media/images/avatars/'.$this->data->ID.'/'.$picture);
            }
    		return base_url('media/images/avatars/'.$this->data->ID.'/'.$this->data->picture);
    	}

        if ($size=='small') return base_url("media/images/avatars/default.png");
        return base_url("media/images/avatars/default_big.png");

    }
}


?>
<?php

class MY_Controller extends CI_Controller
{
    var $data;
    var $current_user;

    function MY_Controller ()  {
        parent::__construct();
        $this->preload();
    }

    public function preload()
    {
        $this->load->database();
        $this->load->library('session');

        $this->load->helper('url');
        $this->load->helper('security');

        $this->load->model('user','user_model');
        $this->load->model('categories','categories_model');
        $this->load->model('messages','messages_model');

        if(!isset($this->session->userdata['logged_in']))
            $this->session->set_userdata($this->user_model->getData());

        $this->current_user = $this->session->all_userdata();

        $this->data['current_user'] =  $this->session->all_userdata();
        $this->data['new_alerts'] = $this->user_model->getAlerts();
        $this->data['logged_in'] = $this->session->userdata['logged_in'];
        $this->data['categories'] = $this->categories_model->getAll();
        $this->data['messages'] = $this->messages_model;

        $facebook_config = $this->config->item('facebook');
        $this->data['facebook_config']=$facebook_config;

        //$this->start_session();
        //$this->secure_session();
        $this->facebook_login();

        if($this->session->userdata('logged_in') && ($this->session->userdata('name')=="" || $this->session->userdata('name')=="User"))
            $this->messages_model->addAlert('You have no user name, please enter one in your <a href="/account/profil">profile</a>');

    }

    /*public function start_session(){
        //Create the session for the uploadify's flash
        if (isset($_POST['session_name'])) {
            session_id($_POST['session_name']);
        }
        //Retrieve the session ID of the last login of the user
        elseif (isset($_COOKIE['SESSIONID'])){
            $this->db->select('IP');
            $this->db->from('users');
            $this->db->where('session',$_COOKIE['SESSIONID']);
            $query = $this->db->get();

            if ($query->num_rows() > 0){
                $row = $query->row();
                if ($row['IP']==$_SERVER['REMOTE_ADDR']){
                    session_id($_COOKIE['SESSIONID']);
                }
            }
        }

        session_start();
    }

    public function secure_session(){
        //Safety against session fixation attacks
        // If there isn't an active session associated with a session identifier that the user is presenting, then regenerate it
        if (!isset($_SESSION['user']['initiated'])){
            session_regenerate_id();
            $_SESSION['user']['initiated'] = true;
        }

        if (!isset($_SESSION['user']['ID']) ) {
            $_SESSION['user']['initiated'] = false;
            $_SESSION['user']['ID'] = 0;
            $_SESSION['user']['email'] = '';
        }
    }*/

    public function load_facebook_api(){
        //Code to include the facebook login/register api
        $this->load->file('application/libraries/facebook/facebook.php', false);

        // Create our Application instance (replace this with your appId and secret).
        $facebook_config = $this->config->item('facebook');
        $facebook = new Facebook(array(
          'appId'  => $facebook_config['appId'],
          'secret' => $facebook_config['secret'],
          'cookie' => true,
        ));

        // We may or may not have this data based on a $_GET or $_COOKIE based session.
        //
        // If we get a session here, it means we found a correctly signed session using
        // the Application Secret only Facebook and the Application know. We dont know
        // if it is still valid until we make an API call using the session. A session
        // can become invalid if it has already expired (should not be getting the
        // session back in this case) or if the user logged out of Facebook.
        $session = $facebook->getUser();

        $me = null;
        // Session based API call.
        if ($session) {
          try {
            $uid = $facebook->getUser();
            $me = $facebook->api('/me');
          } catch (FacebookApiException $e) {
            error_log($e);
          }
        }

        // login or logout url will be needed depending on current user state.
        if ($me) {
          $logoutUrl = $facebook->getLogoutUrl();
        } else {
          $loginUrl = $facebook->getLoginUrl();
        }

        return $me;
    }

    public function facebook_login(){
        $me = $this->load_facebook_api();

        //If not connected on the site , but connected on facebook and already accepted the fb application
        if($me['id'] && !$this->session->userdata('logged_in') == 0 && !$this->input->get('fb')){
            $this->db->select('ID');
            $this->db->from('users');
            $this->db->where('facebook_UID',$me['id']);
            $query = $this->db->get();

            if ($query->num_rows() == 0)
                redirect('/account/facebookRegister/', 'refresh');
            else
                redirect('/account/facebookLogin/'.$me['id'], 'refresh');
        }
    }
}
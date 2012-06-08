<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends MY_Controller {

    function  __construct()  {
        parent::MY_Controller();
    }

    public function index(){
        $this->data['title'] = 'ArtDuel - A new kind of artistic and creative platform!';

        $this->load->view('page/head',$this->data);
        $this->load->view('page/header',$this->data);
        $this->load->view('page/footer',$this->data);
    }

    function login(){
        $email=$this->input->post('login_email');
        $password=$this->input->post('login_password');

        if($this->input->post('submit')){
            if($this->input->post('login_email_b')){
                $email = $this->input->post('login_email_b');
                $password = $this->input->post('login_password_b');
            }

        	$succeed=false;

        	if(isBot($this->input->post())===true) $this->message_model->addError("We have detected that you could be a bot... if you're not, leave the red input empty!");
        	else {

                $this->db->select('IP');
                $this->db->from('login_attempts');
                $this->db->where('IP',$_SERVER['REMOTE_ADDR']);
                $this->db->where('username',$email);
                $this->db->where('date >=',date('Y-m-d H:i:s',time()-60*15));

                $query = $this->db->get();

        		if (!$query || $query->num_rows() < 8){
        			if(!$email)
                        $this->messages_model->addError("You need to fill in an <strong>Email</strong> adress.");
        			if(!$password)
                        $this->messages_model->addError("You need to fill in a <strong>Password</strong>.");

        			if ($this->messages_model->getNbErrors()==0){
                        $this->db->from('users');
                        $this->db->where('email',$email);

                        $query = $this->db->get();
                        $user = $query->row();

        				//check if there was not a match
        				if(!$user){
                            $this->messages_model->addError("The <strong>Email</strong> address you supplied does not exist.");
        				}else{
        					//checking the password
        					$validPassword=false;

        					//Check the password from the login form
        					$password=hash("sha256",$password.$user->rtime.$this->config->item("encryption_key"));
        					if ($password === $user->password){
        						$validPassword=true;
        						$active=$user->active;
        						$id=$user->ID;
        					}

        					if(!$validPassword){
                                $this->messages_model->addError('The <strong>Password</strong> you supplied does not match the one for that email adress.');
        					}else{

        						//check to see if the user has not activated their account yet
        						if($active != 1){
                                    $this->messages_model->addError("Your account has been desactivated for some reason.");
        						}else{
        							$succeed=true;
        							if(isset($_POST['login_remember']))
        								$this->_login($id,true,NULL);
        							else
        								$this->_login($id,false,NULL);

        						}
        					}
        				}
        			}

        			if (!$succeed){
                        $data = array(
                           'IP' => $_SERVER['REMOTE_ADDR'] ,
                           'username' => $email ,
                           'date' => date('Y-m-d H:i:s')
                        );

                        $this->db->insert('login_attempts', $data);
        			}
        		}
                else{
                    $this->messages_model->addError("You have reached the maximum of attempts, wait 15 minutes and try again.");
                }
        	}

        }

        if (isset($_GET["confirmation"])){
            $this->messages_model->addSuccess("You have successfully registered, please visit your email inbox to activate your account before you can log in.");
        }
        if (isset($_GET['message'])){
            $this->messages_model->addSuccess("Your new password has been set. You can now log in with the same email adress and your new password.");
        }

        //http://stackoverflow.com/questions/9170449/codeigniter-after-method-equivalent-of-remap
        //http://forrst.com/posts/CodeIgniter_before_after_controller_action-Diy

        $this->data['page_title'] = 'ArtDuel - A new kind of artistic and creative platform!';
        $this->load->view('page/head',$this->data);
                $this->load->view('page/header',$this->data);
                $this->load->view('page/footer',$this->data);
        //redirect('/');
    }

    function facebookLogin(){

    }

    private function _login($id,$remember,$uid){
        $this->db->select('ID')->from('users');

        if(isset($uid))
            $this->db->where('facebook_UID',$uid);
        else
            $this->db->where('ID',$id);

        $query = $this->db->get();

        if ($query->num_rows() == 0)
            return false;

        $row = $query->row_array();

        /*$this->data['ID']=$row['ID'];
        $this->data['logged_in']=true;

        $_SESSION['user']=$row['ID'];
        $_SESSION['user']['logged_in']=true;*/

        $this->session->set_userdata(array('logged_in'=>true));

        $data = array(
           'dateLastLogIn' => date('Y-m-d H:i:s'),
           'IP' => $_SERVER['REMOTE_ADDR']
        );

        $remember_string = false;

        if(isset($remember)){
            $remember_string = uniqid('',true);
            $data['session'] = $remember_string;
        }

        $this->db->where('id', $id);
        $this->db->update('users', $data);

        $url='index.php';

        //Set the cookie to autologin
        header("Location: ".base_url());
        if(isset($remember)){
            setcookie('remember_me_token', $remember_string,time()+60*60*24*355,'/','.artduel.com',FALSE,TRUE);
        }
        else{
            setcookie('remember_me_token', '',NULL,'/','.artduel.com',FALSE,TRUE);
        }
        exit;
    }

    public function facebookRegister(){

    }

    public function register(){

    }

}

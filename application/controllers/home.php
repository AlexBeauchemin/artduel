<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

    function  __construct()  {
        parent::MY_Controller();
    }

	public function index()
	{
        $this->data['page_title'] = 'ArtDuel - A new kind of artistic and creative platform!';

        //$active_category=$this->_setCategory($this->data['categories']);
        $active_category = $this->current_user['category'];
        $vote = $this->_retrieveVote();

        if($vote && !$this->_checkMaxVote()){
           $this->_processVote($vote);
           $this->_addVoteLimit();
        }

        $this->load->model('submission','challenger1');
        $this->load->model('submission','challenger2');

        $this->_selectChallenger1($active_category);
        $this->_selectChallenger2();

        $challenger1 = $this->challenger1->getData();
        $challenger2 = $this->challenger2->getData();

        $this->data['show_retry']=false;
        $this->data['newDuelID']=$this->_createDuel($challenger1,$challenger2);
        if(!$this->data['newDuelID'])
           $this->data['show_retry']=true;

        //Get the top stats
        $this->db->select('IDUser,name,points,picture,FbPicture,useFbPicture');
        $this->db->from('profiles');
        $this->db->order_by('points','desc');
        $this->db->limit('5');
        $query = $this->db->get();
        $top_users = array();
        $this->load->model('user','top_user');
        foreach ($query->result() as $row){
            //$top_users[] = $row;
            $top_users[] = $this->top_user->load($row->IDUser)->prepareOutput()->getData();
            $this->top_user->clean();
        }

        //Get top sequence
        $this->db->select('submissions.ID');
        $this->db->from('submissions');
        $this->db->join('submissionsCategories','submissionsCategories.IDSubmission = submissions.ID');
        if($active_category)
            $this->db->where('submissionsCategories.IDCategory',$active_category);
        $this->db->order_by('sequence','desc');
        $this->db->limit('1');
        $query = $this->db->get();
        $top_sequence = $query->row();
        $this->load->model('submission','top_sequence');
        $this->top_sequence->load($top_sequence->ID);

        //Get top percent
        $this->db->select('submissions.ID');
        $this->db->from('submissions');
        $this->db->join('submissionsCategories','submissionsCategories.IDSubmission = submissions.ID');
        $this->db->where('win + lose >',9);
        if($active_category)
            $this->db->where('submissionsCategories.IDCategory',$active_category);
        $this->db->order_by('win/(win+lose)','desc');
        $this->db->limit('3');
        $query = $this->db->get();
        $top_percent = array();
        foreach ($query->result() as $row){
            $this->load->model('submission','top_percent');
            $this->top_percent->load($row->ID);
            $top_percent[] = $this->top_percent->prepareOutput()->getData();
        }

        $this->data['image_margin'] = $this->_getImageMargin($this->challenger1,$this->challenger2);
        $this->data['challenger1'] = $this->challenger1->prepareOutput();
        $this->data['challenger2'] = $this->challenger2->prepareOutput();
        $this->data['top_users'] = $top_users;
        $this->data['top_sequence'] = $this->top_sequence->prepareOutput()->getData();
        $this->data['top_percent'] = $top_percent;
        $this->data['additional_js'] = array('index.js');

        $this->load->view('page/head',$this->data);
        $this->load->view('page/header',$this->data);
        $this->load->view('home',$this->data);
        $this->load->view('page/footer',$this->data);
	}

    public function category(){
        if($this->uri->segment(3)=="all")
            $this->session->unset_userdata('category');
        else{
            $uri = '/'.uri_string();
            foreach($this->data['categories'] as $category){
                if($category->url==$uri)
                    $this->session->set_userdata(array('category'=>$category->ID));
            }
        }

        $this->index();
    }

    private function _setCategory($categories){
        /*//Remove the cookie if the user has selected "everything" as category
        if(isset($_GET['cat']) ){
            if($_GET['cat']==0){
                setcookie('CATEGORY', '',time()-1000,'/','.artduel.com',FALSE,TRUE);
                return null;
            }
        }

        //Redirect the user to the good category's page if the cookie exists
        if(strtolower($_SERVER['PHP_SELF'])=='/index.php' && isset($_COOKIE['CATEGORY'])){
            foreach($categories as $category){
                if($category->id==$_COOKIE['CATEGORY']){
                   header("Location: ".$category->url);
                   exit;
                }
            }
        }

        //create the cookie if the user request for a category
        foreach($categories as $category){
            if(strtolower($_SERVER['PHP_SELF'])==$category->url){
                $create_cookie=false;
                if(!isset($_COOKIE['CATEGORY']))
                    $create_cookie=true;
                else{
                    if($_COOKIE['CATEGORY']!=$category->id)
                        $create_cookie=true;
                }


                if($create_cookie)
                    setcookie('CATEGORY', secure_url_param($category->ID,'numeric'),time()+60*60*12,'/','.artduel.com',FALSE,TRUE);

                return $category['ID'];
            }
        }*/
        return null;
    }

    private function _retrieveVote(){
        //Retrieve the vote (checkin the x-position of the button cause its an image input that is sending the form)
        if (isset($_POST['vote1_x'])){
            $vote['id_win']=$_POST['fighter1'];
            $vote['id_lose']=$_POST['fighter2'];
            $vote['id_duel']=$_POST['IDDuel'];
            $vote['id_user1']=$_POST['iduser1'];
            $vote['id_user2']=$_POST['iduser2'];
            return $vote;
        }
        elseif (isset($_POST['vote2_x'])){
            $vote['id_win']=$_POST['fighter2'];
            $vote['id_lose']=$_POST['fighter1'];
            $vote['id_duel']=$_POST['IDDuel'];
            $vote['id_user1']=$_POST['iduser2'];
            $vote['id_user2']=$_POST['iduser1'];
            return $vote;
        }
        return false;
    }

    private function _checkMaxVote(){
        if (!$this->current_user['logged_in']) {
            //If the user has reached the maximum of vote without being loggued in (cookie)
            if (isset($_COOKIE['limit'])){
                $cookie_info=explode('-',$_COOKIE['limit']);
                if ($cookie_info[0]>=20){
                    $this->messages_model->addError("You reached the maximum vote for today. Sorry for the inconvenience but this is to avoid abuses. To continue voting, please login or subscribe. It's fast, easy and you will be able to send your own creations!");
                    return true;
                }
            }
            else {
                //If the user is not loggued in and this IP has reached the limit of vote
                $qry_duplicates_ip = mysql_query('SELECT Count(ID) as total FROM duels WHERE IP="'.$_SERVER['REMOTE_ADDR'].'" AND IDUser=0 AND Winner > 0 AND Date > "'.date('Y-m-d H:i:s',time()-(60*60*24)).'" GROUP BY IP');
                $ip_abuse=mysql_fetch_array($qry_duplicates_ip);
                if ($ip_abuse['total']>=10) {
                    $this->messages_model->addError("You reached the maximum vote for today. Sorry for the inconvenience but this is to avoid abuses. To continue to vote, please login or subscribe. It's fast, easy and you will be able to post your own creations!");
                    return true;
                }
            }
        }
        return false;
    }

    private function _processVote($vote){
        $qry_duel=mysql_query("SELECT * FROM duels WHERE UID='".protect($vote['id_duel'])."'
                                                         AND Winner=0
                                                         AND IDUser=".protect($this->current_user['ID'])."
                                                         AND (fighter1=".protect($vote['id_win'])." OR fighter1=".protect($vote['id_lose']).") AND (fighter2=".protect($vote['id_win'])." OR fighter2=".protect($vote['id_lose']).")");

        if (mysql_num_rows($qry_duel)==0)
            $this->messages_model->addError("Sorry, we were unable to retrieve the duel you are trying to vote for.");
        else{
            //Update the database
            mysql_query("UPDATE submissions SET win=win+1,sequence=sequence+1 WHERE ID=".protect($vote['id_win']));
            mysql_query("UPDATE submissions SET lose=lose+1,sequence=0 WHERE ID=".protect($vote['id_lose']));
            mysql_query("UPDATE duels SET winner=".protect($vote['id_win'])." WHERE UID='".protect($vote['id_duel'])."'");

            //Update the sequence max
            $qry_sequence_max=mysql_query('SELECT sequence,sequence_max FROM submissions WHERE ID='.protect($vote['id_win']));
            $sequence_max=mysql_fetch_array($qry_sequence_max);
            if($sequence_max['sequence'] > $sequence_max['sequence_max']){
                mysql_query('UPDATE submissions SET sequence_max = '.$sequence_max['sequence'].' WHERE ID='.protect($vote['id_win']));
            }

            $qry_sequence=mysql_query('SELECT ID,sequence FROM submissions WHERE ID='.protect($vote['id_win']));
            $sequence=mysql_fetch_array($qry_sequence);

            //Give points to the users
            if ($this->session->userdata('user_data')){
                give_points(10+$sequence['sequence'],$vote['id_win'],NULL);
                give_points(1,NULL,$this->current_user['ID']);
                mysql_query('UPDATE profiles SET votes=votes+1 WHERE IDUser='.protect($this->current_user['ID']));
                check_badges(12,$this->current_user['ID'],NULL);
            }
            else {
                give_points(5,$vote['id_win'],NULL);
            }
            give_points(-5,$vote['id_lose'],NULL);

            //Update the number of new fights for the user
            mysql_query('UPDATE profiles SET newAlerts=newAlerts+1 WHERE IDUser='.protect($vote['id_user1']));
            if ($vote['id_user1']!=$vote['id_user2']){
                mysql_query('UPDATE profiles SET newAlerts=newAlerts+1 WHERE IDUser='.protect($vote['id_user2']));
            }

            //Check for level up
            update_level($this->current_user['ID'],NULL);
            update_level(NULL,$vote['id_win']);
            update_level(NULL,$vote['id_lose']);

            //Check for badges
            check_badges(1,$vote['id_user1'],$vote['id_win']);
            check_badges(7,$vote['id_user1'],$vote['id_win']);
        }
    }

    private function _addVoteLimit(){
        //Set the cookie to max 10 votes/day for unregistered users
        if (!$this->current_user['logged_in']){
            if (!isset($_COOKIE['limit'])){
                setcookie('limit', '1-'.strval(time()+60*60*24), time()+60*60*24);
            }
            else {
                $cookie_info=explode('-',$_COOKIE['limit']);
                $expiration= $cookie_info[1];
                $number=$cookie_info[0]+1;
                setcookie('limit', $number.'-'.$expiration,$expiration);
            }
        }
    }

    private function _selectChallenger1($category){
        //Select the first challenger
        $random = true;

        if ($category)
            $random = false;

        $count=0;
        while($this->challenger1->data->ID==0 && $count<20){
            if($random){
                $IDSubmission = $this->_random_submission(NULL,NULL);
                if($IDSubmission)
                    $this->challenger1->load($IDSubmission);
            }
            else
                $this->challenger1->load($this->_random_submission($category,NULL));

            $count++;
        }
    }

    private function _selectChallenger2(){
        $count=0;
        if ($this->challenger1->data->ID != 0){
            while($this->challenger2->data->ID ==0 && $count<20){
                $IDSubmission=$this->_random_submission($this->challenger1->data->IDCategory,$this->challenger1->data->IDUser);
                if($IDSubmission)
                    $this->challenger2->load($IDSubmission);
                $count++;
            }
        }
        return $this->challenger2;
    }

    private function _createDuel($challenger1,$challenger2){
        if ($challenger1->ID && $challenger2->ID){
            //Insert the duel in the database, waiting for the user to choose a winner
            $add_id=uniqid();
            $add_fighter1=$challenger1->ID;
            $add_fighter2=$challenger2->ID;
            $add_date=date('Y-m-d H:i:s');
            $add_ip=$_SERVER['REMOTE_ADDR'];
            $add_userid=$this->current_user['ID'];
            mysql_query("INSERT INTO duels (UID,fighter1,fighter2,date,IP,IDUser) VALUES ('$add_id',$add_fighter1,$add_fighter2,'$add_date','$add_ip',$add_userid)");
            return $add_id;
        }

        $this->messages_model->addError("Error retrieving the duelers from the database.");
        return false;
    }

    private function _random_submission($category,$first){
    	#$level=rand(1,3);
    	//Based on : http://www.greggdev.com/web/articles.php?id=6

    	if ($category!='')
    		$category='AND IDCategory='.$category;

    	if (isset($first))
    		$first='AND users.ID!='.$first;
        else
            $first='';

    	#$qry_random_row=mysql_query('SELECT FLOOR(RAND() * COUNT(*)) AS row, submissions.ID FROM submissions INNER JOIN submissionsCategories ON submissions.ID = submissionsCategories.IDSubmission INNER JOIN users ON submissions.IDUser = users.ID INNER JOIN profiles ON profiles.IDUser = users.ID WHERE users.ID!='.$_SESSION['user']['ID'].' AND submissions.active=1 AND profiles.level='.$level.' '.$category.' '.$first);
    	$qry_random_row=mysql_query('SELECT FLOOR(RAND() * COUNT(*)) AS row, submissions.ID FROM submissions INNER JOIN submissionsCategories ON submissions.ID = submissionsCategories.IDSubmission INNER JOIN users ON submissions.IDUser = users.ID INNER JOIN profiles ON profiles.IDUser = users.ID WHERE users.ID!='.$this->current_user['ID'].' AND submissions.active=1 '.$category.' '.$first);
        if (mysql_num_rows($qry_random_row)>0){
    		$random_row=mysql_fetch_array($qry_random_row);
    		#$qry_submission=mysql_query('SELECT submissions.ID FROM submissions INNER JOIN submissionsCategories ON submissions.ID = submissionsCategories.IDSubmission INNER JOIN users ON submissions.IDUser = users.ID INNER JOIN profiles ON profiles.IDUser = users.ID WHERE users.ID!='.$_SESSION['user']['ID'].' AND submissions.active=1 AND profiles.level='.$level.' '.$category.' '.$first.' LIMIT '.$random_row['row'].', 1;');
            $qry_submission=mysql_query('SELECT submissions.ID FROM submissions INNER JOIN submissionsCategories ON submissions.ID = submissionsCategories.IDSubmission INNER JOIN users ON submissions.IDUser = users.ID INNER JOIN profiles ON profiles.IDUser = users.ID WHERE users.ID!='.$this->current_user['ID'].' AND submissions.active=1 '.$category.' '.$first.' LIMIT '.$random_row['row'].', 1;');
            $submission=mysql_fetch_array($qry_submission);

    		return $submission['ID'];
    	}
    	else{
    		return NULL;
    	}
    }

    private function _getImageMargin($challenger1,$challenger2){
        $margin[0] = 0;
        $margin[1] = 0;
        if ($challenger1->data->ID && $challenger2->data->ID){
            if(file_exists($challenger1->getImageServerPath('medium')))
                $height1 = getimagesize($challenger1->getImageServerPath('medium'));
            else
                $height1[1] = 300;
            if(file_exists($challenger2->getImageServerPath('medium')))
                $height2 = getimagesize($challenger2->getImageServerPath('medium'));
            else
                $height2[1]=300;

            if ($height1[1] > $height2[1])
                $margin[1] = floor(($height1[1] - $height2[1]) / 2);
            else
                $margin[0] = floor(($height2[1] - $height1[1]) / 2);

        }
        return $margin;
    }

}

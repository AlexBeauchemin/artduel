<?php

class Submission extends CI_Model
{
    var $data;
	var $rawData;

    function __construct()
    {
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('security');

        $this->data->ID=0;

        parent::__construct();
    }

    function load($id)
    {
		if(!is_numeric($id))
			return $this;

        $this->db->select('submissions.ID,image,submissions.name,profiles.name as username,submissions.IDUser,submissions.description,tags,win,lose,dateAdded,active,sequence,sequence_max,categories.name AS category,categories.ID as IDCategory');
        $this->db->from('submissions');
        $this->db->join('submissionsCategories','submissionsCategories.IDSubmission = submissions.ID','inner');
        $this->db->join('categories','categories.ID = submissionsCategories.IDCategory','inner');
        $this->db->join('profiles','profiles.IDUser = submissions.IDUser','inner');
        $this->db->where('submissions.active',1);
        $this->db->where('submissions.ID',$id);

        $query = $this->db->get();

        if (!$query || $query->num_rows() == 0)
            return $this;

        $submission = $query->row();
        $this->data = $submission;

        $this->data->username = $this->_getUsername($this->data->username);
        if($this->data->win + $this->data->lose != 0)
            $this->data->percent = round($this->data->win/($this->data->win+$this->data->lose)*100);
        else
            $this->data->percent = "n/a";

        $this->rawData = $this->data;

		return $this;
    }

    function prepareOutput(){
        foreach($this->data as $key => $data){
            $this->data->$key = outputData($data);
        }
        return $this;
    }

    function getData(){
        return $this->data;
    }

    function getRawData(){
        return $this->rawData;
    }

    private function _getUsername($username){
    	if ($username=="" || $username==" "){
    		return "User";
    	}
    	else {
    		return $username;
    	}
    }

    function getImageUrl($size){
        $size=strtolower($size);
        switch($size){
            case 'small':
                return base_url('media/images/submissions/'.$this->data->IDUser.'/'.outputData($this->data->image).'_small.jpg');
                break;
            case 'medium':
                return base_url('media/images/submissions/'.$this->data->IDUser.'/'.outputData($this->data->image).'_medium.jpg');
                break;
            case 'large' || 'big':
                return base_url('media/images/submissions/'.$this->data->IDUser.'/'.outputData($this->data->image).'_big.jpg');
        }

    }

    function getImageServerPath($size){
        $base_path=$this->config->item('root');

        $size=strtolower($size);
        switch($size){
            case 'small':
                return $base_path.'/media/images/submissions/'.$this->data->IDUser.'/'.outputData($this->data->image).'_small.jpg';
                break;
            case 'medium':
                return $base_path.'/media/images/submissions/'.$this->data->IDUser.'/'.outputData($this->data->image).'_medium.jpg';
                break;
            case 'large' || 'big':
                return $base_path.'/media/images/submissions/'.$this->data->IDUser.'/'.outputData($this->data->image).'_big.jpg';
        }
    }

	function save(){
        if($this->data->ID == 0)
            return false;

        //save to database
	}

	function addWin(){
		$this->data->sequence+=1;
	}

    function getMessages(){
        $qry_messages = mysql_query('SELECT IDUserSend,Message,profiles.Name as Username FROM submissionsMessages INNER JOIN profiles ON IDUserSend = profiles.IDUser WHERE submissionsMessages.IDSubmission='.$this->ID.' ORDER BY submissionsMessages.DateAdded DESC');
        $array = array();
        while($message=mysql_fetch_assoc($qry_messages))
            array_push($array,$message);
        return $array;
    }

	function addLose(){

	}

    function getRank(){
        /*$test = mysql_query("SELECT name, points, FIND_IN_SET(points, (SELECT  GROUP_CONCAT( DISTINCT points ORDER BY points DESC )FROM profiles)) as rank FROM  profiles");
        while ($row = mysql_fetch_assoc($test)) {
            var_dump($row);
        }

        $test2 = mysql_query("SELECT @row := @row+1 as rank, name, points from (select name, count(*) as points FROM profiles, (SELECT @row:=0) r group by name order by points desc) x;");
        while ($row = mysql_fetch_assoc($test2)) {
            var_dump($row);
        }*/

        $qry_rank = mysql_query("SELECT uo.*, (SELECT  COUNT(*) FROM submissions ui INNER JOIN submissionsCategories ON ui.ID = submissionsCategories.IDSubmission WHERE IDCategory = ".$this->IDCategory." AND ui.win+ui.lose>9 AND (ui.win/ui.lose, ui.ID) >= (uo.win/uo.lose, uo.ID)) AS rank FROM submissions uo WHERE ID=".$this->ID);
        $qry_total = mysql_query("SELECT COUNT(*) AS total FROM submissions INNER JOIN submissionsCategories ON submissionsCategories.IDSubmission = submissions.ID WHERE submissionsCategories.IDCategory=".$this->IDCategory);
        $rank = mysql_fetch_array($qry_rank);
        $total=mysql_fetch_array($qry_total);
        return $rank['rank'].'/'.$total['total'];
    }

}


?>
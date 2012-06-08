<?php

class Messages extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function getErrors(){
        return $this->getItems('messages_errors');
    }

    function getNbErrors(){
        $items = $this->session->userdata("messages_errors");
        if(!$items)
            return 0;
        return count($items);
    }

    function addError($message){
        $this->addItem('messages_errors',$message);
    }

    function getAlerts(){
        return $this->getItems('messages_alerts');
    }

    function addAlert($message){
        $this->addItem('messages_alerts',$message);
    }

    function getSuccess(){
        return $this->getItems('messages_success');
    }

    function addSuccess($message){
        $this->addItem('messages_success',$message);
    }

    function getItems($type){
        if(count($this->session->userdata($type))){
            $messages = $this->session->userdata($type);
            $this->session->set_userdata($type,array());
            return $messages;
        }
        return array();
    }

    function getAll(){
        $messages['messages_errors'] = $this->getErrors();
        $messages['messages_alerts'] = $this->getAlerts();
        $messages['messages_success'] = $this->getSuccess();
        return $messages;
    }

    function addItem($type='',$message=''){
        $items = array();
        if(count($this->session->userdata($type)))
            $items = $this->session->userdata($type);
        $items[] = $message;
        $this->session->set_userdata($type,$items);
    }
}
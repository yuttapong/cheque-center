<?php

define('SESSION_NAME','SESSION_CHEQUE');

############ Session สำหรับเรียกใช้งานเก็บข้อมูลไซต์หรือบริษัทที่เลือกใช้งาน ###########
class SessionStore {

  var $session = array();
  var $username = '';

  function __construct(){
    if(! session_start())
      session_start();
    $this->session = $_SESSION[SESSION_NAME];
	$this->username = $_SESSION['USERNAME'];
    }



  function _set($data = array()){
    if(is_array($data)){
      foreach ($data as $key => $value) {
        $_SESSION[SESSION_NAME][$key] = $value;
      }
    }
  }

  function _get($name){
    return isset($_SESSION[SESSION_NAME][$name])?$_SESSION[SESSION_NAME][$name]:NULL;
  }

  function _unset($name){
      unset($_SESSION[SESSION_NAME][$name]);
  }

  function _getCompany($id){
    $sql = "select * from org_company  where id='$id' order by name asc limit 1 ";
  	$rs = mysql_query($sql) or die(mysql_error());
  	$row = mysql_fetch_object($rs);
	   return $row;
  }

  function _getSite($id){
    $sql = "select * from org_site  where site_id='$id' limit 1 ";
  	$rs = mysql_query($sql) or die(mysql_error());
  	$row = mysql_fetch_object($rs);
	   return $row;
  }

  //check login session
  function isLoggedIn(){
     return ! empty($this->username)?true:false;
  }

  //  Is admin ?
  function isAdmin(){
     return ! empty($this->username) && 'admin' == strtolower($this->username)?true:false;
  }




  #บันทึก session ที่ได้จากแบบฟอร์มเลือกบริษัท
  function save(){
    $this->clear();
    $id_company = $_POST['company'];
    $username = $_POST['username'];
    $site_id = $_POST['site'];
    $site = $this->_getSite($site_id);
    $company = $this->_getCompany($site->company_id);
    $data = array(
      'company_id' => $company->company_id,
      'company_code' => $company->code,
      'company_name' => $company->name,
      'company_contact' => $company->contact,
      'company_img' => $company->img,
      'site_id' => $site->site_id,
      'site_name' => $site->site_name,
      'username' => $username,
    );
    if($site_id != ''){
      $this->_set($data);
    }
  }

  function clear(){
      unset($_SESSION[SESSION_NAME]);
      $this->session = array();
  }

  function hasSet(){
    if(isset($_SESSION[SESSION_NAME]) && ! empty($_SESSION[SESSION_NAME])){
      return true;
    }else{
      return false;
    }
  }

  function getCompanyName(){
      return $this->session['company_name'];
  }

  function getCompanyId(){
      return $this->session['company_id'];
  }

    function getCompanyCode(){
        return $this->session['company_code'];
    }

  function getSiteName(){
      return $this->session['site_name'];
  }

  function getSiteId(){
      return $this->session['site_id'];
  }


    function getUsername(){
        return $this->session['username'];
    }


	}

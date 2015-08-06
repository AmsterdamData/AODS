<?php
$path = '/home/amsterdam/domains/tools.amsterdamopendata.nl/public_html/AODS/classes/phpseclib/';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

include("Net/SSH2.php");
include("Net/SFTP.php");
include(dirname(__FILE__) ."/settings.php");

class AODS{
    var $sftp;
    
    function AODS(){
        $this->sftp = null;
    }
    
    function connect(){
        if(!$this->sftp){
            $domain = $this->settings->sftp->domain;
            $user = $this->settings->sftp->username; 
            $password = $this->settings->sftp->password;
    
            $this->sftp = new Net_SFTP(SFTP_DOMAIN, 22, 30);
            if(!$this->sftp->login(SFTP_USERNAME, SFTP_PASSWORD)) {
                exit('Login Failed');
            }
            $this->setFolder(SFTP_TARGETFOLDER);
        }
    }
    
    function setFolder($folder){
        $this->sftp->chdir($folder);
    }
    
    function upload($filelocation){
        $this->connect();
        $contents = file_get_contents($filelocation);
        $fname = substr($filelocation, strrpos($filelocation, "/") + 1);
        $this->sftp->put($fname, $contents);
    }
}
?>

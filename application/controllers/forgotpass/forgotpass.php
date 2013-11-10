<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: forgotpass controller class
 */
class forgotpass extends CI_Controller{
	

	public function loadview(){
	$this->load->view('common/header2');
	$this->load->view('signupsviews/passwordchange.php');


	}


public function forget()
{
    if (isset($_GET['info'])) {
           $data['info'] = $_GET['info'];
          }
    if (isset($_GET['error'])) {
          $data['error'] = $_GET['error'];
          }
     
    $this->load->view('signupsviews/passwordchange.php',$data);
}

public function doforget()
{
    $this->load->helper('url');
    $email= $_POST['email'];
    $q = $this->db->query("select * from users where userName='" . $email . "'");
    if ($q->num_rows > 0) {
        $r = $q->result();
        $user=$r[0];
        $this->resetpassword($user);
        $info= "Password has been reset and has been sent to email id: ". $email;
        redirect('/index.php/forgotpass/forget?info=' . $info, 'refresh');
    }
    $error= "The email id you entered not found on our database ";
    redirect('/index.php/forgotpass/forget?error=' . $error, 'refresh');
     
}

    private function resetpassword($user)
{
    date_default_timezone_set('GMT');
    $this->load->helper('string');
    $password= random_string('alnum', 16);
    $this->db->where('userid', $user->id);
    $this->db->update('users',array('password'=>MD5($password)));
    $this->load->library('email');
    $this->email->from('htahir111@gmail.com', 'Hamza Tahir');
    $this->email->to($user->email);   
    $this->email->subject('Password reset');
    $this->email->message('You have requested the new password, Here is you new password:'. $password);  
    $this->email->send();
}

}
?>
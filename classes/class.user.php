<?php
include('class.password.php');
class User extends Password{
    private $db;
	function __construct($db){
		parent::__construct();
		$this->_db = $db;
	}
	// check if user is logged in
	public function is_logged_in(){
		if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
			return true;
		}
	}
	// retrieve username and password from database
	private function get_user_hash($username){
		try {
			$stmt = $this->_db->prepare('SELECT MemberID, username, password FROM blog_members WHERE username = :username');
			$stmt->execute(array('username' => $username));
			return $stmt->fetch();
		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}
	// verify hash
	public function login($username,$password){
		$user = $this->get_user_hash($username);
		// if hash successfully verified, set session 
		if($this->password_verify($password,$user['password']) == 1){
		    $_SESSION['loggedin'] = true;
		    $_SESSION['memberID'] = $user['memberID'];
		    $_SESSION['username'] = $user['username'];
		    return true;
		}
	}
	// end session on logout
	public function logout(){
		session_destroy();
	}
}
?>
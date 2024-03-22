<?php
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    public function testValidLogin()
    {

        
        $_POST['username'] = 'user1';
        $_POST['password'] = 'user1';
        $_POST['loginsubmitted']=true;

        ob_start();
        require 'login.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Log in successful', $output());
    }

    public function testEmptyUsername(){
        $_POST['username'] = 'user1';
        $_POST['password'] = 'wrongpassword'; // Incorrect password
        $_POST['loginsubmitted'] = true;
    
        ob_start();
        require 'login.php';
        $output = ob_get_clean();
    
        $this->assertStringContainsString('Error logging in, password does not match', $output());
    }


}






?>

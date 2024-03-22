<?php
use PHPUnit\Framework\TestCase;

class signUpTest extends TestCase
{
    
  
        public function testValidSignUp()
        {
            // Set up POST data for a valid sign-up
            $_POST['username'] = 'testUserrrrrr';
            $_POST['password'] = 'password';
            $_POST['first-name'] = 'tester';
            $_POST['last-name'] = 'tester';
            $_POST['email'] = 'hello@hello.com';
            $_POST['house-number'] = '11';
            $_POST['address-line1'] = 'tester street';
            $_POST['address-line2'] = 'tester road';
            $_POST['post-code'] = 'ABC DEF';
            $_POST['city'] = 'London';
            $_POST['country'] = 'United Kingdom';
            $_POST['signupsubmitted'] = true;
    
            // Capture the output of the script
            ob_start();
            require 'signup.php';
            $output = ob_get_clean();
    
            // Assert that the output contains a success message
            $this->assertStringContainsString('Sign up successful', $output);
        }
    
        
    }
       





var usernameError = document.getElementById("usernameError");
var passwordError = document.getElementById("passwordError");
var loginError = document.getElementById("loginError");

function validateUsername(){
    var username = document.getElementById("username").value;
    if(!username.match(/^[a-zA-Z0-9!@#$%^]{5,}$/)
    ){
        usernameError.innerHTML = "Please enter a valid username of at least 5 characters";
        usernameError.style.color = "red"; 
        
        return false;
    }
    usernameError.innerHTML = '';
    return true;
}

function validatePassword(){
    var password = document.getElementById("password").value;
    if(!password.match((/^[a-zA-Z0-9!@#$%^]{5,}$/))){
        passwordError.innerHTML = "Please enter a valid password of at least 5 characters";
        passwordError.style.color = "red";
        return false;

    }
    passwordError.innerHTML = '';
    return true;
}

function validateForm(){
    if(validateUsername() && validatePassword()){
        return true;
    }
    else{

    
    loginError.innerHTML = "Please fill in all the fields correctly";
    loginError.style.color = "red";;
    return false;
    }

}

var nameError= document.getElementById("nameError");
var emailError=document.getElementById("emailError");
var messageError=document.getElementById("messageError");
var submitError=document.getElementById("submitError");


function validateName(){
    var name= document.getElementById("name").value;
    if(!name.match(/^[a-zA-Z ]{2,}$/)){
        nameError.innerHTML="Please enter your name";
        nameError.style.color="red";
        return false;
    }
    nameError.innerHTML='Valid';
    nameError.style.color='green';
    return true;
}

function validateEmail(){
    var email = document.getElementById("email").value;

    if(!email.match(/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,}$/) ){
        emailError.innerHTML = "Please enter a valid email";
        emailError.style.color = "red"; 
        
        return false;
    }
    emailError.innerHTML = "Valid";
    emailError.style.color="green";
    return true;

}

function validateMessage(){
    var message= document.getElementById("message").value;
    if(!message.match(/^[a-zA-Z ]{2,}$/)){
        messageError.innerHTML="Please enter a valid message";
        messageError.style.color="red";
        return false;

    }
    messageError.innerHTML='Valid';
    messageError.style.color='green';
    return true;
}

function validateForm(){
    if(validateName() && validateEmail() && validateMessage() ){
            return true;
        } else{
            submitError.innerHTML = "Please fill in all the fields correctly";
            submitError.style.color = "red";
            return false
        }
 }    


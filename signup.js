
var firstNameError = document.getElementById("first-name-error");
var lastNameError = document.getElementById("last-name-error");
var usernameError = document.getElementById("username-error");
var passwordError = document.getElementById("password-error");
var emailError = document.getElementById("email-error");
var houseNumberError= document.getElementById("house-number-error");
var addressLine1Error = document.getElementById("address-line1-error");
var addressLine2Error = document.getElementById("address-line2-error");
var postCodeError = document.getElementById("post-code-error");
var cityError = document.getElementById("city-error");
var countryError = document.getElementById("country-error");
var submitError = document.getElementById("signup-error");


function validateFirstName(){
    var firstName = document.getElementById("first-name").value;
    if(firstName.length ==0 ){
        firstNameError.innerHTML = "Please dont leave this empty";
        firstNameError.style.color = "red";
        return false;
    }

    if(!firstName.match(/^[a-zA-Z]{2,}$/) ){
        firstNameError.innerHTML = "Please enter a valid first name";
        firstNameError.style.color = "red"; 
        
        return false;
    }
    firstNameError.innerHTML = "Valid";
    firstNameError.style.color = "green";
    return true;
}


function validateLastName(){
    var lastName = document.getElementById("last-name").value;
    if(lastName.length ==0 ){
        lastNameError.innerHTML = "Please dont leave this empty";
        lastNameError.style.color = "red";
        return false;
    }

    if(!lastName.match(/^[a-zA-Z]{2,}$/) ){
        lastNameError.innerHTML = "Please enter a valid last name ";
        lastNameError.style.color = "red"; 
        
        return false;
    }
    lastNameError.innerHTML = "Valid";
    lastNameError.style.color = "green";
    return true;
}


function validateUsername(){
    var username = document.getElementById("username").value;
  
    if(!username.match(/^[a-zA-Z0-9!@#$%^]{5,}$/)
    ){
        usernameError.innerHTML = "Please enter a valid username of at least 5 characters  you can include !@#$%^";
        usernameError.style.color = "red"; 
        
        return false;
    }
    usernameError.innerHTML = "Valid";
    usernameError.style.color = "green";
    return true;
}

function validatePassword(){
    var password = document.getElementById("password").value;
    if(!password.match((/^[a-zA-Z0-9!@#$%^]{5,}$/))){
        passwordError.innerHTML = "Please enter a valid password of at least 5 characters  you can include !@#$%^";
        passwordError.style.color = "red";
        return false;

           }
    passwordError.innerHTML = "Valid";
    passwordError.style.color = "green";
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
    emailError.style.color = "green";
    return true;


}


function validateHouseNumber(){
    var houseNumber = document.getElementById("house-number").value;
    if(houseNumber.length ==0 ){
        houseNumberError.innerHTML = "Please dont leave this empty";
        houseNumberError.style.color = "red";
        return false;
    }

    if(!houseNumber.match(/^[0-9]+$/) ){
        houseNumberError.innerHTML = "Please enter a valid house number";
        houseNumberError.style.color = "red"; 
        
        return false;
    }
    houseNumberError.innerHTML = "Valid";
    houseNumberError.style.color = "green";
    return true;
}

function validateAddressLine1(){
    var addressLine1 = document.getElementById("address-line1").value;
    if(addressLine1.length ==0 ){
        addressLine1Error.innerHTML = "Please dont leave this empty";
        addressLine1Error.style.color = "red";
        return false;
    }

    // Allow alphanumeric characters and spaces
    if (!addressLine1.match(/^[a-zA-Z0-9\s]+$/)) {
        addressLine1Error.innerHTML = "Please enter a valid address line 1";
        addressLine1Error.style.color = "red";
        return false;
    }
    
    addressLine1Error.innerHTML = "Valid";
    addressLine1Error.style.color = "green";
    return true;
}


function validateAddressLine2(){

}

function validatePostCode(){
    var postCode = document.getElementById("post-code").value;
    if(postCode.length ==0 ){
        postCodeError.innerHTML = "Please dont leave this empty";
        postCodeError.style.color = "red";
        return false;
    }

    if(!postCode.match(/^[0-9a-zA-Z\s-]{3,}$/) ){
        postCodeError.innerHTML = "Please enter a valid post code";
        postCodeError.style.color = "red"; 
        
        return false;
    }
    postCodeError.innerHTML = "Valid";
    postCodeError.style.color = "green";
    return true;
}   

function validateCity(){
    var city = document.getElementById("city").value;
    if(!city.match(/^[a-zA-Z\s.'-]{2,}$/) ){
        cityError.innerHTML = "Please enter a valid city";
        cityError.style.color = "red"; 
        
        return false;
    }
    cityError.innerHTML = "Valid";
    cityError.style.color = "green";
    return true;
}

 function validateCountry(){
    var country = document.getElementById("country").value;
    if(!country.match(/^[a-zA-Z\s.'-]{2,}$/) ){
        countryError.innerHTML = "Please enter a valid country";
        countryError.style.color = "red"; 
        
        return false;
    }
    countryError.innerHTML = "Valid";
    countryError.style.color = "green";
    return true;
 }

 function validateForm(){
    if(validateFirstName() && validateLastName() && validateUsername() && validatePassword() && validateEmail() && validateHouseNumber() && validateAddressLine1() && validatePostCode() && validateCity() && validateCountry()){
        return true;
    }
    else{
        submitError.innerHTML = "Please fill in all the fields correctly";
        submitError.style.color = "red";;
        return false
    }
 }

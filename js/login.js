$(document).ready(function(){
    'use strict';
    console.log("READY");
    function validateForm(e){
        let valid = true;
        errorMessage.text("");
        if(/^\w{4,20}$/.test(username.val()) == false){
            valid = false;
            errorMessage.append("Username must be between 4 and 20 alphanumeric characters. ");
        }
        if(/^\w{8,20}$/.test(password.val()) == false){
            valid = false;
            errorMessage.append("Password must be between 8 and 20 alphanumeric characters.");
        }
        if(valid === false){
            e.preventDefault();
        }
    }
    let username = $("#username");
    let password = $("#password");
    let errorMessage = $("#errorMessage");
    $("#loginForm").submit(function(e){
        validateForm(e);
    });
});
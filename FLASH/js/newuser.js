$(document).ready(function(){

$("#newuser-form").submit(validateNewUser);
function validateNewUser(){
    
    var errormsg = '';
    
    if( !$("#username").val().match(/^[-\w]+$/) ){
        errormsg += "Your username must include one or more letters, numbers, hyphens, and/or underscores\n";
    }

    if( !$("#password").val().match(/\S/) ){
        errormsg += "Your password must include one or more non-space characters\n";
    }

    if( $("#password").val() != $("#password-clone").val() ){
        errormsg += "Passwords do not match\n";
    }

    if( !$("#name").val().match(/^[ -\w]+$/) ){
        errormsg += "Your name "+$("#name").val()+" must include one or more letters, numbers, hyphens, spaces, and/or underscores\n";
    }
    
    // Trim down all but password
    $("#username").val( $("#username").val().trim() );
    $("#name").val( $("#name").val().trim() );
    
    if(errormsg != ''){
        alert(errormsg);
        event.preventDefault();
        
    }
}
    
});
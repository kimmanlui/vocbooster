$(document).ready(function(){

    // Log user out
    $("#logout").click(function(){
        $.removeCookie('userid');
        $.removeCookie('name');
    });
    
});
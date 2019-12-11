$(document).ready(function(){

// TODO check for duplicate deck name when creating new deck. call to php script perhaps?
    
setDeckTitleDisplay();
setMethodDisplay();
$("select[name=deckid]").change( setDeckTitleDisplay );
$("input[name=input-method]").change( setMethodDisplay );

// clickable labels
$("#radio-upload + span").click(function(){ $("#radio-upload").prop("checked", true).change(); });
$("#radio-manual + span").click(function(){ $("#radio-manual").prop("checked", true).change(); });

var cardInputClicked = false;
$("#card-input").click( cardInputClick );

// Force validation before submission
var faultLine = "";
$("#add-card-form").submit(function(){
    
    var errormsg = '';
    if( $("#deckid").val() == -1 &&
        $("input[name=new-deck-title-input]").val() == ""
    ){
        errormsg += "Provide a title for your new deck\n";
    }
    
    if( $("#radio-upload").prop("checked") == true &&
        $("input[name=input-file]").val() == '' ){
            
        errormsg += "Provide a filename\n";
        
    }else if( $("#radio-manual").prop("checked") == true ){
        if( 
            $("#card-input").val().match(/^\s*$/) 
        ){
            errormsg += "Enter values in card input textbox\n";
            
        }else if( (faultLine = validateCardInput() ) != true ){
            
            errormsg += "Line improperly formatted: \""+faultLine+"\"\n";
            
        }
        
    }
    
    if(errormsg != ''){
        alert(errormsg);
        event.preventDefault();
        
    }
    
});

function setDeckTitleDisplay(){
    if($("select[name=deckid]").val() != -1 ){
        $("#new-deck-title").hide();
    }else{
        $("#new-deck-title").show();
    }
}

function setMethodDisplay(){
    if(         $("#radio-upload").prop("checked") == true ){
        $("#file-upload").show();
        $("#card-input").hide();
    }else if(   $("#radio-manual").prop("checked") == true ){
        $("#file-upload").hide();
        $("#card-input").show();
    }
}

function cardInputClick(){
    if(cardInputClicked == false){
        cardInputClicked = true;
        $("#card-input").text("");
    }
    
}   

// Ensures manually entered cards conform to "key,value"
// returns true if validated, a string if not
function validateCardInput(){
    var lines = $("#card-input").val().split(/\n+/);
    var i;
    for(i=0; i < lines.length; i++){
        if(!lines[i]){
            continue;
        }
        if( !lines[i].match(/^\s*.*\S+.*\s*,\s*\s*.*\S+.*\s*$/) ){
            return lines[i];
        }
    }
    return true;
}

});

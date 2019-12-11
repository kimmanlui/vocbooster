$(document).ready(function(){

var userid = 1;

refreshButtonVisibility();

$("input[name=deckid]").click( refreshButtonVisibility );
$("#select-all").click( toggleSelectAll );

function toggleSelectAll(){
    if( $("#select-all").prop("checked") == true ){
        $("input[name=deckid]").prop("checked",true);
    }else{
        $("input[name=deckid]").prop("checked",false);
    }
    refreshButtonVisibility();
}

$("#btn-delete").click( function(){
    window.location = "deckaction.php?action=delete&deckids="+getDeckIDs();
});


$("#btn-combine").click( function(){
    var newDeckTitle = prompt("Enter a title for your new deck:");
    if(newDeckTitle.length == 0){
        alert( "Error: must include a title for your new deck" );
    }else{
        window.location = "deckaction.php"+
            "?action=combine"+
            "&deckids="+getDeckIDs()+
            "&newdecktitle="+encodeURIComponent(newDeckTitle)+
            "&userid="+userid;
    }
});

$("#btn-copy").click( function(){
    var newDeckTitle = prompt("Enter a title for your new deck:");
    if(newDeckTitle.length == 0){
        alert( "Error: must include a title for your new deck" );
    }else{
        window.location = "deckaction.php"+
            "?action=copy"+
            "&deckids="+getDeckIDs()+
            "&newdecktitle="+encodeURIComponent(newDeckTitle)+
            "&userid="+userid;
    }
});

function getDeckIDs(){
    return $("input[name=deckid]:checked").map( function(){
        return this.value; 
    }).get().join(); 
}

function refreshButtonVisibility(){
    var boxesChecked =  $("input[name=deckid]").map( function(){ 
            return $(this).prop("checked");
        } ).get();
    
    
    // If at least one box is checked
    if( boxesChecked.indexOf(true) != -1){
        $("#btn-delete").show();
        // If just one
        if( boxesChecked.indexOf(true) == boxesChecked.lastIndexOf(true) ){
            $("#btn-combine").hide();
            $("#btn-copy").show();
        }else{ // if more than 1
            $("#btn-combine").show();
            $("#btn-copy").hide();
        }
    }else{
        $("#btn-copy").hide();
        $("#btn-combine").hide();
        $("#btn-delete").hide();
    }
}

});
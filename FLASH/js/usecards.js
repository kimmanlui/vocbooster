$(document).ready(function(){

if( $("#flashcard").length != 0 ){

    // var flashCards = [
    	// {front:"1 + 1 = _",		back:"2"},
    	// {front:"a,b,c,d,_ ?",	back:"e"},
    	// {front:"dat front",		back:"dat back"},
    	// {front:"front4",		back:"back4"},
    	// {front:"front5",		back:"back5"},
    // ];
    
    // flashcards deck pulled from container
    
    var placeHolderCard = {front:"[No cards]", back:"[No cards]"};
    
    var deck = range(0, flashCards.length-1);  // cards belong in the deck, until they are discarded
    var discard = [];
    var nextDeck = [];
    var roundNum = 1;
    var roundStats = { correct: 0, incorrect: 0 };
    var numunflip   = 0; 
    var cardFrame = {
    	currentCardIndex: null,
    	currentCard: null,
    	element: $("#flashcard p"),
    	state: "unflipped", // flipped or unflipped
    	
    
    	initialize: function(){
    		
    		if(undefined){
    			console.log("deck:");
    			console.log(deck);
    			console.log("discard:");
    			console.log(discard);
    			console.log("nextDeck");
    			console.log(nextDeck);
    			console.log();
    		}
    	
    		if(deck.length > 0){
    		    this.shuffle();
    			this.currentCardIndex = deck[0];
    			this.currentCard = flashCards[this.currentCardIndex];
    		}else{
    			this.currentCardIndex = undefined;
    			this.currentCard = placeHolderCard;
    		}
    		this.showFront();
    		
    		refreshCounter();
    		updateRound();
    		updateStats();
    		
    		if (0.4<=this.currentCard.score && this.currentCard.score<=0.6)
    		{
    			document.getElementById("flashcard").style.backgroundImage="url('img/blue.jpg')";
    		} else if (this.currentCard.score>0.6)
    		{
    			document.getElementById("flashcard").style.backgroundImage="url('img/papergreen3.jpg')";
    		} else
    		{
    			document.getElementById("flashcard").style.backgroundImage="url('img/red.png')";
    		}
    	},
    	
    	showFront: function(){
    		this.element.text( this.currentCard.front );
    		numunflip=0;
    	},
    	
    	flip: function(){
    		if( this.state == "unflipped" ){
    			this.element.text( this.currentCard.back );
    			this.state = "flipped";
    			document.getElementById("flashcard").style.color = "#0000ff";
    			
    			if ( numunflip == 0 )
    			{
    			  var lccontent =  this.currentCard.front + "`-0.5" ;
                  gbcontent = gbcontent+ "^$"+lccontent; 
    			}
                numunflip=numunflip+1;
    	  	    //document.getElementById("flashcard").style.height = "50px";
    	  	    //document.getElementById("flashcard2").style.visibility = "visible";
 
    			
    		}else{
    			this.element.text( this.currentCard.front );
    			this.state = "unflipped";
    			document.getElementById("flashcard").style.color = "#000000";
                //document.getElementById("flashcard").style.height = "230px";
                //document.getElementById("flashcard2").style.visibility = "hidden";
    		}
    	},
    	
    	correct: function(){
    		// kimman
    		// alert(this.currentCard.deckid);
    		var lccontent =  this.currentCard.front + "`1" ;
            gbcontent = gbcontent+ "^$"+lccontent; 
            
    	    roundStats.correct++;
    		discard.push(deck.shift());
    		this.initialize();
    		if(deck.length == 0){
    			this.deckClosure();
    		}
    		document.getElementById("flashcard").style.color = "#000000";
    		 //document.getElementById("flashcard").style.height = "230px";
    		  //document.getElementById("flashcard2").style.visibility = "hidden";
    	},
    	
    	incorrect: function(){
    		// kimman
    		var lccontent =  this.currentCard.front + "`-1" ;
    		gbcontent = gbcontent+ "^$"+lccontent; 
    		
    	    roundStats.incorrect++;
    		nextDeck.push(deck.shift());
    		this.initialize();
    		if(deck.length == 0){
    			this.deckClosure();
    		}
    		document.getElementById("flashcard").style.color = "#000000";
    		 //document.getElementById("flashcard").style.height = "230px";
    		  //document.getElementById("flashcard2").style.visibility = "hidden";
    	},
    	
    	skip: function(){
    		// kimman
    		// alert(this.currentCard.front +":"+ jvuserid);
    		if(deck.length > 0){
    			var lccontent =  this.currentCard.front + "`0" ;
    			gbcontent = gbcontent+ "^$"+lccontent; 
    			
    			deck.push(deck.shift());
    			this.initialize();
    		}
    		document.getElementById("flashcard").style.color = "#000000";
    		 //document.getElementById("flashcard").style.height = "230px";
    		  //document.getElementById("flashcard2").style.visibility = "hidden";
    	},
    	
    	play: function(){
    		// kimman
            //alert(this.currentCard.au);
            var base64string=getRemote(this.currentCard.au);
    		var snd = new Audio(base64string);
            snd.play();
    		
            //var audio = new Audio(this.currentCard.au);
            //audio.play();
    	},
    	
    	deckClosure: function(){
    		if(false){
        		console.log("discard:");
        		console.log(discard);
        		console.log("nextDeck");
        		console.log(nextDeck);
    		}

    		gbcontent=gbcontent.substring(2);
    		var sendString="deckid="+jvdeckid;
    		sendString = sendString+"&sid="+sid;
    		sendString = sendString+"&userid="+jvuserid;
            sendString = sendString+"&data="+gbcontent;
    		gbcontent=""; 
    		var  url='record.php?'+sendString;
            sendOut(url);
    		//alert(sendString);
    		
    		alert(
    			"Round over! " + discard.length + "/" + ( discard.length + nextDeck.length ) 
    			+ "correct. " + nextDeck.length + " cards moved to next round"
    		);
 
    		nextRound();
    	},
    	
    	shuffle: function(){
    	    deckClone = deck.slice(0, deck.length);
    	    shuffledDeck = [];
    	    while( shuffledDeck.length < deck.length ){
    	        randomIndex = Math.floor((Math.random() * deckClone.length-1) );
    	        shuffledDeck.push( deckClone.splice(randomIndex,1)[0] );
    	    }
    	    deck = shuffledDeck;
    	}
    };
    
    
    
    // Initializes board
    (function initialize(){ 
    	cardFrame.initialize();
    	$("#flashcard")		.on( "click", function(){cardFrame.flip();} );
    	$("#btn-correct")	.on( "click", function(){cardFrame.correct();} );
    	$("#btn-incorrect")	.on( "click", function(){cardFrame.incorrect();} );
    	$("#btn-skip")		.on( "click", function(){cardFrame.skip();} );
    	$("#btn-play")		.on( "click", function(){cardFrame.play();} );
    
        // Keyboard controls
        $("body").keypress(function(e){
            //console.log(e.charCode);
            switch(e.charCode){
                case 97: // a
                    cardFrame.skip();
                    break;
                case 115: // s
                    cardFrame.correct();
                    break;
                case 100: // d
                    cardFrame.incorrect();
                    break;
                case 102: // f
                    cardFrame.flip();
                    break;
            }
        });
    	
    	// Debug menu
    	$("#debug button").on( "click", function(){
    		cardFrame.flip();
    	} );
    })();
    
    function nextRound(){
    	deck = nextDeck;
    	nextDeck = [];
    	discard = [];
    	incrementRound();
        roundStats.correct = 0;
        roundStats.incorrect = 0;
    	cardFrame.initialize();
    }
    
    function  getRemote(remote_url) {
      return $.ajax({
        type: "GET",
        url: remote_url,
        async: false
      }).responseText;
    }
    
    // Sets counter to "x of <total>"
    function refreshCounter(){
    	$(" #card-counter ").text(deck.length + " remaining");
    }
    
    // Updates correct and incorrect counters
    function updateStats(){
        $(" #correct-counter ").text(roundStats.correct);
        $(" #incorrect-counter ").text(roundStats.incorrect);
        
    }
    
    function incrementRound(){
    	roundNum++;
    	updateRound();
    }
    
    function updateRound(){
    	$(" #round-counter ").text("Round " + roundNum);
    }
    
    function range(start, end) {
        var foo = [];
        for (var i = start; i <= end; i++) {
            foo.push(i);
        }
        return foo;
    }
    
    function sendOut(url)
    {
      const Http = new XMLHttpRequest();
      Http.open("GET", url);
      Http.send();
      Http.onreadystatechange=(e)=>{
      //alert(Http.responseText);
    }	
}

}

});
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
    	},
    	
    	showFront: function(){
    		this.element.text( this.currentCard.front );
    	},
    	
    	flip: function(){
    		if( this.state == "unflipped" ){
    			this.element.text( this.currentCard.back );
    			this.state = "flipped";
    			document.getElementById("flashcard").style.color = "#0000ff";
    		
    			
    		}else{
    			this.element.text( this.currentCard.front );
    			this.state = "unflipped";
    			document.getElementById("flashcard").style.color = "#000000";

    		}
    	},
    	
    	correct: function(){
    	    roundStats.correct++;
    		discard.push(deck.shift());
    		this.initialize();
    		if(deck.length == 0){
    			this.deckClosure();
    		}
    		document.getElementById("flashcard").style.color = "#000000";
    	},
    	
    	incorrect: function(){
    	    roundStats.incorrect++;
    		nextDeck.push(deck.shift());
    		this.initialize();
    		if(deck.length == 0){
    			this.deckClosure();
    		}
    		document.getElementById("flashcard").style.color = "#000000";
    	},
    	
    	skip: function(){
    		if(deck.length > 0){
    			deck.push(deck.shift());
    			this.initialize();
    			
    		}
    		document.getElementById("flashcard").style.color = "#000000";
    	},
    	
    	deckClosure: function(){
    		if(false){
        		console.log("discard:");
        		console.log(discard);
        		console.log("nextDeck");
        		console.log(nextDeck);
    		}
    		
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

}

});
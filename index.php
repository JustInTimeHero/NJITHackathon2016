<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" href="vendor/font-awesome-4.6.3/css/font-awesome.min.css">
	<title>Social Media Parser</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="reddit.js"></script>
	<script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
</head>
<body onload="loadListen()">

<style>
	#mic {
		position: relative;
		z-level: 1;
	}
	svg text {
		font-family: FontAwesome;
	}
}
</style>

<div class="jumbotron text-center">
	<h1>
		<svg height="100" width="100">
			<circle id="radiate" cx="50" cy="50" r="0" stroke="black" stroke-width="3" fill="skyblue"></circle>
			<text x="50%" y="50%" text-anchor="middle" alignment-baseline="central"
			onclick="micClick();">&#xf130;</text>
		</svg>
	</h1>
	<form action="Temp.php" method="post">
		<div class="checkbox">
			<!--<label><input type="checkbox" name="age" value="true">
			I am 18 or older.</label>-->
		</div>
	</form>
	<div id="results">
		<span class="final" id="final_span" onclick="final_transcript = '';"></span>
		<span class="interim" id="interim_span"></span>
	</div>
</div>

<div>
<span id="reddit-content">
</span>
</div>

<script>
	speechSynthesis.cancel();
	document.getElementById("reddit-content").innerHTML = "";
	started = false;
	function loadListen() {
		console.log("AAAAA");
		var final_word= '';
		var recognizer = new webkitSpeechRecognition();
		recognizer.lang = 'en-US';
		recognizer.continuous = true;
		recognizer.interimResults = false;
		recognizer.start();

		recognizer.onresult = function(event) {
			var interim_transcript = '';
			
			for (var i = event.resultIndex; i < event.results.length; ++i) {
				if (event.results[i].isFinal) {
					final_word = event.results[i][0].transcript;
				}
			}
			console.log(final_word);
			if (final_word == "begin" || final_word == " begin" || final_word == "start" || final_word == " start"
			|| final_word == "commence" || final_word == " commence") {
				recognizer.stop();
				micClick();
			}
			else {
				final_word = "";
			}
		};
	}
	
	document.getElementById("radiate").addEventListener("transitionend", waitReset);
	function micHover() {
		document.getElementById("radiate").style.transitionDuration="2s";
		document.getElementById("radiate").style.r="40";
	}
	function waitReset() {
		setTimeout(reset, 450);
	}
	function reset() {
		document.getElementById("radiate").style.transitionDuration="0s";
		document.getElementById("radiate").style.r="0";
	}
	function micClick() {
		if (started == false) {
			started = true;
			micHover();
			hover = setInterval(micHover, 2500);
			console.log("START");
			closer = 0;
			var final_transcript = '';
			recognition = new webkitSpeechRecognition();
			recognition.lang = 'en-US';
			recognition.continuous = true;
			recognition.interimResults = false;
			recognition.start();
			recognition.onresult = function(event) {
				console.log(19999);
				clearTimeout(closer);
				
				for (var i = event.resultIndex; i < event.results.length; ++i) {
					if (event.results[i].isFinal) {
						final_transcript += event.results[i][0].transcript;
					}
				}
				final_span.innerHTML = final_transcript;
			};
			if (started == true) {
				recognition.onspeechend = function() {
					console.log("PPPPPPPPPP");
					//closer = setTimeout(stopper, 3000);
				}
			}
			recognition.onend = function() {
				started = false;
				console.log("STOPPED'CAUSEEND");
				clearInterval(hover);
				console.log(hover);
				loadListen();
				console.log("a");
				execute_query("subreddit="+final_transcript);
				var message = document.getElementById("reddit-content").innerHTML;
				var msg = new SpeechSynthesisUtterance(message);
				speechSynthesis.speak(msg);
				console.log(message);
				window.open("http://www.reddit.com/r/"+final_transcript, "_blank");
			};
			/*function stopper() {
				console.log(10100);
				recognition.stop();
			}*/
		}
		else {
			started = false;
			console.log(started);
			recognition.stop();
		}
	}
</script>

</body>
</html>
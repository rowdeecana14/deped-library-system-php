
localStorage.setItem("counter", 120);
localStorage.setItem("reset", 0);
var counter = localStorage.getItem("counter");
var modal = false;

function resetTimer() {
	if(modal == false) {
		localStorage.setItem("counter", 120);
	}
	counter = localStorage.getItem("counter");
	localStorage.setItem("reset", 1);
}
function timeIt() {
	
	counter = localStorage.getItem("counter");
	//console.log(counter);
	
	if(counter <= 10 ) {
		$("#t").text(""+counter);
	}
	if(counter <= 0) {
		window.location.href = "lockscreen.php? lock=true";
	}
	if(counter == 10) {
		modal = true;
		$.confirm({
			title: 'Warning Message',
			content: "<div class='row'><center>Automatic lockscreen <h1 id='t' style='color:red'>"+counter+"</h1></center></div>",
			icon: 'glyphicon glyphicon-info-sign',
			theme: 'bootstrap',
			 type: 'orange',
			buttons: {
				Cancel: {
					text: "Cancel",
					btnClass: "btn-danger",
					keys: ['esc'],
					action: function() {
						localStorage.setItem("counter", 120);
					}
				},
				Okay: {
					text: 'Okay',
					btnClass: 'btn-primary',
					keys: ['enter'],
					action: function() {
						window.location.href = "../index.php";
					}
				}
			}
		})
	}
	counter--;
	localStorage.setItem("counter", counter);
}
setInterval(timeIt, 1000);
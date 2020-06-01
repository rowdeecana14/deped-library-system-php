notification();
function notification() {
$.ajax({
	url: "model/borrowed.php",
	dataType:'json',
	type: "POST",
	data:{ 
		action:"alert",
	},
	success: function(data) {
		recordNotify(data);
	},
	error: function(){
		alert("error");
	}
});
}
function recordNotify(data) {
var list = data.data;
var length = data.data.length;
var html = "";

if(length > 0) {

	for(var x=0; x<length; x++) {
		html = html +'<li>\
		  <a>\
			<span class="image"><img src="books/'+list[x].image+'" alt="Profile Image" /></span>\
			<span>\
			  <span>'+list[x].title+'</span>\
			</span>\
			<span class="message">'+list[x].grade+'</span>\
		  </a>\
		</li>';
	}
	html = html +'<li>\
		  <div class="text-center">\
			<a href="unreturned_books.php">\
			  <strong>See all records</strong>\
			  <i class="fa fa-angle-right"></i>\
			</a>\
		  </div>\
		</li>\
		';

}
$("#menu1").html(html);
$("#alert").text(data.total);
}
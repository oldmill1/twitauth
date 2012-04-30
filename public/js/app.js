(function($) {	
	var users = $("#users li"); 
	var links = users.find( "a" ); 
	var user_ids = new Array(); 
	var main = $("#main"); 
				
	users.each( function() { 
		var id = $(this).attr("id"); 
		if ( id != undefined ) { 
			user_ids.push(id);  
		}
	});  
	
	var app = { 
		print_tweet: function(status, screen_name, status_id) { 
			buttons = "<div id='actions' class='well'><a href='' id='"+status_id+"' class='btn btn-primary'>ReTweet</button></div>"; 
			text = "<li class='tweet-view'><h2>" + status + "</h2><p>" + screen_name + "</p>"+buttons+"</li>"; 
			main.find("ul").prepend(text); 
		}, 
	}; // app 
	
	links.click( function(event) { 
		event.preventDefault(); 
		$(".alert").hide(); 
		main.prepend("<img class='working' src='/twitauth/public/img/load.gif' />");
		 
		id = $(this).parent().attr("id");
		
		var data = { 
			'id': id
		}; 
		
		$.post( 
			'/twitauth/app.php', 
			data, 
			function( response ) { 
				// todo: check if status exists before going deeper
				$(".working").hide(); 
				//console.dir( response ); 

				var status = response[0].status.text; 
				var status_id = response[0].status.id; 
				var screen_name = response[0].screen_name; 
				app.print_tweet( status, screen_name, status_id ); 
			}, 
			'json'
		);  	
	}); 
	
})( jQuery ); 
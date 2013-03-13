var sxswObject = function(){
	var gigs,
		searchAutoComplete = [],
		gigHTML ='';
	return{
		init:function(){
			
			searchAutoComplete = phpSearchArray;
			gigHTML = phpGigHTML;
						
		},
		searchAutoComplete : function(){
			return searchAutoComplete;
		},
		gigHTML : function(){
			return gigHTML;
		},
		searchValue : function(searchSet){
			var searchUnique = [];
			
			$.each(searchAutoComplete, function(i, el){
				var string = searchAutoComplete[i].toUpperCase();
				if(string.search(searchSet.toUpperCase()) != -1){
					searchUnique.push(searchAutoComplete[i])
				}
			});
			
			sortList.checkResults(searchUnique);
		}
		
	}
}();

var sortList = function(){
	return {
		checkResults : function(gigSet /*array*/){
			var gigs = sxswObject.searchAutoComplete();
			var results = []
			
			for(var i=0; i < gigSet.length; i++){
				if($.inArray(gigSet[i], gigs) != -1){
					
					results.push('.'+gigSet[i].replace(/[-!$%^&*()_+|~=`{}\[\]:";'<>?,.\/]|@| /g,''));
				}
			}
						
			this.showGigs(results);
			
		},
		showGigs:function(gigSet){
			var gigHTML = sxswObject.gigHTML();
			
			
			$('#masterList .grid-container div').remove();
			
			var currentHTML = gigHTML.find(gigSet.toString()).clone(),
				currentDate = '',
				days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
				today = new Date(),
				text_day = '';
			
			$.each(currentHTML,function(key, value){
				var $date = $(value).find('.gigInfo input[name=date]').val();
				
				if(currentDate != $date){
					currentDate = $date;
					var jsdate = new Date($date+'T12:00');
					if(today.getDay() == jsdate.getDay())
						text_day = 'today';
					else if(today.getDay() - jsdate.getDay() == -1)
						text_day = 'tomorrow';
					else
						text_day = days[jsdate.getDay()];
					
					$('#masterList .grid-container').append('<div id="eventDate" class="event-date grid-12">'+text_day+'</div>');
				}
				$('#masterList .grid-container').append(value);
			});
			
			scrollWindow.go();
			
			
			
		}
	}
}();

var user = function(){
	var initialized = false,
		FBLikes = [],
		SDsongs = [],
		optionsSelected = {},
		selectedObject,
		checkSpotify = false;
	return {
		initSC : function(paging){
			var url = (paging) ? paging : '/me/followings';
			SC.get(url, function(r) {	
				if(r.length > 0){
					for(var i=0; i < r.length; i++){
						SDsongs.push(r[i].username)
					}
				}
				if(r.hasOwnProperty('next_href')){
					user.initSC(r.next_href);
				}
				selectedObject.unbind('click').bind('click',function(){var $this = $(this); user.setObject($this); user.toggle('SDsongs'); return false;});
				user.toggle('SDsongs');
			});
			
		},
		initFB : function(paging){
			
			//Start Loading Grapic
			
			var initialized = true;
			var url = (paging) ? paging : '/me/music';
			if(checkSpotify && !paging){
				url = '/me/music.listens';
			}
			FB.api(url,function(r){
				if(r.data.length > 0 && !checkSpotify){
					for(var i=0; i < r.data.length; i++){
						FBLikes.push(r.data[i].name);
					}
				}
				else if(checkSpotify && r.data.length > 0){
					for(var i=0; i < r.data.length; i++){
						//FBLikes.push(r.data[i].name);
						for(var a in r.data[i].data){
							switch(a){
								case 'playlist':
									FBLikes.push(r.data[i].data[a].title.split(' - ')[0]);
									break;
								case 'musician':
									FBLikes.push(r.data[i].data[a].title);
									break;
								
							}
						}
					}					
					var searchUnique = [];
			
					$.each(FBLikes, function(i, el){
						if($.inArray(el, searchUnique) === -1) searchUnique.push(el);
					});
					FBLikes = searchUnique;
				}			
				if(r.hasOwnProperty('paging') && typeof r.paging.next == 'string')
					user.initFB(r.paging.next);
				else if(!checkSpotify){
					checkSpotify = true;
					user.initFB();
					return false;
				}
				else{
					selectedObject.unbind('click').bind('click',function(){var $this = $(this); user.setObject($this); user.toggle('FBLikes'); return false;});
					user.toggle('FBLikes');
					
					//End Loading Graphic Here
				}
			});
		},
		setObject : function(obj){
			selectedObject = obj;
		},
		toggle : function(obj){
			
			var joinedArrays = []
			
			if(typeof optionsSelected[obj] == 'boolean'){
				delete optionsSelected[obj];
				selectedObject.find('.toggle-on').removeClass("displayNone");
				selectedObject.find('.toggle-off').addClass("displayNone");
			}
			else{
				optionsSelected[obj] = true;
				selectedObject.find('.toggle-on').addClass("displayNone");
				selectedObject.find('.toggle-off').removeClass("displayNone");
			}
				
			for(var list in optionsSelected){
				joinedArrays = joinedArrays.concat(eval(list));
			};
			
			sortList.checkResults(joinedArrays);
		}
	}
}();

var scrollWindow = function(){
	var searchBarOffset = '';
	var pos = 0;
	return{
		go:function(){
			if(typeof searchBarOffset == 'string')
				searchBarOffset = $('#searchForm').offset().top;
			
			pos = $(document).scrollTop();
			
			$('#mainContent').addClass('minHeight')
			
			var scrollani = setInterval(function(){
				if(pos > searchBarOffset)
					clearInterval(scrollani);
				else{
					pos = pos+10;
					$(document).scrollTop(pos);
				}
			},50);
		}
	}
}();

//Enable Objects
$(function(){
	/*$( "#bandSearch" ).autocomplete({
	  source: sxswObject.searchAutoComplete(),
	  response: function( event, ui ) { console.log(ui)}
	});*/
	$('#fbToggle').bind('click',function(e){
		var $this = $(this);
		FB.login(function(response) {
		   if (response.authResponse) {
			 debug.log('Welcome!  Fetching your information.... ');
			 user.setObject($this);
			 user.initFB();
		   } else {
			 debug.log('User cancelled login or did not fully authorize.');
			 setBtnToggle(e);
		   }
		},{scope:'user_likes,user_actions.music'});
		return false;
	});
	$('#scToggle').bind('click',function(e){
		var $this = $(this);
		user.setObject($this);
		SC.connect(function() {
		  user.initSC();
		});
		return false;
	});
	$('#rdioToggle').bind('click',function(e){
		setBtnToggle(e);
		$.ajax({
			url:'service/getInvatationURLs.php'
		})
	});
	$('#searchForm').bind('keyup',function(ev){
		if(this.value.length > 2)
			sxswObject.searchValue(this.value);
		else
			sortList.checkResults([]);
	}).bind('focus',function(){
		scrollWindow.go();
	});
});
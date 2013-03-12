var sxswObject = function(){
	var gigs,
		searchAutoComplete = [],
		gigHTML = $('<div id="gightml" />');
	return{
		init:function(){
			for(var n in sxswMusic)
				gigs = sxswMusic[n].response.gigs;
			
			for(var i=0; i < gigs.length; i++){
				searchAutoComplete.push(gigs[i].band_name);
				searchAutoComplete.push(gigs[i].venue_name);
				this.generateGigHTML(gigs[i]);
			
			}
			searchAutoComplete = searchAutoComplete.sort();
			var searchUnique = [];
			
			$.each(searchAutoComplete, function(i, el){
				if($.inArray(el, searchUnique) === -1) searchUnique.push(el);
			});
			searchAutoComplete = searchUnique;
			
		},
		generateGigHTML : function(gig){
			var pattern = /[-!$%^&*()_+|~=`{}\[\]:";'<>?,.\/]|@| /g,
				cleanBandName = gig.band_name.replace(pattern,''),
				cleanVenueName = gig.venue_name.replace(pattern,'');
				
				html = '<div class="full-width gig '+cleanBandName+' '+cleanVenueName+'"><div id="eventTime" class="event-time grid-3">'+gig.start_time+'</div><div class="grid-7"><div id="bandName" class="band-name full-width">'+gig.band_name+'</div><div id="venueName" class="venue-name full-width"><a>'+gig.venue_name+'</a></div></div><div class="add-to-cal grid-2"><a class="ico-calendar"></a></div><div class="gigInfo"><input type="hidden" name="date" value="'+gig.date+'" /></div></div>'
			
			//var html = '<div class="gig '+cleanBandName+' '+cleanVenueName+'"><div class="time">'+gig.start_time+'</div><div class="gigInfo"><span class="band">'+gig.band_name+'</span><span class="venue">'+gig.venue_name+'</span></div><div class="calendar"></div></div>';
			
			gigHTML.append(html);
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
				
			debug.log(window.opener);
			
				if(r.length > 0){
					for(var i=0; i < r.length; i++){
						SDsongs.push(r[i].username)
					}
				}
				if(r.hasOwnProperty('next_href')){
					user.initSC(r.next_href);
				}
				sortList.checkResults(SDsongs);
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
					selectedObject.unbind('click').bind('click',function(){user.toggle('FBLikes')});
					user.toggle('FBLikes');
					debug.log(FBLikes);
					sortList.checkResults(FBLikes);
					
					//End Loading Graphic Here
				}
			});
		},
		setObject : function(obj){
			selectedObject = obj;
		},
		toggle : function(obj){
			if(typeof optionsSelected[obj] == 'boolean')
				delete optionsSelected[obj]
			else
				optionsSelected[obj] = true;
				
			debug.log(optionsSelected);
		}
	}
}();



//Enable Objects
$(function(){
	/*$( "#bandSearch" ).autocomplete({
	  source: sxswObject.searchAutoComplete(),
	  response: function( event, ui ) { console.log(ui)}
	});*/
	$('.ico-font.facebook').bind('click',function(){
		var $this = $(this);
		FB.login(function(response) {
		   if (response.authResponse) {
			 debug.log('Welcome!  Fetching your information.... ');
			 user.setObject($this);
			 user.initFB();
		   } else {
			 debug.log('User cancelled login or did not fully authorize.');
		   }
		},{scope:'user_likes,user_actions.music'});
	});
	$('.ico-font.soundcloud').bind('click',function(){
		SC.connect(function() {
		  user.initSC();
		});
	});
	$('.ico-font.twitter').bind('click',function(){
		$.ajax({
			url:'service/getInvatationURLs.php'
		})
	});
	$('#search-form').bind('keyup',function(ev){
		if(this.value.length > 2)
			sxswObject.searchValue(this.value);
	});
});
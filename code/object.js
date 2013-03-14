var sxswObject = function(){
	var gigs,
		searchAutoComplete = [],
		gigHTML ='';
	return{
		init:function(){
			
			//searchAutoComplete = phpSearchArray;
			//gigHTML = phpGigHTML;
			
			debug.log('here')
			
			var loader = '.'
			
			var loading = setInterval(function(){
				
				$('#searchForm').attr('value','Loading Gigs'+loader);
				
				if(loader == '.')
					loader = '..'
				else if(loader == '..')
					loader = '...'
				else if (loader == '...')
					loader = '.'
				
			},400);
			
			
			$.ajax({
				url : '/',
				type : 'post',
				dataType:'json',
				data:{getGig:true},
				success:function(r){
					
					if(r.status == 'fail'){
						$('body').html('<div style="margin:0 auto; width:640px;"><img src="images/plugged_in_error.jpg" /></div>')
					}
					
					gigHTML = $(r.gigSet);
					searchAutoComplete = r.searchSet;
					
					clearInterval(loading);
					
					$('#fbToggle').on('click',function(e){
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
					$('#scToggle').on('click',function(e){
						var $this = $(this);
						user.setObject($this);
						SC.connect(function() {
						  user.initSC();
						});
						return false;
					});
					$('#rdioToggle').on('click',function(e){
						setBtnToggle(e);
						$.ajax({
							url:'service/getInvatationURLs.php'
						})
					});
					$('#searchForm').on('keyup',function(ev){
						if(this.value.length > 2)
							sxswObject.searchValue(this.value);
						else
							sortList.showGigs('searching');
					}).on('focus',function(){
						user.toggleOff();
						scrollWindow.go();
					}).attr('value','');
				}
			})
						
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
				var searchCon = searchSet.toUpperCase()
				
				
				
				if(string.search(searchCon) != -1){
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
			
			
			// REVISIT THIS. If the gigset is larger than 300 it break the system. I'll have to rebuild this.
			setLength = (gigSet.length > 300) ? 200 : gigSet.length;
			
			
			for(var i=0; i < setLength; i++){
				if($.inArray(gigSet[i], gigs) != -1){
					
					results.push('.'+gigSet[i].replace(/[-!$%^&*()_+|~=`{}\[\]:";'<>?,.\/]|@| /g,''));
				}
			}
				
			this.showGigs(results);
			
		},
		showGigs:function(gigSet){
			var gigHTML = sxswObject.gigHTML();
			
			$('#masterList div').remove();
			
			debug.log(gigSet);
			
			if(gigSet == 'searching'){
				$('#masterList').append('<div class="grid-container-pad0"><div class="cal-container gig NorthernFaces Barbarella"><div class="event-time grid-3" id="eventTime"></div><div class="grid-7"><div class="band-name full-width" id="bandName">Searching...</div><div class="venue-name full-width" id="venueName"><a>If nothing comes up, keep typing or try a different search term.</a></div></div></div></div>');
			} else if(gigSet.length == 0)
				$('#masterList').append('<div class="grid-container-pad0"><div class="cal-container gig NorthernFaces Barbarella"><div class="event-time grid-3" id="eventTime"></div><div class="grid-7"><div class="band-name full-width" id="bandName">No Results</div><div class="venue-name full-width" id="venueName"><a>Try the search bar.</a></div></div></div></div>');
			
			var currentHTML = gigHTML.find(gigSet.toString()).clone(),
				currentDate = '',
				days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
				today = new Date(),
				text_day = '';
			
			
			var gridContain = '';
			
			$.each(currentHTML,function(key, value){
				var $date = $(value).find('.date input').val();
				var jsdate = new Date($date);
				
				
				
				if(today.getDate() - jsdate.getDate() <= 0){
					if(currentDate != $date.split('T')[0]){
						currentDate = $date.split('T')[0];
						
						gridContain = $('<div class="grid-container-pad0" />');
						
						
						debug.log(today.getDate() + " <-- today ")
						debug.log(jsdate.getDate() + " <-- event day ")
						
						if(today.getDate() == jsdate.getDate())
							text_day = 'Today';
						else if(today.getDate() - jsdate.getDate() == -1)
							text_day = 'Tomorrow';
						else
							text_day = days[jsdate.getDay()];
						
						gridContain.append('<div id="eventDate" class="event-date full-width"><span>'+text_day+'</span></div>');
						
						$('#masterList').append(gridContain);
					}
											
					gridContain.append(value);
				}
			});
			
			scrollWindow.go();
			
			
			$('.add-to-cal').on('click',function(){
				user.setCalendar($(this).find('form[name=gigInfo]').serialize());
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
		checkSpotify = false,
		storeSRC = '',
		loadingButton = new Image();
		loadingButton.src = "images/buttons/social_btn.png";
	return {
		setCalendar : function(data){
			//debug.log(data)
			window.open('http://www.google.com/calendar/event?action=TEMPLATE&'+data);
		},
		initSC : function(paging){
			var url = (paging) ? paging : '/me/followings';
			
			if(!paging){
				selectedObject.append('<img class="loading" src="images/loading.gif" />');
				storeSRC = $('#scToggle img')[0].src;
				$('#scToggle img')[0].src = loadingButton.src;
			}
			
			SC.get(url, function(r) {	
				if(r.length > 0){
					for(var i=0; i < r.length; i++){
						SDsongs.push(r[i].username)
					}
				}
				if(r.hasOwnProperty('next_href')){
					user.initSC(r.next_href);
				}
				selectedObject.off('click').bind('click',function(){var $this = $(this); user.setObject($this); user.toggle('SDsongs'); return false;});
				user.toggle('SDsongs');
				$('.loading').remove();
				$('#scToggle img')[0].src = storeSRC;
			});
			
		},
		initFB : function(paging){
			
			if(!paging && !checkSpotify){
				storeSRC = $('#fbToggle img')[0].src;
				$('#fbToggle img')[0].src = loadingButton.src;
				selectedObject.append('<img class="loading" src="images/loading.gif" />');
			}
			
			
			
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
					selectedObject.off('click').bind('click',function(){var $this = $(this); user.setObject($this); user.toggle('FBLikes'); return false;});
					user.toggle('FBLikes');
					
					$('.loading').remove();
					$('#fbToggle img')[0].src = storeSRC;
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
		},
		toggleOff : function(){
			optionsSelected = {};
			$('.toggle-on').removeClass("displayNone");
			$('.toggle-off').addClass("displayNone");
		}
	}
}();

var scrollWindow = function(){
	var searchBarOffset = '';
	var pos = 0;
	return{
		go:function(){
			setTimeout(scrollWindow.exec,100)
		},
		exec : function(){
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
			},5);
		}
	}
}();

//Enable Objects
$(function(){
	/*$( "#bandSearch" ).autocomplete({
	  source: sxswObject.searchAutoComplete(),
	  response: function( event, ui ) { console.log(ui)}
	});*/
	
	
	
});
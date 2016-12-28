/*!
 * Documenter 1.6
 * http://rxa.li/documenter
 *
 * Copyright 2011, Xaver Birsak
 * http://revaxarts.com
 *
 */
//if Cufon replace headings
if(typeof Cufon == 'function') Cufon.replace('h1, h2, h3, h4, h5, h6');
 
$(document).ready(function() {
	var timeout,
		sections = new Array(),
		sectionscount = 0,
		win = $(window),
		sidebar = $('#documenter_sidebar'),
		nav = $('#documenter_nav'),
		logo = $('#documenter_logo'),
		navanchors = nav.find('a'),
		timeoffset = 50,
		hash = location.hash || null;
		iDeviceNotOS4 = (navigator.userAgent.match(/iphone|ipod|ipad/i) && !navigator.userAgent.match(/OS 5/i)) || false,
		badIE = $('html').prop('class').match(/ie(6|7|8)/)|| false;
		
	//handle external links (new window)
	$('a[href^=http]').bind('click',function(){
		window.open($(this).attr('href'));
		return false;
	});
	
	
	//We need the position of each section until the full page with all images is loaded
	win.bind('load',function(){
		
		var sectionselector = 'section';
		
		//Documentation has subcategories		
		if(nav.find('ol').length){
			sectionselector = 'section, h4';
		}
		//saving some information
		$(sectionselector).each(function(i,e){
			var _this = $(this);
			var p = {
				id: this.id,
				pos: _this.offset().top
			};
			sections.push(p);
		});
		
		
		//iPhone, iPod and iPad don't trigger the scroll event
		if(iDeviceNotOS4){
			nav.find('a').bind('click',function(){
				setTimeout(function(){
					win.trigger('scroll');				
				},duration);
				
			});
			//scroll to top
			window.scroll(0,0);
		}

		//how many sections
		sectionscount = sections.length;
		
		sections1 = $('section');
		items1 = $('#documenter_nav a.section');
		
		win.bind('scroll',function(event){
			
			for(var idx = 0; idx < sections1.length; idx++){
				section = sections1[idx];	
				var offset =  $(section).offset();
				if(win.scrollTop() < offset.top - 100){
					break;
				}
			}
			$(items1).removeClass('current');
			activesection = $(items1[(idx == 0 ? 0 : (idx - 1))]);
			activesection.addClass('current');
			
			$('#documenter_nav li').removeClass('current');
			activesection.parent().addClass('current');
			
			items = $('a:not(.section)',activesection.parent());
			sub_sections = $('h4',sections1[(idx == 0 ? 0 : (idx - 1))]);
			
			for(var idx = 0; idx < sub_sections.length; idx++){
				section = sub_sections[idx];	
				var offset =  $(section).offset();
				if(win.scrollTop() < offset.top - 100){
					break;
				}
			}
			$('#documenter_nav a:not(.section)').removeClass('current');
			$(items[(idx == 0 ? 0 : (idx - 1))]).addClass('current');
		})
		
		//bind the handler to the scroll event
		/*
		win.bind('scroll',function(event){
			clearInterval(timeout);
			//should occur with a delay
			timeout = setTimeout(function(){
				//get the position from the very top in all browsers
				pos = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
				
				//iDeviceNotOS4s don't know the fixed property so we fake it
				if(iDeviceNotOS4){
					sidebar.css({height:document.height});
					logo.css({'margin-top':pos});
				}
				//activate Nav element at the current position
				activateNav(pos);
			},timeoffset);
		}).trigger('scroll');
*/
	});
	
	//the function is called when the hash changes
	function hashchange(){
		goTo(location.hash, false);
	}
	
	//scroll to a section and set the hash
	function goTo(hash,changehash){
		win.unbind('hashchange', hashchange);
		hash = hash.replace(/!\//,'');
		win.stop().scrollTo(hash,duration,{
			easing:easing,
			axis:'y'			
		});
		if(changehash !== false){
			var l = location;
			location.href = (l.protocol+'//'+l.host+l.pathname+'#!/'+hash.substr(1));
		}
		win.bind('hashchange', hashchange);
	}
	
	
	//activate current nav element
	/*
	function activateNav(pos){
		var offset = 100,
		current, next, parent, isSub, hasSub;
		win.unbind('hashchange', hashchange);
		for(var i=sectionscount;i>0;i--){
			if(sections[i-1].pos <= pos+offset){
				navanchors.removeClass('current');
				current = navanchors.eq(i-1);
				current.addClass('current');
				
				parent = current.parent().parent();
				next = current.next();
				
				hasSub = next.is('ol');
				isSub = !parent.is('#documenter_nav');
				
				nav.find('ol:visible').not(parent).slideUp('fast');
				if(isSub){
					parent.prev().addClass('current');
					parent.stop().slideDown('fast');
				}else if(hasSub){
					next.stop().slideDown('fast');
				}
				win.bind('hashchange', hashchange);
				break;
			};
		}	
	}*/
	
	
});
/*
 * ======================================================================
 * Slider Pro Admin
 * ======================================================================
 */
(function( $ ) {

	var SliderProAdmin = {

		/**
		 * Stores the data for all slides in the slider.
		 *
		 * @since 1.0.0
		 * 
		 * @type {Array}
		 */
		slides: [],

		/**
		 * Keeps a count for the slides in the slider.
		 *
		 * @since 1.0.0
		 * 
		 * @type {Int}
		 */
		slideCounter: 0,

		/**
		 * Stores all posts names and their taxonomies.
		 *
		 * @since 1.0.0
		 * 
		 * @type {Object}
		 */
		postsData: {},

		/**
		 * Indicates if the preview images from the slides
		 * can be resized.
		 * This prevents resizing the images too often.
		 *
		 * @since 1.0.0
		 * 
		 * @type {Boolean}
		 */
		allowSlideImageResize: true,

		/**
		 * Initializes the functionality for a single slider page
		 * or for the page that contains all the sliders.
		 *
		 * @since 1.0.0
		 */
		init: function() {
			if ( sp_js_vars.page === 'single' ) {
				this.initSingleSliderPage();
			} else if ( sp_js_vars.page === 'all' ) {
				this.initAllSlidersPage();
			}
		},

		/*
		 * ======================================================================
		 * Slider functions
		 * ======================================================================
		 */
		
		/**
		 * Initializes the functionality for a single slider page
		 * by adding all the necessary event listeners.
		 *
		 * @since 1.0.0
		 */
		initSingleSliderPage: function() {
			var that = this;

			this.initSlides();

			if ( parseInt( sp_js_vars.id, 10 ) !== -1 ) {
				this.loadSliderData();
			}

			$( 'form' ).on( 'submit', function( event ) {
				event.preventDefault();
				that.saveSlider();
			});

			$( '.preview-slider' ).on( 'click', function( event ) {
				event.preventDefault();
				that.previewSlider();
			});

			$( '.add-slide' ).on( 'click', function( event ) {
				event.preventDefault();
				that.addImageSlides();
			});

			$( '.postbox .hndle, .postbox .handlediv' ).on( 'click', function() {
				var postbox = $( this ).parent( '.postbox' );
				
				if ( postbox.hasClass( 'closed' ) === true ) {
					postbox.removeClass( 'closed' );
				} else {
					postbox.addClass( 'closed' );
				}
			});

			$( '.sidebar-settings' ).on( 'mouseover', 'label', function() {
				that.showInfo( $( this ) );
			});

			$( window ).resize(function() {
				if ( that.allowSlideImageResize === true ) {
					that.resizeSlideImages();
					that.allowSlideImageResize = false;

					setTimeout( function() {
						that.resizeSlideImages();
						that.allowSlideImageResize = true;
					}, 250 );
				}
			});
		},

		/**
		 * Initializes the functionality for the page that contains
		 * all the sliders by adding all the necessary event listeners.
		 *
		 * @since 1.0.0
		 */
		initAllSlidersPage: function() {
			var that = this;

			$( '.sliders-list' ).on( 'click', '.preview-slider', function( event ) {
				event.preventDefault();
				that.previewSliderAll( $( this ) );
			});

			$( '.sliders-list' ).on( 'click', '.delete-slider', function( event ) {
				event.preventDefault();
				that.deleteSlider( $( this ) );
			});

			$( '.sliders-list' ).on( 'click', '.duplicate-slider', function( event ) {
				event.preventDefault();
				that.duplicateSlider( $( this ) );
			});

			$( '.clear-all-cache' ).on( 'click', function( event ) {
				event.preventDefault();

				$( '.clear-cache-spinner' ).css( { 'display': 'inline-block', 'visibility': 'visible' } );

				var nonce = $( this ).attr( 'data-nonce' );

				$.ajax({
					url: sp_js_vars.ajaxurl,
					type: 'post',
					data: { action: 'sliderpro_lite_clear_all_cache', nonce: nonce },
					complete: function( data ) {
						$( '.clear-cache-spinner' ).css( { 'display': '', 'visibility': '' } );
					}
				});
			});
		},

		/**
		 * Load the slider slider data.
		 * 
		 * Send an AJAX request with the slider id and the nonce, and
		 * retrieve all the slider's database data. Then, assign the
		 * data to the slides.
		 *
		 * @since 1.0.0
		 */
		loadSliderData: function() {
			var that = this;

			$( '.slide-spinner' ).css( { 'display': 'inline-block', 'visibility': 'visible' } );

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'get',
				data: { action: 'sliderpro_lite_get_slider_data', id: sp_js_vars.id, nonce: sp_js_vars.lad_nonce },
				complete: function( data ) {
					var sliderData = $.parseJSON( data.responseText );

					$.each( sliderData.slides, function( index, slide ) {
						var slideData = {
							mainImage: {},
							thumbnail: {},
							caption: slide.caption,
							layers: slide.layers,
							html: slide.html,
							settings: $.isArray( slide.settings ) ? {} : slide.settings
						};

						$.each( slide, function( settingName, settingValue ) {
							if ( settingName.indexOf( 'main_image' ) !== -1 ) {
								slideData.mainImage[ settingName ] = settingValue;
							} else if ( settingName.indexOf( 'thumbnail' ) !== -1 ) {
								slideData.thumbnail[ settingName ] = settingValue;
							}
						});

						that.getSlide( index ).setData( 'all', slideData );
					});

					$( '.slide-spinner' ).css( { 'display': '', 'visibility': '' } );
				}
			});
		},

		/**
		 * Save the slider's data.
		 * 
		 * Get the slider's data and send it to the server with AJAX. If
		 * a new slider was created, redirect to the slider's edit page.
		 *
		 * @since 1.0.0
		 */
		saveSlider: function() {
			var sliderData = this.getSliderData();
			sliderData[ 'nonce' ] = sp_js_vars.sa_nonce;
			sliderData[ 'action' ] = 'save';

			var sliderDataString = JSON.stringify( sliderData );

			var spinner = $( '.update-spinner' ).css( { 'display': 'inline-block', 'visibility': 'visible' } );

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'sliderpro_lite_save_slider', data: sliderDataString },
				complete: function( data ) {
					spinner.css( { 'display': '', 'visibility': '' } );

					if ( parseInt( sp_js_vars.id, 10 ) === -1 && isNaN( data.responseText ) === false ) {
						$( 'h2' ).after( '<div class="updated"><p>' + sp_js_vars.slider_create + '</p></div>' );

						window.location = sp_js_vars.admin + '?page=sliderpro-lite&id=' + data.responseText + '&action=edit';
					} else if ( $( '.updated' ).length === 0 ) {
						$( 'h2' ).after( '<div class="updated"><p>' + sp_js_vars.slider_update + '</p></div>' );
					}
				}
			});
		},

		/**
		 * Get the slider's data.
		 * 
		 * Read the value of the sidebar settings, including the breakpoints,
		 * the slides state, the name of the slider, the id, and get the
		 * data for each slide.
		 *
		 * @since 1.0.0
		 * 
		 * @return {Object} The slider data.
		 */
		getSliderData: function() {
			var that = this,
				sliderData = {
					'id': sp_js_vars.id,
					'name': $( 'input#title' ).val(),
					'settings': {},
					'slides': [],
					'panels_state': {}
				},
				breakpoints = [];

			$( '.slides-container' ).find( '.slide' ).each(function( index ) {
				var $slide = $( this ),
					slideData = that.getSlide( parseInt( $slide.attr('data-id'), 10 ) ).getData( 'all' );
				
				slideData.position = parseInt( $slide.attr( 'data-position' ), 10 );

				sliderData.slides[ index ] = slideData;
			});

			$( '.sidebar-settings' ).find( '.setting' ).each(function() {
				var setting = $( this );
				sliderData.settings[ setting.attr( 'name' ) ] = setting.attr( 'type' ) === 'checkbox' ? setting.is( ':checked' ) : setting.val();
			});

			$( '.sidebar-settings' ).find( '.postbox' ).each(function() {
				var slide = $( this );
				sliderData.panels_state[ slide.attr( 'data-name' ) ] = slide.hasClass( 'closed' ) ? 'closed' : '';
			});

			return sliderData;
		},

		/**
		 * Preview the slider in the slider's edit page.
		 *
		 * @since 1.0.0
		 */
		previewSlider: function() {
			PreviewWindow.open( this.getSliderData() );
		},

		/**
		 * Preview the slider in the sliders' list page.
		 *
		 * @since 1.0.0
		 */
		previewSliderAll: function( target ) {
			var url = $.lightURLParse( target.attr( 'href' ) ),
				nonce = url.lad_nonce,
				id = parseInt( url.id, 10 );

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'get',
				data: { action: 'sliderpro_lite_get_slider_data', id: id, nonce: nonce },
				complete: function( data ) {
					var sliderData = $.parseJSON( data.responseText );

					PreviewWindow.open( sliderData );
				}
			});
		},

		/**
		 * Delete a slider.
		 *
		 * This is called in the sliders' list page upon clicking
		 * the 'Delete' link.
		 *
		 * It displays a confirmation dialog before sending the request
		 * for deletion to the server.
		 *
		 * The slider's row is removed after the slider is deleted
		 * server-side.
		 * 
		 * @since 1.0.0
		 *
		 * @param  {jQuery Object} target The clicked 'Delete' link.
		 */
		deleteSlider: function( target ) {
			var url = $.lightURLParse( target.attr( 'href' ) ),
				nonce = url.da_nonce,
				id = parseInt( url.id, 10 ),
				row = target.parents( 'tr' );

			var dialog = $(
				'<div class="modal-overlay"></div>' +
				'<div class="modal-window-container">' +
				'	<div class="modal-window delete-slider-dialog">' +
				'		<p class="dialog-question">' + sp_js_vars.slider_delete + '</p>' +
				'		<div class="dialog-buttons">' +
				'			<a class="button dialog-ok" href="#">' + sp_js_vars.yes + '</a>' +
				'			<a class="button dialog-cancel" href="#">' + sp_js_vars.cancel + '</a>' +
				'		</div>' +
				'	</div>' +
				'</div>'
			).appendTo( 'body' );

			$( '.modal-window-container' ).css( 'top', $( window ).scrollTop() );

			dialog.find( '.dialog-ok' ).one( 'click', function( event ) {
				event.preventDefault();

				$.ajax({
					url: sp_js_vars.ajaxurl,
					type: 'post',
					data: { action: 'sliderpro_lite_delete_slider', id: id, nonce: nonce },
					complete: function( data ) {
						if ( id === parseInt( data.responseText, 10 ) ) {
							row.fadeOut( 300, function() {
								row.remove();
							});
						}
					}
				});

				dialog.remove();
			});

			dialog.find( '.dialog-cancel' ).one( 'click', function( event ) {
				event.preventDefault();
				dialog.remove();
			});

			dialog.find( '.modal-overlay' ).one( 'click', function( event ) {
				dialog.remove();
			});
		},

		/**
		 * Duplicate a slider.
		 *
		 * This is called in the sliders' list page upon clicking
		 * the 'Duplicate' link.
		 *
		 * A new row is added in the list for the newly created
		 * slider.
		 * 
		 * @since 1.0.0
		 *
		 * @param  {jQuery Object} target The clicked 'Duplicate' link.
		 */
		duplicateSlider: function( target ) {
			var url = $.lightURLParse( target.attr( 'href' ) ),
				nonce = url.dua_nonce,
				id = parseInt( url.id, 10 );

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'sliderpro_lite_duplicate_slider', id: id, nonce: nonce },
				complete: function( data ) {
					var row = $( data.responseText ).appendTo( $( '.sliders-list tbody' ) );
					
					row.hide().fadeIn();
				}
			});
		},

		/*
		 * ======================================================================
		 * Slide functions executed by the slider
		 * ======================================================================
		 */
		
		/**
		 * Initialize all the existing slides when the page loads.
		 * 
		 * @since 1.0.0
		 */
		initSlides: function() {
			var that = this;

			$( '.slides-container' ).find( '.slide' ).each(function( index ) {
				that.initSlide( $( this ) );
			});

			$( '.slides-container' ).lightSortable( {
				children: '.slide',
				placeholder: 'slide slide-placeholder',
				sortEnd: function( event ) {
					$( '.slide' ).each(function( index ) {
						$( this ).attr( 'data-position', index );
					});
				}
			} );
		},

		/**
		 * Initialize an individual slide.
		 *
		 * Creates a new instance of the Slide object and adds it 
		 * to the array of slides.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {jQuery Object} element The slide element.
		 * @param  {Object}        data    The slide's data.
		 */
		initSlide: function( element, data ) {
			var that = this,
				$slide = element,
				slide = new Slide( $slide, this.slideCounter, data );

			this.slides.push( slide );

			slide.on( 'duplicateSlide', function( event ) {
				that.duplicateSlide( event.slideData );
			});

			slide.on( 'deleteSlide', function( event ) {
				that.deleteSlide( event.id );
			});

			$slide.attr( 'data-id', this.slideCounter );
			$slide.attr( 'data-position', this.slideCounter );

			this.slideCounter++;
		},

		/**
		 * Return the slide data.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {Int}    id The id of the slide to retrieve.
		 * @return {Object}    The data of the retrieved slide.
		 */
		getSlide: function( id ) {
			var that = this,
				selectedSlide;

			$.each( that.slides, function( index, slide ) {
				if ( slide.id === id ) {
					selectedSlide = slide;
					return false;
				}
			});

			return selectedSlide;
		},

		/**
		 * Duplicate an individual slide.
		 *
		 * The main image is sent to the server for the purpose
		 * of adding it to the slide preview, while the rest of the data
		 * is passed with JS.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {Object} slideData The data of the object to be duplicated.
		 */
		duplicateSlide: function( slideData ) {
			var that = this,
				newSlideData = $.extend( true, {}, slideData ),
				data = [{
					settings: {
						content_type: newSlideData.settings.content_type
					},
					main_image_source: newSlideData.mainImage.main_image_source
				}];

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'sliderpro_lite_add_slides', data: JSON.stringify( data ) },
				complete: function( data ) {
					var slide = $( data.responseText ).appendTo( $( '.slides-container' ) );

					that.initSlide( slide, newSlideData );
				}
			});
		},

		/**
		 * Delete an individual slide.
		 *
		 * The main image is sent to the server for the purpose
		 * of adding it to the slide preview, while the rest of the data
		 * is passed with JS.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {Int} id The id of the slide to be deleted.
		 */
		deleteSlide: function( id ) {
			var that = this,
				slide = that.getSlide( id ),
				dialog = $(
					'<div class="modal-overlay"></div>' +
					'<div class="modal-window-container">' +
					'	<div class="modal-window delete-slide-dialog">' +
					'		<p class="dialog-question">' + sp_js_vars.slide_delete + '</p>' +
					'		<div class="dialog-buttons">' +
					'			<a class="button dialog-ok" href="#">' + sp_js_vars.yes + '</a>' +
					'			<a class="button dialog-cancel" href="#">' + sp_js_vars.cancel + '</a>' +
					'		</div>' +
					'	</div>' +
					'</div>').appendTo( 'body' );

			$( '.modal-window-container' ).css( 'top', $( window ).scrollTop() );

			dialog.find( '.dialog-ok' ).one( 'click', function( event ) {
				event.preventDefault();

				slide.off( 'duplicateSlide' );
				slide.off( 'deleteSlide' );
				slide.remove();
				dialog.remove();

				that.slides.splice( $.inArray( slide, that.slides ), 1 );
			});

			dialog.find( '.dialog-cancel' ).one( 'click', function( event ) {
				event.preventDefault();
				dialog.remove();
			});

			dialog.find( '.modal-overlay' ).one( 'click', function( event ) {
				dialog.remove();
			});
		},

		/**
		 * Add image slide(s).
		 *
		 * Add one or multiple slides pre-populated with image data.
		 *
		 * @since 1.0.0
		 */
		addImageSlides: function() {
			var that = this;
			
			MediaLoader.open(function( selection ) {
				var images = [];

				$.each( selection, function( index, image ) {
					images.push({
						main_image_id: image.id,
						main_image_source: image.url,
						main_image_alt: image.alt,
						main_image_title: image.title,
						main_image_width: image.width,
						main_image_height: image.height,
						caption: image.caption
					});
				});

				$.ajax({
					url: sp_js_vars.ajaxurl,
					type: 'post',
					data: { action: 'sliderpro_lite_add_slides', data: JSON.stringify( images ) },
					complete: function( data ) {
						var lastIndex = $( '.slides-container' ).find( '.slide' ).length - 1,
							slides = $( '.slides-container' ).append( data.responseText ),
							indexes = lastIndex === -1 ? '' : ':gt(' + lastIndex + ')';

						slides.find( '.slide' + indexes ).each(function( index ) {
							var slide = $( this );

							that.initSlide( slide, { mainImage: images[ index ], thumbnail: {}, caption: images[ index ][ 'caption' ], layers: {}, html: '', settings: {} } );
						});
					}
				});
			});
		},

		/*
		 * ======================================================================
		 * More slider functions
		 * ======================================================================
		 */
		
		/**
		 * Display the informative tooltip.
		 * 
		 * @since 1.0.0
		 * 
		 * @param  {jQuery Object} target The setting label which is hovered.
		 */
		showInfo: function( target ) {
			var label = target,
				info = label.attr( 'data-info' ),
				infoTooltip = null;

			if ( typeof info !== 'undefined' ) {
				infoTooltip = $( '<div class="info-tooltip">' + info + '</div>' ).appendTo( label.parent() );
				infoTooltip.css( { 'left': - infoTooltip.outerWidth( true ) ,'marginTop': - infoTooltip.outerHeight( true ) * 0.5 - 9 } );
			}

			label.on( 'mouseout', function() {
				if ( infoTooltip !== null ) {
					infoTooltip.remove();
				}
			});
		},

		/**
		 * Iterate through all slides and resizes the preview
		 * images based on their aspect ratio and the slide's
		 * current aspect ratio.
		 *
		 * @since 1.0.0
		 */
		resizeSlideImages: function() {
			var slideRatio = $( '.slide-preview' ).width() / $( '.slide-preview' ).height();

			$( '.slide-preview > img' ).each(function() {
				var image = $( this );

				if ( image.width() / image.height() > slideRatio ) {
					image.css( { width: 'auto', height: '100%' } );
				} else {
					image.css( { width: '100%', height: 'auto' } );
				}
			});
		}
	};

	/*
	 * ======================================================================
	 * Slide functions
	 * ======================================================================
	 */
	
	/**
	 * Slide object.
	 *
	 * @since 1.0.0
	 * 
	 * @param {jQuery Object} element The jQuery element.
	 * @param {Int}           id      The id of the slide.
	 * @param {Object}        data    The data of the slide.
	 */
	var Slide = function( element, id, data ) {
		this.$slide = element;
		this.id = id;
		this.data = data;
		this.events = $( {} );

		if ( typeof this.data === 'undefined' ) {
			this.data = { mainImage: {}, thumbnail: {}, caption: '', layers: {}, html: '', settings: {} };
		}

		this.init();
	};

	Slide.prototype = {

		/**
		 * Initialize the slide.
		 * 
		 * Add the necessary event listeners.
		 *
		 * @since 1.0.0
		 */
		init: function() {
			var that = this;

			this.$slide.find( '.slide-preview' ).on( 'click', function( event ) {
				MediaLoader.open(function( selection ) {
					var image = selection[ 0 ];

					that.setData( 'mainImage', { main_image_id: image.id, main_image_source: image.url, main_image_alt: image.alt, main_image_title: image.title, main_image_width: image.width, main_image_height: image.height } );
					that.setData( 'caption', image.caption );
					that.updateSlidePreview();
				});
			});

			this.$slide.find( '.delete-slide' ).on( 'click', function( event ) {
				event.preventDefault();
				that.trigger( { type: 'deleteSlide', id: that.id } );
			});

			this.$slide.find( '.duplicate-slide' ).on( 'click', function( event ) {
				event.preventDefault();
				that.trigger( { type: 'duplicateSlide', slideData: that.data } );
			});

			this.resizeImage();
		},

		/**
		 * Return the slide's data.
		 *
		 * It can return the main image data, or the layers
		 * data, or the HTML data, or the settings data, or
		 * all the data.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {String} target The type of data to return.
		 * @return {Object}        The requested data.
		 */
		getData: function( target ) {
			if ( target === 'all' ) {
				var allData = {};

				$.each( this.data.mainImage, function( settingName, settingValue ) {
					allData[ settingName ] = settingValue;
				});

				$.each( this.data.thumbnail, function( settingName, settingValue ) {
					allData[ settingName ] = settingValue;
				});

				allData[ 'caption' ] = this.data.caption;
				allData[ 'layers' ] = this.data.layers;
				allData[ 'html' ] = this.data.html;
				allData[ 'settings' ] = this.data.settings;

				return allData;
			} else if ( target === 'mainImage' ) {
				return this.data.mainImage;
			} else if ( target === 'caption' ) {
				return this.data.caption;
			}
		},

		/**
		 * Set the slide's data.
		 *
		 * It can set a specific data type, like the main image, 
		 * layers, html, settings, or it can set all the data.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {String} target The type of data to set.
		 * @param  {Object} data   The data to attribute to the slide.
		 */
		setData: function( target, data ) {
			var that = this;

			if ( target === 'all' ) {
				this.data = data;
			} else if ( target === 'mainImage' ) {
				$.each( data, function( name, value ) {
					that.data.mainImage[ name ] = value;
				});
			} else if ( target === 'thumbnail' ) {
				$.each( data, function( name, value ) {
					that.data.thumbnail[ name ] = value;
				});
			} else if ( target === 'caption' ) {
				this.data.caption = data;
			}
		},

		/**
		 * Remove the slide.
		 * 
		 * @since 1.0.0
		 */
		remove: function() {
			this.$slide.find( '.slide-preview' ).off( 'click' );
			this.$slide.find( '.delete-slide' ).off( 'click' );
			this.$slide.find( '.duplicate-slide' ).off( 'click' );

			this.$slide.fadeOut( 500, function() {
				$( this ).remove();
			});
		},

		/**
		 * Update the slide's preview.
		 *
		 * If the content type is custom, the preview will consist
		 * of an image. If the content is dynamic, a text will be 
		 * displayed that indicates the type of content (i.e., posts).
		 *
		 * This is called when the main image is changed or
		 * when the content type is changed.
		 * 
		 * @since 1.0.0
		 */
		updateSlidePreview: function() {
			var slidePreview = this.$slide.find( '.slide-preview' ),
				contentType = this.data.settings[ 'content_type' ];

			slidePreview.empty();

			if ( typeof contentType === 'undefined' || contentType === 'custom' ) {
				var mainImageSource = this.data.mainImage[ 'main_image_source' ];

				if ( typeof mainImageSource !== 'undefined' && mainImageSource !== '' ) {
					$( '<img src="' + mainImageSource + '" />' ).appendTo( slidePreview );
					this.resizeImage();
				} else {
					$( '<p class="no-image">' + sp_js_vars.no_image + '</p>' ).appendTo( slidePreview );
				}

				this.$slide.removeClass( 'dynamic-slide' );
			}
		},

		/**
		 * Resize the preview image, after it has loaded.
		 *
		 * @since 1.0.0
		 */
		resizeImage: function() {
			var slidePreview = this.$slide.find( '.slide-preview' ),
				slideImage = this.$slide.find( '.slide-preview > img' );

			if ( slideImage.length ) {
				var checkImage = setInterval(function() {
					if ( slideImage[0].complete === true ) {
						clearInterval( checkImage );

						if ( slideImage.width() / slideImage.height() > slidePreview.width() / slidePreview.height() ) {
							slideImage.css( { width: 'auto', height: '100%' } );
						} else {
							slideImage.css( { width: '100%', height: 'auto' } );
						}
					}
				}, 100 );
			}
		},

		/**
		 * Add an event listener to the slide.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {String}   type    The event name.
		 * @param  {Function} handler The callback function.
		 */
		on: function( type, handler ) {
			this.events.on( type, handler );
		},

		/**
		 * Remove an event listener from the slide.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {String} type The event name.
		 */
		off: function( type ) {
			this.events.off( type );
		},

		/**
		 * Triggers an event.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {String} type The event name.
		 */
		trigger: function( type ) {
			this.events.triggerHandler( type );
		}
	};

	/*
	 * ======================================================================
	 * Media loader
	 * ======================================================================
	 */

	var MediaLoader = {

		/**
		 * Open the WordPress media loader and pass the
		 * information of the selected images to the 
		 * callback function.
		 *
		 * The passed that is the image's url, alt, title,
		 * width and height.
		 * 
		 * @since 1.0.0
		 */
		open: function( callback ) {
			var selection = [],
				insertReference = wp.media.editor.insert;
			
			wp.media.editor.send.attachment = function( props, attachment ) {
				var image = typeof attachment.sizes[ props.size ] !== 'undefined' ? attachment.sizes[ props.size ] : attachment.sizes[ 'full' ],
					id = attachment.id,
					url = image.url,
					width = image.width,
					height = image.height,
					alt = attachment.alt,
					title = attachment.title,
					caption = attachment.caption;

				selection.push({ id: id, url: url, alt: alt, title: title, caption: caption, width: width, height: height });
			};

			wp.media.editor.insert = function( prop ) {
				callback.call( this, selection );

				wp.media.editor.insert = insertReference;
			};

			wp.media.editor.open( 'media-loader' );
		}
	};

	/*
	 * ======================================================================
	 * Preview window
	 * ======================================================================
	 */
	
	var PreviewWindow = {

		/**
		 * Reference to the modal window.
		 *
		 * @since 1.0.0
		 * 
		 * @type {jQuery Object}
		 */
		previewWindow: null,

		/**
		 * Reference to the slider instance.
		 *
		 * @since 1.0.0
		 * 
		 * @type {jQuery Object}
		 */
		slider: null,

		/**
		 * The slider's data.
		 *
		 * @since 1.0.0
		 * 
		 * @type {Object}
		 */
		sliderData: null,

		/**
		 * Open the preview window and pass the slider's data,
		 * which consists of slider settings and each slide's
		 * settings and content.
		 *
		 * Send an AJAX request with the data and receive the 
		 * slider's HTML markup and inline JavaScript.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {Object} data The data of the slider
		 */
		open: function( data ) {
			var that = this,
				spinner = $( '.preview-spinner' ).css( { 'display': 'inline-block', 'visibility': 'visible' } );

			$( 'body' ).append( '<div class="modal-overlay"></div>' +
				'<div class="modal-window-container preview-window">' +
				'	<div class="modal-window">' +
				'		<span class="close-x"></span>' +
				'	</div>' +
				'</div>');

			this.sliderData = data;

			this.init();

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'sliderpro_lite_preview_slider', data: JSON.stringify( data ) },
				complete: function( data ) {
					that.previewWindow.append( data.responseText );
					that.slider = that.previewWindow.find( '.slider-pro' );
					that.previewWindow.css( 'visibility', '' );
					spinner.css( { 'display': '', 'visibility': '' } );
				}
			});
		},

		/**
		 * Initialize the preview.
		 *
		 * Detect when the window is resized and resize the preview
		 * window accordingly, and also based on the slider's set
		 * width.
		 *
		 * @since 1.0.0
		 */
		init: function() {
			var that = this;

			$( '.modal-window-container' ).css( 'top', $( window ).scrollTop() );

			this.previewWindow = $( '.preview-window .modal-window' );

			this.previewWindow.find( '.close-x' ).on( 'click', function( event ) {
				that.close();
			});

			this.previewWindow.css( 'visibility', 'hidden' );

			var previewWidth = this.sliderData[ 'settings' ][ 'width' ],
				previewHeight = this.sliderData[ 'settings' ][ 'height' ],
				visibleSize = this.sliderData[ 'settings' ][ 'visible_size' ],
				forceSize = this.sliderData[ 'settings' ][ 'force_size' ],
				orientation = this.sliderData[ 'settings' ][ 'orientation' ],
				isThumbnailScroller = this.sliderData[ 'settings' ][ 'auto_thumbnail_images' ],
				thumbnailScrollerOrientation = this.sliderData[ 'settings' ][ 'thumbnails_position' ] === 'top' || this.sliderData[ 'settings' ][ 'thumbnails_position' ] === 'bottom' ? 'horizontal' : 'vertical';

			$.each( this.sliderData.slides, function( index, element ) {
				if ( ( typeof element.thumbnail_source !== 'undefined' && element.thumbnail_source !== '' ) || ( typeof element.thumbnail_content !== 'undefined' && element.thumbnail_content !== '' ) ) {
					isThumbnailScroller = true;
				}
			});

			if ( visibleSize !== 'auto' ) {
				if ( orientation === 'horizontal' ) {
					previewWidth = visibleSize;
				} else if ( orientation === 'vertical' ) {
					previewHeight = visibleSize;
				}
			}

			if ( forceSize === 'fullWidth' ) {
				previewWidth = '100%';
			} else if ( forceSize === 'fullWindow' ) {
				previewWidth = '100%';
				previewHeight = '100%';
			}

			var isPercentageWidth = previewWidth.indexOf( '%' ) !== -1,
				isPercentageHeight = previewHeight.indexOf( '%' ) !== -1;

			if ( isPercentageWidth === false && isThumbnailScroller === true && thumbnailScrollerOrientation === 'vertical' ) {
				previewWidth = parseInt( previewWidth, 10 ) + parseInt( this.sliderData[ 'settings' ][ 'thumbnail_width' ], 10 );
			}

			$( window ).on( 'resize.sliderPro', function() {
				if ( isPercentageWidth === true ) {
					that.previewWindow.css( 'width', $( window ).width() * ( parseInt( previewWidth, 10 ) / 100 ) - 100 );
				} else if ( previewWidth >= $( window ).width() - 100 ) {
					that.previewWindow.css( 'width', $( window ).width() - 100 );
				} else {
					that.previewWindow.css( 'width', previewWidth );
				}

				if ( isPercentageHeight === true ) {
					that.previewWindow.css( 'height', $( window ).height() * ( parseInt( previewHeight, 10 ) / 100 ) );
				}
			});

			$( window ).trigger( 'resize' );
		},

		/**
		 * Close the preview window.
		 *
		 * Remove event listeners and elements.
		 *
		 * @since 1.0.0
		 */
		close: function() {
			this.previewWindow.find( '.close-x' ).off( 'click' );
			$( window ).off( 'resize.sliderPro' );

			this.slider.sliderPro( 'destroy' );
			$( 'body' ).find( '.modal-overlay, .modal-window-container' ).remove();
		}
	};

	$( document ).ready(function() {
		SliderProAdmin.init();
	});

})( jQuery );

/*
 * ======================================================================
 * LightSortable
 * ======================================================================
 */

;(function( $ ) {

	var LightSortable = function( instance, options ) {

		this.options = options;
		this.$container = $( instance );
		this.$selectedChild = null;
		this.$placeholder = null;

		this.currentMouseX = 0;
		this.currentMouseY = 0;
		this.slideInitialX = 0;
		this.slideInitialY = 0;
		this.initialMouseX = 0;
		this.initialMouseY = 0;
		this.isDragging = false;
		
		this.checkHover = 0;

		this.uid = new Date().valueOf();

		this.events = $( {} );
		this.startPosition = 0;
		this.endPosition = 0;

		this.init();
	};

	LightSortable.prototype = {

		init: function() {
			this.settings = $.extend( {}, this.defaults, this.options );

			this.$container.on( 'mousedown.lightSortable' + this.uid, $.proxy( this._onDragStart, this ) );
			$( document ).on( 'mousemove.lightSortable.' + this.uid, $.proxy( this._onDragging, this ) );
			$( document ).on( 'mouseup.lightSortable.' + this.uid, $.proxy( this._onDragEnd, this ) );
		},

		_onDragStart: function( event ) {
			if ( event.which !== 1 || $( event.target ).is( 'select' ) || $( event.target ).is( 'input' ) || $( event.target ).is( 'a' ) ) {
				return;
			}

			this.$selectedChild = $( event.target ).is( this.settings.children ) ? $( event.target ) : $( event.target ).parents( this.settings.children );

			if ( this.$selectedChild.length === 1 ) {
				this.initialMouseX = event.pageX;
				this.initialMouseY = event.pageY;
				this.slideInitialX = this.$selectedChild.position().left;
				this.slideInitialY = this.$selectedChild.position().top;

				this.startPosition = this.$selectedChild.index();

				event.preventDefault();
			}
		},

		_onDragging: function( event ) {
			if ( this.$selectedChild === null || this.$selectedChild.length === 0 )
				return;

			event.preventDefault();

			this.currentMouseX = event.pageX;
			this.currentMouseY = event.pageY;

			if ( ! this.isDragging ) {
				this.isDragging = true;

				this.trigger( { type: 'sortStart' } );
				if ( $.isFunction( this.settings.sortStart ) ) {
					this.settings.sortStart.call( this, { type: 'sortStart' } );
				}

				var tag = this.$container.is( 'ul' ) || this.$container.is( 'ol' ) ? 'li' : 'div';

				this.$placeholder = $( '<' + tag + '>' ).addClass( 'ls-ignore ' + this.settings.placeholder )
					.insertAfter( this.$selectedChild );

				if ( this.$placeholder.width() === 0 ) {
					this.$placeholder.css( 'width', this.$selectedChild.outerWidth() );
				}

				if ( this.$placeholder.height() === 0 ) {
					this.$placeholder.css( 'height', this.$selectedChild.outerHeight() );
				}

				this.$selectedChild.css( {
						'pointer-events': 'none',
						'position': 'absolute',
						left: this.$selectedChild.position().left,
						top: this.$selectedChild.position().top,
						width: this.$selectedChild.width(),
						height: this.$selectedChild.height()
					} )
					.addClass( 'ls-ignore' );

				this.$container.append( this.$selectedChild );

				$( 'body' ).css( 'user-select', 'none' );

				var that = this;

				this.checkHover = setInterval( function() {

					that.$container.find( that.settings.children ).not( '.ls-ignore' ).each( function() {
						var $currentChild = $( this );

						if ( that.currentMouseX > $currentChild.offset().left &&
							that.currentMouseX < $currentChild.offset().left + $currentChild.width() &&
							that.currentMouseY > $currentChild.offset().top &&
							that.currentMouseY < $currentChild.offset().top + $currentChild.height() ) {

							if ( $currentChild.index() >= that.$placeholder.index() )
								that.$placeholder.insertAfter( $currentChild );
							else
								that.$placeholder.insertBefore( $currentChild );
						}
					});
				}, 200 );
			}

			this.$selectedChild.css( { 'left': this.currentMouseX - this.initialMouseX + this.slideInitialX, 'top': this.currentMouseY - this.initialMouseY + this.slideInitialY } );
		},

		_onDragEnd: function() {
			if ( this.isDragging ) {
				this.isDragging = false;

				$( 'body' ).css( 'user-select', '');

				this.$selectedChild.css( { 'position': '', left: '', top: '', width: '', height: '', 'pointer-events': '' } )
									.removeClass( 'ls-ignore' )
									.insertAfter( this.$placeholder );

				this.$placeholder.remove();

				clearInterval( this.checkHover );

				this.endPosition = this.$selectedChild.index();

				this.trigger( { type: 'sortEnd' } );
				if ( $.isFunction( this.settings.sortEnd ) ) {
					this.settings.sortEnd.call( this, { type: 'sortEnd', startPosition: this.startPosition, endPosition: this.endPosition } );
				}
			}

			this.$selectedChild = null;
		},

		destroy: function() {
			this.$container.removeData( 'lightSortable' );

			if ( this.isDragging ) {
				this._onDragEnd();
			}

			this.$container.off( 'mousedown.lightSortable.' + this.uid );
			$( document ).off( 'mousemove.lightSortable.' + this.uid );
			$( document ).off( 'mouseup.lightSortable.' + this.uid );
		},

		on: function( type, callback ) {
			return this.events.on( type, callback );
		},
		
		off: function( type ) {
			return this.events.off( type );
		},

		trigger: function( data ) {
			return this.events.triggerHandler( data );
		},

		defaults: {
			placeholder: '',
			sortStart: function() {},
			sortEnd: function() {}
		}

	};

	$.fn.lightSortable = function( options ) {
		var args = Array.prototype.slice.call( arguments, 1 );

		return this.each(function() {
			if ( typeof $( this ).data( 'lightSortable' ) === 'undefined' ) {
				var newInstance = new LightSortable( this, options );

				$( this ).data( 'lightSortable', newInstance );
			} else if ( typeof options !== 'undefined' ) {
				var	currentInstance = $( this ).data( 'lightSortable' );

				if ( typeof currentInstance[ options ] === 'function' ) {
					currentInstance[ options ].apply( currentInstance, args );
				} else {
					$.error( options + ' does not exist in lightSortable.' );
				}
			}
		});
	};

})( jQuery );

/*
 * ======================================================================
 * lightURLParse
 * ======================================================================
 */

;(function( $ ) {

	$.lightURLParse = function( url ) {
		var urlArray = url.split( '?' )[1].split( '&' ),
			result = [];

		$.each( urlArray, function( index, element ) {
			var elementArray = element.split( '=' );
			result[ elementArray[ 0 ] ] = elementArray[ 1 ];
		});

		return result;
	};

})( jQuery );
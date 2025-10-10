// Creates a Namespace
gfPlatform = {};


// takes a plugin Constructor function and creates a jQuery style widget
jQuery.pluginMaker = function(plugin) {
	jQuery.fn[plugin.prototype.name] = function(options) {

		var args = jQuery.makeArray(arguments),
			after = args.slice(1);

		return this.each(function() {

			// see if we have an instance
			var instance = jQuery.data(this, plugin.prototype.name);

			if (instance) {

				// call a method on the instance
				if ( typeof options == "string" ) {
					instance[options].apply(instance, after);

				} else if ( instance.update ) {
					// call update on the instance
					instance.update.apply(instance, args);
				}

			} else {
				// create the plugin
				new plugin(this, options);
			}
		});
	};
};

// gfPlatform Pagination v1.0.0
//
//		A general ajax pagination plugin that loads content into the selected element.
//
// Options:
//		paginateIdentifier = This is the class or id for the <span> that wraps the anchor links in the pagination custom tag.
//		loadingImage = Path to the image used to show while it is loading
//		loadingClass = Container for the loading image
//
// Events:
//		gfPaginate_success = triggers when the loading is complete.
//
// Requirements:
//		gfplatform.base.js
//

// A Basic Pagination Constructor function
gfPlatform.Paginate = function(elem, options) {
    // if we don't have arguments, we're inheriting
    if ( elem ) {
        this.init(elem, options);
    }
};


// Extend the prototype
jQuery.extend(gfPlatform.Paginate.prototype, {

	// the name of the plugin, where it will be saved in jQuery.data
	name: "gfPaginate",

	// default options
	options: {
		paginateIdentifier: ".Pagination",            //This is the class or id for the <span> that wraps the anchor links in the pagination custom tag.
		loadingImage: "/assets/images/loading.gif",   //Path to the image used to show while it is loading
		loadingClass: '.Loading'                      //Container for the loading image
	},

	// Sets up the pagination
	init: function(elem, options) {
		// save this element for faster queries
		this.element = jQuery(elem);

		// Mix in the passed in options with the default options
		this.options = jQuery.extend({}, this.options , options || {});

		// save this instance in jQuery data
		jQuery.data(elem, this.name, this);

		// bind event handlers
		this.bind();
	},

	// bind events to this instance's methods
	bind: function() {
		var elemObject = this;

		//Pagination Loaded Via Ajax
		this.element.on({

			click: function( event ) {
				event.preventDefault();

				//Load content
				elemObject.load(jQuery(this).attr("href"), {});
			}

		}, this.options.paginateIdentifier + " a");
	},

	//Remove binded event
    unbind: function() {
		this.element.off("click", this.options.paginateIdentifier + " a");
    },

    load: function(url, queryData) {
		var elem = this.element,
			jQueryloading = jQuery('<div class="' + this.options.loadingClass.substr(1) +'"><img src="'+ this.options.loadingImage +'" alt="loading" /></div>');

		//Show loading screen
		elem.append(jQueryloading.show());

		this._ajaxCallback(url, queryData).done(function( data ) {
			//Remove the loading
			jQueryloading.remove();

			//Load the data
			elem.empty().append(data);

			//Trigger an event for extensibility
			elem.trigger('gfPaginate_success');
		});
	},

	_ajaxCallback: function(url, queryData) {
		return jQuery.ajax({
					url: url,
					data: queryData
				});
	}
});


// make the gfPaginate plugin
jQuery.pluginMaker(gfPlatform.Paginate);


// gfPlatform Pagination Remote v1.0.0
//
//		A ajax pagination plugin that uses a remote url to load content into the selected element. For use with cf_paging custom tag.
//
// Options:
//		paginateIdentifier = This is the class or id for the <span> that wraps the anchor links in the pagination custom tag.
//		loadingImage = Path to the image used to show while it is loading
//		loadingClass = Container for the loading image
//
// Events:
//		gfPaginate_success = triggers when the loading is complete.
//
// Requirements:
//		gfplatform.base.js
//		jquery.gfPaginate.js


// A Basic Pagination Constructor function
gfPlatform.PaginateRemote = function(elem, options) {
    // if we don't have arguments, we're inheriting
    if ( elem ) {
        this.init(elem, options);
    }
};


// setup the prototype chain
gfPlatform.PaginateRemote.prototype = new gfPlatform.Paginate();


// overwrite the properties we care about
jQuery.extend(gfPlatform.PaginateRemote.prototype, {
    name: "gfPaginate_remote",

    // bind events to this instance's methods
	bind: function() {
		var elemObject = this;

		//Pagination Loaded Via Ajax
		this.element.on({

			click: function( event ) {
				event.preventDefault();

				var jQuerylink = jQuery(this),
					remoteUrl = jQuerylink.parents(elemObject.options.paginateIdentifier).data("remote-url");
					queryParams = "?" + jQuerylink.attr("href").split('?')[1];

				if ( remoteUrl ) {
					//Load content
					elemObject.load(remoteUrl + queryParams, {});
				} else {
					//Load content using regular link.
					elemObject.load(jQuerylink.attr("href"), {});
				}
			}

		}, this.options.paginateIdentifier + " a");
	}
});

// make the remote url overwrite pagination plugin
jQuery.pluginMaker(gfPlatform.PaginateRemote);

// gfPlatform Radio Buttons v1.0.0
//
//		js implementation on radio buttons for style overrides.
//
// Options:
//		radioButtonClass = the class of the radio buttons group container
//		activeClass = radioButtonClass active state class
//
// Events:
//		gfRadiobuttons_selected = triggers when you click on a radio button
//
// Requirements:
//		gfplatform.base.js
//

// A Basic Radio Button Constructor function
gfPlatform.RadioButtons = function(elem, options) {
    // if we don't have arguments, we're inheriting
    if ( elem ) {
        this.init(elem, options);
    }
};


// Extend the prototype
jQuery.extend(gfPlatform.RadioButtons.prototype, {

	// the name of the plugin, where it will be saved in jQuery.data
	name: "gfRadiobuttons",

	// default options
	options: {
		radioButtonClass: ".Radio-Button",
		activeClass: ".Active"
	},

	// Sets up the radio buttons
	init: function(elem, options) {
		// save this element for faster queries
		this.element = jQuery(elem);

		// Mix in the passed in options with the default options
		this.options = jQuery.extend({}, this.options , options || {});

		// save this instance in jQuery data
		jQuery.data(elem, this.name, this);

		// bind event handlers
		this.bind();
	},

	// bind events to this instance's methods
	bind: function() {
		var elemObj = this,
			elem = this.element;

		//Select the current radio button, unselect others on click
		elem.on({

			mousedown: function( event ) {
				var jQueryradioButton = jQuery(this),
					leftClick = 1;
					event.preventDefault();

				if ( event.which === leftClick ) {
					//Remove previous radio button effect
					jQuery(elemObj.options.radioButtonClass + elemObj.options.activeClass, elem).removeClass(elemObj.options.activeClass.substr(1));

					//Check current radio button
					jQueryradioButton.addClass(elemObj.options.activeClass.substr(1)).children('input').trigger('click');

					//Trigger an event for extensibility
					elem.trigger('gfRadiobuttons_selected');
				}

			}

		}, elemObj.options.radioButtonClass);
	},

	//Remove binded event
    unbind: function() {
		this.element.off("mousedown", this.options.radioButtonClass);
    },

    //Return the selected item
	selected: function() {
		return jQuery(this.options.radioButtonClass + this.options.activeClass, this.element);
	}
});


// make the gfPlatform_radiobuttons plugin
jQuery.pluginMaker(gfPlatform.RadioButtons);


// gfPlatform Checkboxes v1.0.0
//
//		js implementation on checkboxes for style overrides.
//
// Options:
//		checkboxClass = the class of the container for the input[type="checkbox"]
//		activeClass = checkboxClass active state class
//
// Events:
//		gfCheckboxes_selected = triggers when you select on a checkbox
//		gfCheckboxes_unselected = triggers when you unselect a checkbox
// Requirements:
//		gfplatform.base.js
//

// A Basic Checkbox Constructor function
gfPlatform.Checkboxes = function(elem, options) {
    // if we don't have arguments, we're inheriting
    if ( elem ) {
        this.init(elem, options);
    }
};


// Extend the prototype
jQuery.extend(gfPlatform.Checkboxes.prototype, {

	// the name of the plugin, where it will be saved in jQuery.data
	name: "gfCheckboxes",

	// default options
	options: {
		checkboxClass: ".Checkbox",
		activeClass: ".Active"
	},

	// Sets up the checkboxes
	init: function(elem, options) {
		// save this element for faster queries
		this.element = jQuery(elem);

		// Mix in the passed in options with the default options
		this.options = jQuery.extend({}, this.options , options || {});
		this.options.activeClassName = this.options.activeClass.substr(1);

		// save this instance in jQuery data
		jQuery.data(elem, this.name, this);

		// bind event handlers
		this.bind();
	},

	// bind events to this instance's methods
	bind: function() {
		var elemObj = this,
			elem = this.element;

		//Select Checkbox on click
		elem.on({

			mousedown: function( event ) {
				var jQuerycheckbox = jQuery(this),
					leftClick = 1;

				if ( event.which === leftClick ) {

					if ( jQuerycheckbox.hasClass(elemObj.options.activeClassName)) {
						//Uncheck checkbox
						jQuerycheckbox.removeClass(elemObj.options.activeClassName);

						//Trigger an event for extensibility
						elem.trigger('gfCheckboxes_unselected');
					} else {
						//Check checkbox
						jQuerycheckbox.addClass(elemObj.options.activeClassName);

						//Trigger an event for extensibility
						elem.trigger('gfCheckboxes_selected');
					}


				}

			}

		}, elemObj.options.checkboxClass);
	},

	//Remove binded event
    unbind: function() {
		this.element.off("mousedown", this.options.checkboxClass);
    },

    //Return the selected items
	selected: function() {
		return jQuery(this.options.checkboxClass + this.options.activeClass, this.element);
	}
});


// make the gfCheckboxes plugin
jQuery.pluginMaker(gfPlatform.Checkboxes);
/**
 * @fileoverview Zapatec Auto Complete widget.
 *
 * <pre>
 * Copyright (c) 2004-2009 by Zapatec, Inc.
 * http://www.zapatec.com
 * 1700 MLK Way, Berkeley, California,
 * 94709, U.S.A.
 * All rights reserved.
 * </pre>
 */

/* $Id: zpautocomplete-core.js 16154 2009-03-03 19:55:48Z alex $ */

/**
 * Zapatec Auto Complete widget class. Extends base Zapatec Widget class
 * (utils/zpwidget.js).
 *
 * <pre>
 * Currently AutoComplete accepts only JSON source.
 *
 * Input data format:
 * {
 *   keyword: [string, optional] keyword string passed to server,
 *   tips: [object, optional] array of values containing passed keyword string
 * }
 *
 * <strong>In addition to config options defined in base Zapatec.Widget class
 * provides following config options:</strong>
 *
 * <b>dataOnDemand</b> [boolean] Must be always true because currently this is
 * the only way for AutoComplete to get data. This option should be used
 * together with <b>callbackSource</b> config option defined in
 * {@link Zapatec.Widget} class. In this case AutoComplete passes to
 * <b>callbackSource</b> function following object:
 * {
 *   keyword: [string] entered keyword of constant length defined using
 *     keywordLength config option
 * }
 * and lets it form the source, e.g. server URL, where tips are received then.
 *
 * Entered keyword length is constant and defined using <b>keywordLength</b>
 * config option. This means server side script always receives keywords of the
 * same predefined length. It must return all possible results that are filtered
 * later on the client side depending on the further input.
 *
 * Currently AutoComplete accepts only JSON source. This means
 * <b>callbackSource</b> must return either "json/url" or "json" sourceType.
 *
 * <b>convertTip</b> [function] Callback function converting tip into string.
 * Receives 3 arguments: tip object, field element, bolean value (true if
 * the result will be shown in the popup, false if the result will be shown in
 * the field). Useful for complex tips. If not defined, tips are converted using
 * default conversion. Ignored if <b>selectTip</b> config option is defined.
 *
 * <b>customEvents</b> [boolean] If true, field events (focus, keydown, keyup,
 * keypress, blur) will not trigger AutoComplete selection. Instead you can
 * use your custom events. Do not use this option unless you know exactly what
 * you are doing. Default: false.
 *
 * <b>direction</b> [string] 'up' - show drop down box above the field; 'down' -
 * below the field. Default: 'down'.
 *
 * <b>fields</b> [object] Array of input element objects or their ids.
 * AutoComplete object will be attached to all elements passed in this array and
 * shared among them. When one of input fields receives focus, AutoComplete
 * appears next to it. When field looses focus, AutoComplete disappears
 * automatically. Default: empty array.
 *
 * Passing several elements gives ability to reuse the same AutoComplete object
 * among several input fields.
 *
 * If this option is not defined, initially AutoComplete object is not attached
 * to any input field. Later, after intialization it can be attached and
 * detached dynamically using {@link Zapatec.AutoComplete#addField} and
 * {@link Zapatec.AutoComplete#removeField} methods.
 *
 * <b>height</b> [string] CSS value of tips box height. Default: content height.
 *
 * <b>keywordLength</b> [number] Length of keyword that is passed to the server.
 * Until a user inputs number of characters defined here, nothing happens. Once
 * a user inputs number of characters defined using this config option, request
 * is sent to the server side script. On further input request is not sent to
 * the server any more. Only previously received response is filtered.
 * Default: 3.
 *
 * <b>multiLine</b> [boolean] True shows tips on up and down arrow keys.
 * Default: false.
 *
 * <b>overflow</b> [string] CSS value of tips box overflow. Default: "hidden".
 *
 * <b>selectTip</b> [function] Callback function populating field with selected
 * value. Receives 2 arguments: tip object and field element. Useful for complex
 * tips. If not defined, fields are populated with the value returned by
 * <b>convertTip</b> callback.
 *
 * <b>width</b> [string] CSS value of tips box width or "auto" to adjust it to
 * the field width. Default: content width.
 * </pre>
 *
 * @constructor
 * @extends Zapatec.Widget
 * @param {object} oArg User configuration
 */
zapatecAutoComplete =
Zapatec.AutoComplete = function(oArg) {
	// Call constructor of superclass
	Zapatec.AutoComplete.SUPERconstructor.call(this, oArg);
};

// Inherit Widget
Zapatec.inherit(Zapatec.AutoComplete, Zapatec.Widget);

/**
 * Initializes object.
 *
 * @param {object} oArg User configuration
 */
Zapatec.AutoComplete.prototype.init = function(oArg) {
	// Call init method of superclass
	zapatecAutoComplete.SUPERclass.init.call(this, oArg);
};

/**
 * Reconfigures the widget with new config options after it was initialized.
 * May be used to change look or behavior of the widget after it has loaded
 * the data. In the argument pass only values for changed config options.
 * There is no need to pass config options that were not changed.
 *
 * @param {object} oArg Changes to user configuration
 */
Zapatec.AutoComplete.prototype.reconfigure = function(oArg) {
	// Remove old fields
	var aFields = this.config.fields;
	if (typeof oArg.fields != 'undefined' && (aFields instanceof Array)) {
		var iFields = aFields.length;
		var iField;
		for (iField = 0; iField < iFields; iField++) {
			this.removeField(aFields[iField]);
		}
	}
	// Call parent method
	zapatecAutoComplete.SUPERclass.reconfigure.call(this, oArg);
};

/**
 * Configures the widget. Gets called from init and reconfigure methods of
 * superclass.
 *
 * @private
 * @param {object} oArg User configuration
 */
Zapatec.AutoComplete.prototype.configure = function(oArg) {
	// Define new config options
	this.defineConfigOption('convertTip');
	this.defineConfigOption('dataOnDemand', false);
	this.defineConfigOption('direction', 'down');
	this.defineConfigOption('fields', []);
	this.defineConfigOption('height');
	this.defineConfigOption('keywordLength', 3);
	this.defineConfigOption('multiLine', false);
	this.defineConfigOption('overflow', 'hidden');
	this.defineConfigOption('selectTip');
	this.defineConfigOption('width');
	// Call parent method
	zapatecAutoComplete.SUPERclass.configure.call(this, oArg);
	// Check passed config options and correct them if needed
	var oConfig = this.config;
	oConfig.keywordLength = parseInt(oConfig.keywordLength);
	if (isNaN(oConfig.keywordLength) || oConfig.keywordLength < 0) {
		oConfig.keywordLength = 3;
	}
	// Set default convertTip callback
	if (typeof oConfig.convertTip != 'function') {
		oConfig.convertTip = zapatecAutoComplete.tipToString;
	}
	// Set default selectTip callback
	if (typeof oConfig.selectTip != 'function') {
		oConfig.selectTip = new Function('oTip', 'oField',
			'zapatecWidgetCallMethod(' + this.id + ',"populateField",oTip,oField)'
		);
	}
	// By default dimensions are in pixels
	if (oConfig.width && oConfig.width == parseInt(oConfig.width)) {
		oConfig.width += 'px';
	}
	if (oConfig.height && oConfig.height == parseInt(oConfig.height)) {
		oConfig.height += 'px';
	}
	// Add fields
	if (oConfig.fields instanceof Array) {
		var aFields = oConfig.fields;
		var iFields = aFields.length;
		var oField;
		for (var iField = 0; iField < iFields; iField++) {
			oField = aFields[iField];
			if (Zapatec.MinimalEditor && (oField instanceof Zapatec.MinimalEditor)) {
				oField.addEventListener('onInit', new Function('oField',
					'zapatecWidgetCallMethod(' + this.id + ',"addField",oField)'
				));
			} else {
				this.addField(oField);
			}
		}
	}
	// Current field
	this.field = null;
	// Active tip id
	this.activeTipId = null;
	// Selected tip
	this.tip = null;
	// Current set of tips
	this.data = null;
	// Tips cache
	this.cache = {};
};

/**
 * Adds standard event listeners.
 */
Zapatec.AutoComplete.prototype.addStandardEventListeners = function() {
	this.addEventListener('loadDataEnd', zapatecAutoComplete.onLoadDataEnd);
};

/**
 * Attaches this AutoComplete object to the specified text field.
 *
 * @param {object} oField Input element object or id
 */
Zapatec.AutoComplete.prototype.addField = function(oField) {
	if (Zapatec.MinimalEditor && (oField instanceof Zapatec.MinimalEditor)) {
		var iframeDoc = oField.pane.getIframeDocument();
		// Attach editor instance to various elements we'll be receiving events from
		this.createProperty(iframeDoc.documentElement, 'zpEditor', oField);
		this.createProperty(iframeDoc, 'zpEditor', oField);
		this.createProperty(iframeDoc.body, 'zpEditor', oField);
		this.createProperty(oField.pane.getContainer(), 'zpEditor', oField);
		iframeDoc.zpAutoCompleteId = this.id;
		iframeDoc.body.zpAutoCompleteId = this.id;
		oField = iframeDoc.documentElement;
	} else {
		// Get field object
		oField = zapatecWidget.getElementById(oField);
		if (!oField || typeof this.getFieldText(oField) == 'undefined') {
			return;
		}
	}
	// Attach
	oField.zpAutoCompleteId = this.id;
	if (oField.getAttribute) {
		// Turn browser autocomplete feature off
		oField.zpAutocompleteOrig = oField.getAttribute('autocomplete');
		oField.setAttribute('autocomplete', 'off');
	}
	// Add event listeners
	if (!this.config.customEvents) {
		zapatecUtils.addEvent(oField, 'keydown', zapatecAutoComplete.onKeydown);
		zapatecUtils.addEvent(oField, 'keyup', zapatecAutoComplete.onKeyup);
		zapatecUtils.addEvent(oField, 'keypress', zapatecAutoComplete.onKeypress);
		// If provided field is an editor
		if (oField.zpEditor) {
			oField.zpEditor.addEventListener('onBlur', zapatecAutoComplete.onBlur);
			oField.zpEditor.addEventListener('focus', zapatecAutoComplete.onFocus);
		} else {
			zapatecUtils.addEvent(oField, 'blur', zapatecAutoComplete.onBlur);
			zapatecUtils.addEvent(oField, 'focus', zapatecAutoComplete.onFocus);
		}
	}
};

/**
 * Detaches this AutoComplete object from the specified text field.
 *
 * @param {object} oField Input element object or id
 */
Zapatec.AutoComplete.prototype.removeField = function(oField) {
	// Get field object
	oField = zapatecWidget.getElementById(oField);
	if (!oField || oField.zpAutoCompleteId != this.id) {
		return;
	}
	// Detach
	oField.zpAutoCompleteId = null;
	// Turn browser autocomplete feature on
	oField.setAttribute('autocomplete', oField.zpAutocompleteOrig);
	oField.zpAutocompleteOrig = null;
	// Remove event listeners
	if (!this.config.customEvents) {
		zapatecUtils.removeEvent(oField, 'focus', zapatecAutoComplete.onFocus);
		zapatecUtils.removeEvent(oField, 'keydown',zapatecAutoComplete.onKeydown);
		zapatecUtils.removeEvent(oField, 'keyup', zapatecAutoComplete.onKeyup);
		zapatecUtils.removeEvent(oField, 'keypress', zapatecAutoComplete.onKeypress);
		zapatecUtils.removeEvent(oField, 'blur', zapatecAutoComplete.onBlur);
	}
};

/**
 * Gets text field that originated the event
 *
 * @private
 * @param {object} oEvent Event object
 */
Zapatec.AutoComplete.getEventSource = function(oEvent) {
	var oField = null;
	if (oEvent.srcElement) {
		oField = oEvent.srcElement;
	}
	else if (Zapatec.MinimalEditor && oEvent instanceof Zapatec.MinimalEditor) {
		oField = oEvent.pane.getIframeDocument();
	}
	else {
		// Get target element
		oField = zapatecUtils.getTargetElement(oEvent);
	}
	return oField;
}

/**
 * Field onfocus event listener.
 *
 * @private
 * @param {object} oEvent Event object
 */
Zapatec.AutoComplete.onFocus = function(oEvent) {
	// Get target element
	var oField = zapatecAutoComplete.getEventSource(oEvent);
	if (oField) {
		// Call method of attached AutoComplete object
		zapatecWidgetCallMethod(oField.zpAutoCompleteId, 'onFocus', {
			field: oField
		});
	}
};

/**
 * Keeps regexp used to escape double quotes.
 * @private
 * @final
 */
Zapatec.AutoComplete.acRegexpDoubleQuotes = /"/g;

/**
 * Switches AutoComplete to the specified field when it is focused. Field must
 * be previously added using {@link Zapatec.AutoComplete#addField} method.
 *
 * <pre>
 * Arguments format:
 * {
 *   field: [object] Input field element
 * }
 * </pre>
 *
 * @private
 * @param {object} oArg Arguments
 */
Zapatec.AutoComplete.prototype.onFocus = function(oArg) {
	// Shortcuts
	var iThisId = this.id;
	// Unset current field
	this.field = null;
	// Get field
	if (!oArg) {
		return;
	}
	var oField = oArg.field;
	// Field must be previously attached
	if (!oField || oField.zpAutoCompleteId != iThisId) {
		return;
	}
	// Set current field
	this.field = oField;
	// If keywordLength == 0, show selection on focus
	if (!this.config.keywordLength) {
		// Prevent loosing of focus when scrollbar is clicked in Opera and Safari
		// If scrollbar was clicked, focus is returned to the input field. No need
		// to open selection in this case because it is already opened.
		var oContent = this.content;
		if (oContent && oContent.zpACClicked) {
			return;
		}
		// Show selection
		var sKeyword = this.getFieldText(oField);
		if (sKeyword) {
			sKeyword = sKeyword.replace(
				zapatecAutoComplete.acRegexpDoubleQuotes, '\\"');
			setTimeout('zapatecWidgetCallMethod(' + iThisId +
				',"loadData",{keyword:"' + sKeyword + '"})', 0);
		}
	}
};

/**
 * Field onblur event listener.
 *
 * @private
 * @param {object} oEvent Event object
 */
Zapatec.AutoComplete.onBlur = function(oEvent) {
	// Get target element
	var oField = zapatecAutoComplete.getEventSource(oEvent);
	if (oField) {
		// Call method of attached AutoComplete object
		zapatecWidgetCallMethod(oField.zpAutoCompleteId, 'onBlur', {
			field: oField
		});
	}
};

/**
 * Selects first tip and hides tips on field blur.
 *
 * <pre>
 * Arguments format:
 * {
 *   field: [object] Input field element
 * }
 * </pre>
 *
 * @private
 * @param {object} oArg Arguments
 */
Zapatec.AutoComplete.prototype.onBlur = function(oArg) {
	// Get field
	if (!oArg) {
		return;
	}
	var oField = oArg.field;
	if (!oField) {
		return;
	}
	// Prevent loosing of focus when scrollbar is clicked in Opera and Safari
	// If scrollbar was clicked, return focus to the input field
	// this.content may be undefined at this point
	var oContent = this.content;
	if (oContent && oContent.zpACClicked) {
		oField.focus();
		oContent.zpACClicked = null;
		return;
	}
	// Select active tip if the tip was not selected
	var iActiveTipId = this.activeTipId;
	if (this.config.keywordLength) {
		// If there is no active tip, we take the first
		if (typeof iActiveTipId != 'number') {
			iActiveTipId = 0;
		}
	} else {
		// If selection is shown on focus and there is no active tip, we require
		// that field must be empty to prevent undesirable selection
		if (typeof iActiveTipId != 'number' && !this.getFieldText(oField).length) {
			iActiveTipId = 0;
		}
	}
	if (!this.tip) {
		// Populate field with active tip
		this.selectTip({
			field: oField,
			i: iActiveTipId
		});
	} else {
		// Don't change field value
		this.hide();
	}
};

/**
 * Field keydown event listener.
 *
 * @private
 * @param {object} oEvent Event object
 */
Zapatec.AutoComplete.onKeydown = function(oEvent) {
	// Get target element
	var oField = zapatecAutoComplete.getEventSource(oEvent);
	if (oField) {
		// Call method of attached AutoComplete object
		zapatecWidgetCallMethod(oField.zpAutoCompleteId, 'onKeydown', {
			event: oEvent,
			field: oField
		});
	}
};

/**
 * Hides tips on Esc, porvides keyboard navigation.
 *
 * <pre>
 * Arguments format:
 * {
 *   event: [object] Event object
 * }
 * </pre>
 *
 * @private
 * @param {object} oArg Arguments
 */
Zapatec.AutoComplete.prototype.onKeydown = function(oArg) {
	// Get event
	if (!oArg) {
		return;
	}
	var oField = oArg.field;
	if (!oField) {
		return;
	}
	var oEvent = oArg.event;
	if (!oEvent) {
		return;
	}
	// If tips are not visible
	if (!this.isTipsVisible) {
		return;
	}
	// Process key
	switch (oEvent.keyCode) {
		case 9: // Tab
			if (typeof this.activeTipId != 'number') {
				this.activeTipId = this.scrolltoTipId || 0;
			}
			// Select active tip
			this.selectTip({
				field: oField
			});
			// Prevent cursor from moving to the next field
			zapatecUtils.stopEvent(oEvent);
			break;
		case 13: // Enter
			if (typeof this.activeTipId == 'number') {
				// Select active tip
				this.selectTip({
					field: oField
				});
			}
			// Prevent form submission
			this.lastStoppedEnter = new Date().getTime();
			zapatecUtils.stopEvent(oEvent);
			break;
		case 27: // Esc
			this.activeTipId = null;
			this.hide();
			// Prevent entered value from erasing
			zapatecUtils.stopEvent(oEvent);
			break;
		case 38: // Up arrow
			this.gotoPrevTip();
			this.lastStoppedUp = new Date().getTime();
			// Prevent movement of cursor inside the field
			zapatecUtils.stopEvent(oEvent);
			break;
		case 40: // Down arrow
			this.gotoNextTip();
			this.lastStoppedDown = new Date().getTime();
			// Prevent movement of cursor inside the field
			zapatecUtils.stopEvent(oEvent);
			break;
	}
};

/**
 * Field keyup event listener.
 *
 * @private
 * @param {object} oEvent Event object
 */
Zapatec.AutoComplete.onKeyup = function(oEvent) {
	// Get target element
	var oField = zapatecAutoComplete.getEventSource(oEvent);
	if (oField) {
		// Call method of attached AutoComplete object
		zapatecWidgetCallMethod(oField.zpAutoCompleteId, 'onKeyup', {
			field: oField,
			event: oEvent
		});
	}
};

/**
 * Shows tips when there are at least keywordLength chars entered in the field.
 *
 * <pre>
 * Arguments format:
 * {
 *   field: [object] Input field element,
 *   event: [object] Event object
 * }
 * </pre>
 *
 * @private
 * @param {object} oArg Arguments
 */
Zapatec.AutoComplete.prototype.onKeyup = function(oArg) {
	// Get field
	if (!oArg) {
		return;
	}
	var oField = oArg.field;
	if (!oField) {
		return;
	}
	var sPreviousKeyword = this.acCurrentKeyword;
	var sKeyword = this.acCurrentKeyword = this.getTipsKeyword(oField);
	if (typeof sKeyword == 'undefined') {
		return;
	}
	// Get event
	var oEvent = oArg.event;
	if (!oEvent) {
		return;
	}
	// Check key
	switch (oEvent.keyCode) {
		case 9:  // Tab
		case 13: // Enter
		case 16: // Shift + Tab
		case 27: // Esc
			return;
		case 38: // Up arrow
		case 40: // Down arrow
			if (this.isTipsVisible || !this.config.multiLine) {
				return;
			}
			break;
	}
	// Check length
	var iLen = sKeyword.length;
	if (iLen >= this.config.keywordLength) {
		// Hide select box if keyword has changed
		if (sKeyword != sPreviousKeyword) {
			this.hide();
		}
		// Load tips for the keyword
		this.loadData({
			keyword: sKeyword
		});
	} else if (!iLen) {
		// Remove data
		this.data = null;
		// Hide select box
		this.hide();
	}
};

/**
 * Field keyup event listener.
 *
 * @private
 * @param {object} oEvent Event object
 */
Zapatec.AutoComplete.onKeypress = function(oEvent) {
	// Get target element
	var oField = zapatecAutoComplete.getEventSource(oEvent);
	if (oField) {
		// Call method of attached AutoComplete object
		zapatecWidgetCallMethod(oField.zpAutoCompleteId, 'onKeypress', {
			field: oField,
			event: oEvent
		});
	}
};

/**
 * Shows tips when there are at least keywordLength chars entered in the field.
 *
 * <pre>
 * Arguments format:
 * {
 *   field: [object] Input field element,
 *   event: [object] Event object
 * }
 * </pre>
 *
 * @private
 * @param {object} oArg Arguments
 */
Zapatec.AutoComplete.prototype.onKeypress = function(oArg) {
	// Get field
	if (!oArg) {
		return;
	}
	var oField = oArg.field;
	if (!oField) {
		return;
	}
	// Get event
	var oEvent = oArg.event;
	if (!oEvent) {
		return;
	}
	// Check key
	switch (oEvent.keyCode) {
		case 13: // Enter
			if (new Date().getTime() - this.lastStoppedEnter < 10) {
				// Prevent form submission
				zapatecUtils.stopEvent(oEvent);
			}
			break;
		case 38: // Up arrow
			if (new Date().getTime() - this.lastStoppedUp < 10) {
				// Prevent movement of cursor inside the field
				zapatecUtils.stopEvent(oEvent);
			}
			break;
		case 40: // Down arrow
			if (new Date().getTime() - this.lastStoppedDown < 10) {
				// Prevent movement of cursor inside the field
				zapatecUtils.stopEvent(oEvent);
			}
			break;
	}
};

/**
 * Reloads data from the specified source after widget is initialized. Argument
 * should be passed only when dataOnDemand config option is true and
 * callbackSource config option is defined. See description of dataOnDemand
 * config option for details.
 *
 * @param {object} oArg Optional. Arguments object
 */
Zapatec.AutoComplete.prototype.loadData = function(oArg) {
	// Delay loading if busy
	if (this.acBusy) {
		// Delete old delayed loadings
		this.removeEvent('acAvailable');
		// Start new loading once current is done
		this.addOnetimeEventListener('acAvailable', new Function(
			'zapatecWidgetCallMethod(' + this.id + ',"loadData",' +
			zapatecTransport.serializeJsonObj(oArg) + ')'
		));
		return;
	}
	// Set flag to prevent concurrent server calls
	this.acBusy = true;
	// Form arguments object
	if (this.config.dataOnDemand && !oArg) {
		oArg = {};
	}
	// Call parent method
	zapatecAutoComplete.SUPERclass.loadData.call(this, oArg);
};

/**
 * loadDataEnd event listener. Clears busy flag and fires acAvailable event.
 * Must be called in scope of AutoComplete object.
 * @private
 */
Zapatec.AutoComplete.onLoadDataEnd = function() {
	// Clear flag
	this.acBusy = null;
	// Fire event
	this.fireEvent('acAvailable');
};

/**
 * Loads data from the JSON source.
 *
 * @private
 * @param {object} oData Input data object
 */
Zapatec.AutoComplete.prototype.loadDataJson = function(oData) {
	// Check arguments
	if (!oData) {
		oData = {};
	}
	// Check if keyword is still actual
	if (typeof oData.keyword == 'string' &&
		this.acCurrentKeyword != oData.keyword) {
		// Keyword has changed
		return;
	}
	// Get data
	if (!(oData.tips instanceof Array)) {
		oData.tips = [];
	}
	this.data = oData;
	// Show select box
	this.show();
};

/**
 * Returns internal tips array.
 *
 * @private
 * @return Tips array
 * @type object
 */
Zapatec.AutoComplete.prototype.getTips = function() {
	var oData = this.data;
	if (!oData) {
		oData = this.data = {};
	}
	var aTips = oData.tips;
	if (!(aTips instanceof Array)) {
		aTips = oData.tips = [];
	}
	return aTips;
};

/**
 * Converts tip into string. Used when convertTip config option is not defined.
 * If passed object has toString method, this method is called.
 *
 * @private
 * @param {object} oTip Tip
 */
Zapatec.AutoComplete.tipToString = function(oTip) {
	if (!oTip) {
		return '';
	}
	if (oTip.toString) {
		return oTip.toString();
	}
	return oTip + '';
};

/**
 * Shows box with tips.
 * @private
 */
Zapatec.AutoComplete.prototype.show = function() {
	// Hide old tips
	this.hide();
	// Check if there are data
	var oData = this.data;
	if (!oData) {
		return;
	}
	var aTips = oData.tips;
	if (!(aTips instanceof Array)) {
		return;
	}
	var iTips = aTips.length;
	if (!iTips) {
		return;
	}
	// Check if field is attached
	var oField = this.getTipsNode(this.field);
	if (!oField || (!oField.zpEditor && !oField.parentNode)) {
		return;
	}
	// Get field offset
	var oFieldOffset = zapatecUtils.getElementOffsetRelative(oField);
	// Reset active tip
	this.activeTipId = null;
	// Remove selected tip
	this.tip = null;
	// Create container and WCH
	var oConfig = this.config;
	var oContainer = this.container;
	var oContent = this.content;
	var oContainerStyle;
	if (!oContainer) {
		// Create container
		oContainer = this.container = zapatecUtils.createElement('div');
		oContainerStyle = oContainer.style;
		oContainerStyle.position = 'absolute';
		oContainerStyle.zIndex = zapatecUtils.maxZindex;
		oContainerStyle.display = 'none';
		// Insert container
		oField.parentNode.insertBefore(oContainer, oField);
		// Create WCH
		this.wch = zapatecUtils.createWCH(oContainer);
		// Put WCH under container
		if (this.wch) {
			this.wch.style.zIndex = -1;
		}
		// Create content
		oContent = this.content = zapatecUtils.createElement('div', oContainer);
		var oContentStyle = oContent.style;
		oContent.className = this.getClassName({prefix: 'zpAC'});
		// Setup dimensions
		if (oConfig.width) {
			if (oConfig.width == 'auto') {
				oContentStyle.width = oField.offsetWidth + 'px';
			} else {
				oContentStyle.width = oConfig.width;
			}
		}
		if (oConfig.height) {
			oContentStyle.height = oConfig.height;
		}
		oContentStyle.overflow = oConfig.overflow;
		// Prevent loosing of focus when scrollbar is clicked in Opera and Safari
		// Set flag to know that selection was clicked
		// Doesn't work in IE and not needed there
		oContent.setAttribute('onmousedown', 'this.zpACClicked=true');
	} else {
		oContainerStyle = oContainer.style;
		if (oContainer.parentNode != oField.parentNode) {
			// Move container
			oField.parentNode.insertBefore(oContainer, oField);
		}
	}
	var aHtml = [];
	// Add tips
	var sId = this.id.toString();
	var fConvertTip = oConfig.convertTip;
	var oTip;
	for (var iTip = 0; iTip < iTips; iTip++) {
		oTip = aTips[iTip];
		aHtml.push('<div id="zpAC');
		aHtml.push(sId);
		aHtml.push('Tip');
		aHtml.push(iTip);
		aHtml.push('" class="zpACTip');
		if (iTip % 2 == 1) {
			aHtml.push(' zpACTipOdd');
		} else {
			aHtml.push(' zpACTipEven');
		}
		aHtml.push('" onmousedown="zapatecWidgetCallMethod(');
		aHtml.push(sId);
		aHtml.push(",'selectTip',{i:");
		aHtml.push(iTip);
		aHtml.push('})" onmouseover="zapatecWidgetCallMethod(');
		aHtml.push(sId);
		aHtml.push(",'setActiveTip',");
		aHtml.push(iTip);
		aHtml.push(')" onmouseout="zapatecWidgetCallMethod(');
		aHtml.push(sId);
		aHtml.push(",'resetActiveTip')\">");
		aHtml.push(fConvertTip(oTip, oField, true));
		aHtml.push('</div>');
	}
	zapatecTransport.parseHtml(aHtml.join(''), oContent);
	// Correct container position
	var bUp = (oConfig.direction == 'up');
	oContainerStyle.top =
		bUp ? '-9999px' : oFieldOffset.top + oField.offsetHeight + 'px';
	oContainerStyle.left = oFieldOffset.left + 'px';
	// Show container
	oContainerStyle.display = '';
	this.isTipsVisible = true;
	// Pop above the field if needed
	var iOffsetHeight = oContainer.offsetHeight;
	if (bUp) {
		oContainerStyle.top = oFieldOffset.top - iOffsetHeight + 'px';
	}
	// Setup WCH
	zapatecUtils.setupWCH(this.wch, 0, 0, oContainer.offsetWidth, iOffsetHeight);
	// Scroll to specified tip
	var iSelected = oData.selected;
	if (typeof iSelected == 'number') {
		oContent.scrollTop = oContent.firstChild.offsetHeight * iSelected;
		// Flag for correct keyboard navigation
		this.scrolltoTipId = iSelected;
	}
};

/**
 * Gets node which parent is going to be used as parent for the suggestions div
 *
 * @protected
 * @param {object} oField Field to get suggestions node for
 * @return node element whose parent to use for adding suggestions div to
 * @type text object
 */
Zapatec.AutoComplete.prototype.getTipsNode = function(oField) {
	var node;
	if (oField.zpEditor) {
		node = oField.zpEditor.pane.getContainer();
	}
	else{
		node = oField;
	}
	return node;
}

/**
 * Reads text inside a text field and converts it to a tips keyword.
 *
 * @private
 * @param {object} oField Field to get text from
 * @return text keyword to search suggestions for
 * @type text string
 */
Zapatec.AutoComplete.prototype.getTipsKeyword = function(oField) {
	// If field is part of an editor
	if (oField.zpEditor) {
		// Get html text from document start to caret
		var sKeyword = oField.zpEditor.getHtmlFromBeginToCaret();
		// Convert html to plain text keyword
		return this.editorHtmlToText(sKeyword);
	} else {
		var selStart = Zapatec.AutoComplete.getSelectionStart(oField);
		var text = oField.value.substr(0, selStart);
		return text;
	}
};

/**
 * Converts html to plain text. Strips html tags and keeps only text.
 *
 * @private
 * @param {string} html Html text to process
 * @return text plain text
 * @type text string
 */
Zapatec.AutoComplete.prototype.editorHtmlToText = function(html) {
	var text = html;
	text = text.replace(/&nbsp;/gi, ' ');
	text = text.replace(/<br[^>]*>/gi, '');
	text = text.replace(/<\/?p[^>]*>/gi, '');
	text = text.replace(/^\s+|\s+$/g, '');
	return text;
}

/**
 * Hides box with tips.
 * @private
 */
Zapatec.AutoComplete.prototype.hide = function() {
	// Reset active tip
	this.activeTipId = null;
	// Hide tips
	if (this.container) {
		// Hide container
		this.container.style.display = 'none';
		// Remove tips
		this.content.innerHTML = '';
	}
	this.isTipsVisible = false;
};

/**
 * Goes to the next tip. Used in keyboard navigation.
 * @private
 */
Zapatec.AutoComplete.prototype.gotoNextTip = function() {
	// Get number of tips
	var iTips = this.getTips().length;
	if (!iTips) {
		return;
	}
	// Get next tip ID
	var iScrolltoTip = this.scrolltoTipId;
	var iActiveTip = this.activeTipId;
	var iTip =
		typeof iScrolltoTip == 'number' ?	iScrolltoTip + 1 :
		(typeof iActiveTip == 'number' ? iActiveTip + 1 : 0);
	if (iTip >= iTips) {
		iTip = 0;
	}
	// Go to next tip
	this.setActiveTip(iTip);
	// Scroll to active tip
	var oContent = this.content;
	oContent.scrollTop = oContent.firstChild.offsetHeight * iTip;
	this.scrolltoTipId = iTip;
	// Select active tip
	this.selectTip({
		i: iTip,
		nohide: true
	});
};

/**
 * Goes to the previous tip. Used in keyboard navigation.
 * @private
 */
Zapatec.AutoComplete.prototype.gotoPrevTip = function() {
	// Get last tip ID
	var iLastTip = this.getTips().length - 1;
	if (iLastTip < 0) {
		return;
	}
	// Get previous tip ID
	var iScrolltoTip = this.scrolltoTipId;
	var iActiveTip = this.activeTipId;
	var iTip =
		typeof iScrolltoTip == 'number' ?	iScrolltoTip - 1 :
		(typeof iActiveTip == 'number' ? iActiveTip - 1 : iLastTip);
	if (iTip < 0) {
		iTip = iLastTip;
	}
	// Go to previous tip
	this.setActiveTip(iTip);
	// Scroll to active tip
	var oContent = this.content;
	oContent.scrollTop = oContent.firstChild.offsetHeight * iTip;
	this.scrolltoTipId = iTip;
	// Select active tip
	this.selectTip({
		i: iTip,
		nohide: true
	});
};

/**
 * Sets active tip.
 *
 * @private
 * @param {number} iTip Index of tip to make active in the visible tips array
 */
Zapatec.AutoComplete.prototype.setActiveTip = function(iTip) {
	// Reset active tip first
	this.resetActiveTip();
	// Check tip id
	if (!this.data || !(this.data.tips instanceof Array) ||
	 typeof this.data.tips[iTip] == 'undefined') {
		return;
	}
	// Set active tip
	this.activeTipId = iTip;
	// Change className of the respective div
	var oDiv = document.getElementById('zpAC' + this.id + 'Tip' + iTip);
	if (oDiv && oDiv.className.indexOf('zpACTipActive') == -1) {
		if (oDiv.className.indexOf('zpACTipOdd') == -1) {
			// Even
			oDiv.className += ' zpACTipActive zpACTipActiveEven';
		} else {
			// Odd
			oDiv.className += ' zpACTipActive zpACTipActiveOdd';
		}
	}
};

/**
 * Resets active tip.
 * @private
 */
Zapatec.AutoComplete.prototype.resetActiveTip = function() {
	// Check if there is active tip
	if (typeof this.activeTipId != 'number') {
		return;
	}
	// Change className of the respective div
	var oDiv = document.getElementById('zpAC' + this.id + 'Tip' +
	 this.activeTipId);
	if (oDiv && oDiv.className.indexOf('zpACTipActive') != -1) {
		oDiv.className = oDiv.className.replace(/ zpACTipActive[^ ]*/g, '');
	}
	// Reset active tip
	this.activeTipId = null;
};

/**
 * Selects one of visible tips.
 *
 * <pre>
 * Arguments format:
 * {
 *   field: [object, optional] field object; default: current field,
 *   i: [number, optional] index of tip to select in the visible tips array;
 *     default: active tip,
 *   nohide: [boolean, optional] tips remain visible if true; default: false
 * }
 * </pre>
 *
 * @private
 * @param {object} oArg Arguments
 */
Zapatec.AutoComplete.prototype.selectTip = function(oArg) {
	// Check arguments
	if (!oArg) {
		oArg = {};
	}
	// Get tip
	var oField = oArg.field;
	if (!oField) {
		oField = this.field;
		if (!oField) {
			!oArg.nohide && this.hide();
			return;
		}
	}
	var iTip = oArg.i;
	if (typeof iTip != 'number') {
		iTip = this.activeTipId;
		if (typeof iTip != 'number') {
			!oArg.nohide && this.hide();
			return;
		}
	}
	var oTip = this.getTips();
	oTip = oTip[iTip];
	if (!oTip) {
		!oArg.nohide && this.hide();
		return;
	}
	// Select tip
	this.tip = oTip;
	// Update field
	if(	this.activeTipId != null) {
		this.config.selectTip(oTip, oField);
	}
	// Hide tips
	!oArg.nohide && this.hide();
};

/**
 * Populates field with tip. Used when selectTip config option is not defined.
 *
 * @private
 * @param {object} oTip Tip
 * @param {object} oField Field element
 */
Zapatec.AutoComplete.prototype.populateField = function(oTip, oField) {
	// Check arguments
	if (!oField) {
		return;
	}
	// Update field
	this.setFieldText(oField, this.config.convertTip(oTip, oField, false));
};

/**
 * Gets text inside an autocomplete text field.
 *
 * @private
 * @param {object} oField Field to get text from
 * @return text field value if field is an input field or a textarea, or
 * html text if field is a zapatec editor.
 * @type text string
 */
Zapatec.AutoComplete.prototype.getFieldText = function(oField) {
	var text;
	// If field is part of an editor
	if (oField.zpEditor) {
		text = oField.zpEditor.getHTML();
	} else {
		text = oField.value;
	}
	return text;
};

/**
 * Sets text inside an autocomplete text field.
 *
 * @private
 * @param {object} oField Field to set text in
 * @param {string} sText Text to set in the field. Html text if field is an
 * editor
 */
Zapatec.AutoComplete.prototype.setFieldText = function(oField, sText) {
	// If field is part of an editor
	if (oField.zpEditor) {
		oField.zpEditor.setHTML(sText);
	} else {
		oField.value = sText;
		// Force onchange event because it does not occur if there was no input from
		// keyboard
		if (typeof oField.onchange == 'function') {
			oField.onchange();
		}
	}
};

/**
 * @private
 * zpFormMask related function.
 * Get start position of selection in INPUT element.
 * @return start position of selection in INPUT element.
 * @type int
 */
Zapatec.AutoComplete.getSelectionStart = function(field) {
	if (document.selection) {
		// IE black magic
		return Math.abs(
			document.selection.createRange().moveStart("character", -1000000)
		);
	} else if (typeof(field.selectionStart) != "undefined"){
		// mozilla and opera
		var selStart = field.selectionStart;

		// Safari bug when field is focused for first time
		if(selStart == 2147483647){
			selStart = 0;
		}

		return selStart;
	}

	return 0;
};

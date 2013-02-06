/*
 *
 * Copyright (c) 2004-2009 by Zapatec, Inc.
 * http://www.zapatec.com
 * 1700 MLK Way, Berkeley, California,
 * 94709, U.S.A.
 * All rights reserved.
 *
 *
 */


zapatecAutoComplete=Zapatec.AutoComplete=function(oArg){Zapatec.AutoComplete.SUPERconstructor.call(this,oArg);};Zapatec.inherit(Zapatec.AutoComplete,Zapatec.Widget);Zapatec.AutoComplete.prototype.init=function(oArg){zapatecAutoComplete.SUPERclass.init.call(this,oArg);};Zapatec.AutoComplete.prototype.reconfigure=function(oArg){var aFields=this.config.fields;if(typeof oArg.fields!='undefined'&&(aFields instanceof Array)){var iFields=aFields.length;var iField;for(iField=0;iField<iFields;iField++){this.removeField(aFields[iField]);}}
zapatecAutoComplete.SUPERclass.reconfigure.call(this,oArg);};Zapatec.AutoComplete.prototype.configure=function(oArg){this.defineConfigOption('convertTip');this.defineConfigOption('dataOnDemand',false);this.defineConfigOption('direction','down');this.defineConfigOption('fields',[]);this.defineConfigOption('height');this.defineConfigOption('keywordLength',3);this.defineConfigOption('multiLine',false);this.defineConfigOption('overflow','hidden');this.defineConfigOption('selectTip');this.defineConfigOption('width');zapatecAutoComplete.SUPERclass.configure.call(this,oArg);var oConfig=this.config;oConfig.keywordLength=parseInt(oConfig.keywordLength);if(isNaN(oConfig.keywordLength)||oConfig.keywordLength<0){oConfig.keywordLength=3;}
if(typeof oConfig.convertTip!='function'){oConfig.convertTip=zapatecAutoComplete.tipToString;}
if(typeof oConfig.selectTip!='function'){oConfig.selectTip=new Function('oTip','oField','zapatecWidgetCallMethod('+this.id+',"populateField",oTip,oField)');}
if(oConfig.width&&oConfig.width==parseInt(oConfig.width)){oConfig.width+='px';}
if(oConfig.height&&oConfig.height==parseInt(oConfig.height)){oConfig.height+='px';}
if(oConfig.fields instanceof Array){var aFields=oConfig.fields;var iFields=aFields.length;var oField;for(var iField=0;iField<iFields;iField++){oField=aFields[iField];if(Zapatec.MinimalEditor&&(oField instanceof Zapatec.MinimalEditor)){oField.addEventListener('onInit',new Function('oField','zapatecWidgetCallMethod('+this.id+',"addField",oField)'));}else{this.addField(oField);}}}
this.field=null;this.activeTipId=null;this.tip=null;this.data=null;this.cache={};};Zapatec.AutoComplete.prototype.addStandardEventListeners=function(){this.addEventListener('loadDataEnd',zapatecAutoComplete.onLoadDataEnd);};Zapatec.AutoComplete.prototype.addField=function(oField){if(Zapatec.MinimalEditor&&(oField instanceof Zapatec.MinimalEditor)){var iframeDoc=oField.pane.getIframeDocument();this.createProperty(iframeDoc.documentElement,'zpEditor',oField);this.createProperty(iframeDoc,'zpEditor',oField);this.createProperty(iframeDoc.body,'zpEditor',oField);this.createProperty(oField.pane.getContainer(),'zpEditor',oField);iframeDoc.zpAutoCompleteId=this.id;iframeDoc.body.zpAutoCompleteId=this.id;oField=iframeDoc.documentElement;}else{oField=zapatecWidget.getElementById(oField);if(!oField||typeof this.getFieldText(oField)=='undefined'){return;}}
oField.zpAutoCompleteId=this.id;if(oField.getAttribute){oField.zpAutocompleteOrig=oField.getAttribute('autocomplete');oField.setAttribute('autocomplete','off');}
if(!this.config.customEvents){zapatecUtils.addEvent(oField,'keydown',zapatecAutoComplete.onKeydown);zapatecUtils.addEvent(oField,'keyup',zapatecAutoComplete.onKeyup);zapatecUtils.addEvent(oField,'keypress',zapatecAutoComplete.onKeypress);if(oField.zpEditor){oField.zpEditor.addEventListener('onBlur',zapatecAutoComplete.onBlur);oField.zpEditor.addEventListener('focus',zapatecAutoComplete.onFocus);}else{zapatecUtils.addEvent(oField,'blur',zapatecAutoComplete.onBlur);zapatecUtils.addEvent(oField,'focus',zapatecAutoComplete.onFocus);}}};Zapatec.AutoComplete.prototype.removeField=function(oField){oField=zapatecWidget.getElementById(oField);if(!oField||oField.zpAutoCompleteId!=this.id){return;}
oField.zpAutoCompleteId=null;oField.setAttribute('autocomplete',oField.zpAutocompleteOrig);oField.zpAutocompleteOrig=null;if(!this.config.customEvents){zapatecUtils.removeEvent(oField,'focus',zapatecAutoComplete.onFocus);zapatecUtils.removeEvent(oField,'keydown',zapatecAutoComplete.onKeydown);zapatecUtils.removeEvent(oField,'keyup',zapatecAutoComplete.onKeyup);zapatecUtils.removeEvent(oField,'keypress',zapatecAutoComplete.onKeypress);zapatecUtils.removeEvent(oField,'blur',zapatecAutoComplete.onBlur);}};Zapatec.AutoComplete.getEventSource=function(oEvent){var oField=null;if(oEvent.srcElement){oField=oEvent.srcElement;}
else if(Zapatec.MinimalEditor&&oEvent instanceof Zapatec.MinimalEditor){oField=oEvent.pane.getIframeDocument();}
else{oField=zapatecUtils.getTargetElement(oEvent);}
return oField;}
Zapatec.AutoComplete.onFocus=function(oEvent){var oField=zapatecAutoComplete.getEventSource(oEvent);if(oField){zapatecWidgetCallMethod(oField.zpAutoCompleteId,'onFocus',{field:oField});}};Zapatec.AutoComplete.acRegexpDoubleQuotes=/"/g;Zapatec.AutoComplete.prototype.onFocus=function(oArg){var iThisId=this.id;this.field=null;if(!oArg){return;}
var oField=oArg.field;if(!oField||oField.zpAutoCompleteId!=iThisId){return;}
this.field=oField;if(!this.config.keywordLength){var oContent=this.content;if(oContent&&oContent.zpACClicked){return;}
var sKeyword=this.getFieldText(oField);if(sKeyword){sKeyword=sKeyword.replace(zapatecAutoComplete.acRegexpDoubleQuotes,'\\"');setTimeout('zapatecWidgetCallMethod('+iThisId+',"loadData",{keyword:"'+sKeyword+'"})',0);}}};Zapatec.AutoComplete.onBlur=function(oEvent){var oField=zapatecAutoComplete.getEventSource(oEvent);if(oField){zapatecWidgetCallMethod(oField.zpAutoCompleteId,'onBlur',{field:oField});}};Zapatec.AutoComplete.prototype.onBlur=function(oArg){if(!oArg){return;}
var oField=oArg.field;if(!oField){return;}
var oContent=this.content;if(oContent&&oContent.zpACClicked){oField.focus();oContent.zpACClicked=null;return;}
var iActiveTipId=this.activeTipId;if(this.config.keywordLength){if(typeof iActiveTipId!='number'){iActiveTipId=0;}}else{if(typeof iActiveTipId!='number'&&!this.getFieldText(oField).length){iActiveTipId=0;}}
if(!this.tip){this.selectTip({field:oField,i:iActiveTipId});}else{this.hide();}};Zapatec.AutoComplete.onKeydown=function(oEvent){var oField=zapatecAutoComplete.getEventSource(oEvent);if(oField){zapatecWidgetCallMethod(oField.zpAutoCompleteId,'onKeydown',{event:oEvent,field:oField});}};Zapatec.AutoComplete.prototype.onKeydown=function(oArg){if(!oArg){return;}
var oField=oArg.field;if(!oField){return;}
var oEvent=oArg.event;if(!oEvent){return;}
if(!this.isTipsVisible){return;}
switch(oEvent.keyCode){case 9:if(typeof this.activeTipId!='number'){this.activeTipId=this.scrolltoTipId||0;}
this.selectTip({field:oField});zapatecUtils.stopEvent(oEvent);break;case 13:if(typeof this.activeTipId=='number'){this.selectTip({field:oField});}
this.lastStoppedEnter=new Date().getTime();zapatecUtils.stopEvent(oEvent);break;case 27:this.activeTipId=null;this.hide();zapatecUtils.stopEvent(oEvent);break;case 38:this.gotoPrevTip();this.lastStoppedUp=new Date().getTime();zapatecUtils.stopEvent(oEvent);break;case 40:this.gotoNextTip();this.lastStoppedDown=new Date().getTime();zapatecUtils.stopEvent(oEvent);break;}};Zapatec.AutoComplete.onKeyup=function(oEvent){var oField=zapatecAutoComplete.getEventSource(oEvent);if(oField){zapatecWidgetCallMethod(oField.zpAutoCompleteId,'onKeyup',{field:oField,event:oEvent});}};Zapatec.AutoComplete.prototype.onKeyup=function(oArg){if(!oArg){return;}
var oField=oArg.field;if(!oField){return;}
var sPreviousKeyword=this.acCurrentKeyword;var sKeyword=this.acCurrentKeyword=this.getTipsKeyword(oField);if(typeof sKeyword=='undefined'){return;}
var oEvent=oArg.event;if(!oEvent){return;}
switch(oEvent.keyCode){case 9:case 13:case 16:case 27:return;case 38:case 40:if(this.isTipsVisible||!this.config.multiLine){return;}
break;}
var iLen=sKeyword.length;if(iLen>=this.config.keywordLength){if(sKeyword!=sPreviousKeyword){this.hide();}
this.loadData({keyword:sKeyword});}else if(!iLen){this.data=null;this.hide();}};Zapatec.AutoComplete.onKeypress=function(oEvent){var oField=zapatecAutoComplete.getEventSource(oEvent);if(oField){zapatecWidgetCallMethod(oField.zpAutoCompleteId,'onKeypress',{field:oField,event:oEvent});}};Zapatec.AutoComplete.prototype.onKeypress=function(oArg){if(!oArg){return;}
var oField=oArg.field;if(!oField){return;}
var oEvent=oArg.event;if(!oEvent){return;}
switch(oEvent.keyCode){case 13:if(new Date().getTime()-this.lastStoppedEnter<10){zapatecUtils.stopEvent(oEvent);}
break;case 38:if(new Date().getTime()-this.lastStoppedUp<10){zapatecUtils.stopEvent(oEvent);}
break;case 40:if(new Date().getTime()-this.lastStoppedDown<10){zapatecUtils.stopEvent(oEvent);}
break;}};Zapatec.AutoComplete.prototype.loadData=function(oArg){if(this.acBusy){this.removeEvent('acAvailable');this.addOnetimeEventListener('acAvailable',new Function('zapatecWidgetCallMethod('+this.id+',"loadData",'+
zapatecTransport.serializeJsonObj(oArg)+')'));return;}
this.acBusy=true;if(this.config.dataOnDemand&&!oArg){oArg={};}
zapatecAutoComplete.SUPERclass.loadData.call(this,oArg);};Zapatec.AutoComplete.onLoadDataEnd=function(){this.acBusy=null;this.fireEvent('acAvailable');};Zapatec.AutoComplete.prototype.loadDataJson=function(oData){if(!oData){oData={};}
if(typeof oData.keyword=='string'&&this.acCurrentKeyword!=oData.keyword){return;}
if(!(oData.tips instanceof Array)){oData.tips=[];}
this.data=oData;this.show();};Zapatec.AutoComplete.prototype.getTips=function(){var oData=this.data;if(!oData){oData=this.data={};}
var aTips=oData.tips;if(!(aTips instanceof Array)){aTips=oData.tips=[];}
return aTips;};Zapatec.AutoComplete.tipToString=function(oTip){if(!oTip){return'';}
if(oTip.toString){return oTip.toString();}
return oTip+'';};Zapatec.AutoComplete.prototype.show=function(){this.hide();var oData=this.data;if(!oData){return;}
var aTips=oData.tips;if(!(aTips instanceof Array)){return;}
var iTips=aTips.length;if(!iTips){return;}
var oField=this.getTipsNode(this.field);if(!oField||(!oField.zpEditor&&!oField.parentNode)){return;}
var oFieldOffset=zapatecUtils.getElementOffsetRelative(oField);this.activeTipId=null;this.tip=null;var oConfig=this.config;var oContainer=this.container;var oContent=this.content;var oContainerStyle;if(!oContainer){oContainer=this.container=zapatecUtils.createElement('div');oContainerStyle=oContainer.style;oContainerStyle.position='absolute';oContainerStyle.zIndex=zapatecUtils.maxZindex;oContainerStyle.display='none';oField.parentNode.insertBefore(oContainer,oField);this.wch=zapatecUtils.createWCH(oContainer);if(this.wch){this.wch.style.zIndex=-1;}
oContent=this.content=zapatecUtils.createElement('div',oContainer);var oContentStyle=oContent.style;oContent.className=this.getClassName({prefix:'zpAC'});if(oConfig.width){if(oConfig.width=='auto'){oContentStyle.width=oField.offsetWidth+'px';}else{oContentStyle.width=oConfig.width;}}
if(oConfig.height){oContentStyle.height=oConfig.height;}
oContentStyle.overflow=oConfig.overflow;oContent.setAttribute('onmousedown','this.zpACClicked=true');}else{oContainerStyle=oContainer.style;if(oContainer.parentNode!=oField.parentNode){oField.parentNode.insertBefore(oContainer,oField);}}
var aHtml=[];var sId=this.id.toString();var fConvertTip=oConfig.convertTip;var oTip;for(var iTip=0;iTip<iTips;iTip++){oTip=aTips[iTip];aHtml.push('<div id="zpAC');aHtml.push(sId);aHtml.push('Tip');aHtml.push(iTip);aHtml.push('" class="zpACTip');if(iTip%2==1){aHtml.push(' zpACTipOdd');}else{aHtml.push(' zpACTipEven');}
aHtml.push('" onmousedown="zapatecWidgetCallMethod(');aHtml.push(sId);aHtml.push(",'selectTip',{i:");aHtml.push(iTip);aHtml.push('})" onmouseover="zapatecWidgetCallMethod(');aHtml.push(sId);aHtml.push(",'setActiveTip',");aHtml.push(iTip);aHtml.push(')" onmouseout="zapatecWidgetCallMethod(');aHtml.push(sId);aHtml.push(",'resetActiveTip')\">");aHtml.push(fConvertTip(oTip,oField,true));aHtml.push('</div>');}
zapatecTransport.parseHtml(aHtml.join(''),oContent);var bUp=(oConfig.direction=='up');oContainerStyle.top=bUp?'-9999px':oFieldOffset.top+oField.offsetHeight+'px';oContainerStyle.left=oFieldOffset.left+'px';oContainerStyle.display='';this.isTipsVisible=true;var iOffsetHeight=oContainer.offsetHeight;if(bUp){oContainerStyle.top=oFieldOffset.top-iOffsetHeight+'px';}
zapatecUtils.setupWCH(this.wch,0,0,oContainer.offsetWidth,iOffsetHeight);var iSelected=oData.selected;if(typeof iSelected=='number'){oContent.scrollTop=oContent.firstChild.offsetHeight*iSelected;this.scrolltoTipId=iSelected;}};Zapatec.AutoComplete.prototype.getTipsNode=function(oField){var node;if(oField.zpEditor){node=oField.zpEditor.pane.getContainer();}
else{node=oField;}
return node;}
Zapatec.AutoComplete.prototype.getTipsKeyword=function(oField){if(oField.zpEditor){var sKeyword=oField.zpEditor.getHtmlFromBeginToCaret();return this.editorHtmlToText(sKeyword);}else{var selStart=Zapatec.AutoComplete.getSelectionStart(oField);var text=oField.value.substr(0,selStart);return text;}};Zapatec.AutoComplete.prototype.editorHtmlToText=function(html){var text=html;text=text.replace(/&nbsp;/gi,' ');text=text.replace(/<br[^>]*>/gi,'');text=text.replace(/<\/?p[^>]*>/gi,'');text=text.replace(/^\s+|\s+$/g,'');return text;}
Zapatec.AutoComplete.prototype.hide=function(){this.activeTipId=null;if(this.container){this.container.style.display='none';this.content.innerHTML='';}
this.isTipsVisible=false;};Zapatec.AutoComplete.prototype.gotoNextTip=function(){var iTips=this.getTips().length;if(!iTips){return;}
var iScrolltoTip=this.scrolltoTipId;var iActiveTip=this.activeTipId;var iTip=typeof iScrolltoTip=='number'?iScrolltoTip+1:(typeof iActiveTip=='number'?iActiveTip+1:0);if(iTip>=iTips){iTip=0;}
this.setActiveTip(iTip);var oContent=this.content;oContent.scrollTop=oContent.firstChild.offsetHeight*iTip;this.scrolltoTipId=iTip;this.selectTip({i:iTip,nohide:true});};Zapatec.AutoComplete.prototype.gotoPrevTip=function(){var iLastTip=this.getTips().length-1;if(iLastTip<0){return;}
var iScrolltoTip=this.scrolltoTipId;var iActiveTip=this.activeTipId;var iTip=typeof iScrolltoTip=='number'?iScrolltoTip-1:(typeof iActiveTip=='number'?iActiveTip-1:iLastTip);if(iTip<0){iTip=iLastTip;}
this.setActiveTip(iTip);var oContent=this.content;oContent.scrollTop=oContent.firstChild.offsetHeight*iTip;this.scrolltoTipId=iTip;this.selectTip({i:iTip,nohide:true});};Zapatec.AutoComplete.prototype.setActiveTip=function(iTip){this.resetActiveTip();if(!this.data||!(this.data.tips instanceof Array)||typeof this.data.tips[iTip]=='undefined'){return;}
this.activeTipId=iTip;var oDiv=document.getElementById('zpAC'+this.id+'Tip'+iTip);if(oDiv&&oDiv.className.indexOf('zpACTipActive')==-1){if(oDiv.className.indexOf('zpACTipOdd')==-1){oDiv.className+=' zpACTipActive zpACTipActiveEven';}else{oDiv.className+=' zpACTipActive zpACTipActiveOdd';}}};Zapatec.AutoComplete.prototype.resetActiveTip=function(){if(typeof this.activeTipId!='number'){return;}
var oDiv=document.getElementById('zpAC'+this.id+'Tip'+
this.activeTipId);if(oDiv&&oDiv.className.indexOf('zpACTipActive')!=-1){oDiv.className=oDiv.className.replace(/ zpACTipActive[^ ]*/g,'');}
this.activeTipId=null;};Zapatec.AutoComplete.prototype.selectTip=function(oArg){if(!oArg){oArg={};}
var oField=oArg.field;if(!oField){oField=this.field;if(!oField){!oArg.nohide&&this.hide();return;}}
var iTip=oArg.i;if(typeof iTip!='number'){iTip=this.activeTipId;if(typeof iTip!='number'){!oArg.nohide&&this.hide();return;}}
var oTip=this.getTips();oTip=oTip[iTip];if(!oTip){!oArg.nohide&&this.hide();return;}
this.tip=oTip;if(this.activeTipId!=null){this.config.selectTip(oTip,oField);}!oArg.nohide&&this.hide();};Zapatec.AutoComplete.prototype.populateField=function(oTip,oField){if(!oField){return;}
this.setFieldText(oField,this.config.convertTip(oTip,oField,false));};Zapatec.AutoComplete.prototype.getFieldText=function(oField){var text;if(oField.zpEditor){text=oField.zpEditor.getHTML();}else{text=oField.value;}
return text;};Zapatec.AutoComplete.prototype.setFieldText=function(oField,sText){if(oField.zpEditor){oField.zpEditor.setHTML(sText);}else{oField.value=sText;if(typeof oField.onchange=='function'){oField.onchange();}}};Zapatec.AutoComplete.getSelectionStart=function(field){if(document.selection){return Math.abs(document.selection.createRange().moveStart("character",-1000000));}else if(typeof(field.selectionStart)!="undefined"){var selStart=field.selectionStart;if(selStart==2147483647){selStart=0;}
return selStart;}
return 0;};
Zapatec.Utils.addEvent(window, 'load', Zapatec.Utils.checkActivation);

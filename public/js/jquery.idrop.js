/**
 * Interface Elements for jQuery
 * FX - drop
 * 
 * http://interface.eyecon.ro
 * 
 * Copyright (c) 2006 Stefan Petre
 * Dual licensed under the MIT (MIT-LICENSE.txt) 
 * and GPL (GPL-LICENSE.txt) licenses.
 *   
 *
 */
(function($){

/**
 * Applies a dropping effect to element
 */
jQuery.fn.extend(
	{
		/**
		 * @name DropOutDown
		 * @description drops the element out down
		 * @param Mixed speed animation speed, integer for miliseconds, string ['slow' | 'normal' | 'fast']
		 * @param Function callback (optional) A function to be executed whenever the animation completes.
		 * @param String easing (optional) The name of the easing effect that you want to use.
		 * @type jQuery
		 * @cat Plugins/Interface
		 * @author Stefan Petre
		 */
		DropOutDown : function (speed, callback, easing) {
			return this.Drop(speed, callback, 'down', 'out', easing);
		},
		
		/**
		 * @name DropInDown
		 * @description drops the element in down
		 * @param Mixed speed animation speed, integer for miliseconds, string ['slow' | 'normal' | 'fast']
		 * @param Function callback (optional) A function to be executed whenever the animation completes.
		 * @param String easing (optional) The name of the easing effect that you want to use.
		 * @type jQuery
		 * @cat Plugins/Interface
		 * @author Stefan Petre
		 */
		DropInDown : function (speed, callback, easing) {
			return this.Drop(speed, callback, 'down', 'in', easing);
		},
		
		/**
		 * @name DropToggleDown
		 * @description drops the element in/out down
		 * @param Mixed speed animation speed, integer for miliseconds, string ['slow' | 'normal' | 'fast']
		 * @param Function callback (optional) A function to be executed whenever the animation completes.
		 * @param String easing (optional) The name of the easing effect that you want to use.
		 * @type jQuery
		 * @cat Plugins/Interface
		 * @author Stefan Petre
		 */
		DropToggleDown : function (speed, callback, easing) {
			return this.Drop(speed, callback, 'down', 'toggle', easing);
		},
		
		/**
		 * @name DropOutUp
		 * @description drops the element out up
		 * @param Mixed speed animation speed, integer for miliseconds, string ['slow' | 'normal' | 'fast']
		 * @param Function callback (optional) A function to be executed whenever the animation completes.
		 * @param String easing (optional) The name of the easing effect that you want to use.
		 * @type jQuery
		 * @cat Plugins/Interface
		 * @author Stefan Petre
		 */
		DropOutUp : function (speed, callback, easing) {
			return this.Drop(speed, callback, 'up', 'out', easing);
		},
		
		/**
		 * @name DropInUp
		 * @description drops the element in up
		 * @param Mixed speed animation speed, integer for miliseconds, string ['slow' | 'normal' | 'fast']
		 * @param Function callback (optional) A function to be executed whenever the animation completes.
		 * @param String easing (optional) The name of the easing effect that you want to use.
		 * @type jQuery
		 * @cat Plugins/Interface
		 * @author Stefan Petre
		 */
		DropInUp : function (speed, callback, easing) {
			return this.Drop(speed, callback, 'up', 'in', easing);
		},
		
		/**
		 * @name DropToggleUp
		 * @description drops the element in/out up
		 * @param Mixed speed animation speed, integer for miliseconds, string ['slow' | 'normal' | 'fast']
		 * @param Function callback (optional) A function to be executed whenever the animation completes.
		 * @param String easing (optional) The name of the easing effect that you want to use.
		 * @type jQuery
		 * @cat Plugins/Interface
		 * @author Stefan Petre
		 */
		DropToggleUp : function (speed, callback, easing) {
			return this.Drop(speed, callback, 'up', 'toggle', easing);
		},
		
		/**
		 * @name DropOutLeft
		 * @description drops the element out left
		 * @param Mixed speed animation speed, integer for miliseconds, string ['slow' | 'normal' | 'fast']
		 * @param Function callback (optional) A function to be executed whenever the animation completes.
		 * @param String easing (optional) The name of the easing effect that you want to use.
		 * @type jQuery
		 * @cat Plugins/Interface
		 * @author Stefan Petre
		 */
		DropOutLeft : function (speed, callback, easing) {
			return this.Drop(speed, callback, 'left', 'out', easing);
		},
		
		/**
		 * @name DropInLeft
		 * @description drops the element in left
		 * @param Mixed speed animation speed, integer for miliseconds, string ['slow' | 'normal' | 'fast']
		 * @param Function callback (optional) A function to be executed whenever the animation completes.
		 * @param String easing (optional) The name of the easing effect that you want to use.
		 * @type jQuery
		 * @cat Plugins/Interface
		 * @author Stefan Petre
		 */
		DropInLeft : function (speed, callback, easing) {
			return this.Drop(speed, callback, 'left', 'in', easing);
		},
		
		/**
		 * @name DropToggleLeft
		 * @description drops the element in/out left
		 * @param Mixed speed animation speed, integer for miliseconds, string ['slow' | 'normal' | 'fast']
		 * @param Function callback (optional) A function to be executed whenever the animation completes.
		 * @param String easing (optional) The name of the easing effect that you want to use.
		 * @type jQuery
		 * @cat Plugins/Interface
		 * @author Stefan Petre
		 */
		DropToggleLeft : function (speed, callback, easing) {
			return this.Drop(speed, callback, 'left', 'toggle', easing);
		},
		
		/**
		 * @name DropOutRight
		 * @description drops the element out right
		 * @param Mixed speed animation speed, integer for miliseconds, string ['slow' | 'normal' | 'fast']
		 * @param Function callback (optional) A function to be executed whenever the animation completes.
		 * @param String easing (optional) The name of the easing effect that you want to use.
		 * @type jQuery
		 * @cat Plugins/Interface
		 * @author Stefan Petre
		 */
		DropOutRight : function (speed, callback, easing) {
			return this.Drop(speed, callback, 'right', 'out', easing);
		},
		
		/**
		 * @name DropInRight
		 * @description drops the element in right
		 * @param Mixed speed animation speed, integer for miliseconds, string ['slow' | 'normal' | 'fast']
		 * @param Function callback (optional) A function to be executed whenever the animation completes.
		 * @param String easing (optional) The name of the easing effect that you want to use.
		 * @type jQuery
		 * @cat Plugins/Interface
		 * @author Stefan Petre
		 */
		DropInRight : function (speed, callback, easing) {
			return this.Drop( speed, callback, 'right', 'in', easing);
		},
		
		/**
		 * @name DropToggleRight
		 * @description drops the element in/out right
		 * @param Mixed speed animation speed, integer for miliseconds, string ['slow' | 'normal' | 'fast']
		 * @param Function callback (optional) A function to be executed whenever the animation completes.
		 * @param String easing (optional) The name of the easing effect that you want to use.
		 * @type jQuery
		 * @cat Plugins/Interface
		 * @author Stefan Petre
		 */
		DropToggleRight : function (speed, callback, easing) {
			return this.Drop(speed, callback, 'right', 'toggle', easing);
		},
		
		Drop: function(speed, callback, direction, type, easing) {
			easing = typeof callback == 'string' ? callback : easing||null;
			callback = typeof callback == 'function' ? callback : null;
			return this.filter(function(){
				if (!jQuery.fxCheckTag(this)) {
					return false;
				}
				return true;
			})
			.each(function(){
				this.style.fakeDrop = 0;
				this.inited = false;
			})
			.animate({
				fakeDrop: 100
			}, {
				duration: speed,
				easing: easing,
				step: function(now) {
					if (this.inited == false) {
						this.inited = true;
						this.oldStyle = {
							position: $.curCSS(this, 'position'),
							display: $.curCSS(this, 'display'),
							top: parseInt($.curCSS(this, 'top'),10)||0,
							left: parseInt($.curCSS(this, 'left'),10)||0
						};
						this.dropType = type == 'toggle' ? ( this.oldStyle.display == 'none' ? 'in' : 'out' ) : type;
						if (!/absolute|relative/.test(this.oldStyle.position)) {
							this.style.position = 'relative';
						}
						if (this.oldStyle.display == 'none') {
							this.style.display = ' block';
						}
					}
					switch (direction) {
						case 'up':
							this.style.top = this.oldStyle.top - (this.dropType == 'in' ? 100 - now :  now ) + 'px';
							break;
						case 'down':
							this.style.top = this.oldStyle.top + (this.dropType == 'in' ? 100 - now :  now )  + 'px';
							break;
						case 'left':
							this.style.left = this.oldStyle.left - (this.dropType == 'in' ? 100 - now :  now )  + 'px';
							break;
						case 'right':
							this.style.left = this.oldStyle.left + (this.dropType == 'in' ? 100 - now :  now )  + 'px';
							break;
					}
					$(this).css('opacity', this.dropType == 'in' ? now /100 : (100 - now)/100);
				},
				complete: function() {
					this.style.top = this.oldStyle.top + 'px';
					this.style.left = this.oldStyle.left + 'px';
					$(this).css('opacity', 1);
					this.style.display = this.dropType == 'in' ? 'block' : 'none';
					if (callback) {
						callback.apply(this);
					}
				}
			})
			.end();
		}
	});
})(jQuery);

/**
 * Interface Elements for jQuery
 * FX
 * 
 * http://interface.eyecon.ro
 * 
 * Copyright (c) 2006 Stefan Petre
 * Dual licensed under the MIT (MIT-LICENSE.txt) 
 * and GPL (GPL-LICENSE.txt) licenses.
 *   
 *
 */

/**
 * Validates elements that can be animated
 */
jQuery.fxCheckTag = function(e)
{
	if (/^tr$|^td$|^tbody$|^caption$|^thead$|^tfoot$|^col$|^colgroup$|^th$|^body$|^header$|^script$|^frame$|^frameset$|^option$|^optgroup$|^meta$/i.test(e.nodeName) )
		return false;
	else 
		return true;
};

/**
 * Destroy the wrapper used for some animations
 */
jQuery.fx.destroyWrapper = function(e, old)
{
	var c = e.firstChild;
	var cs = c.style;
	cs.position = old.position;
	cs.marginTop = old.margins.t;
	cs.marginLeft = old.margins.l;
	cs.marginBottom = old.margins.b;
	cs.marginRight = old.margins.r;
	cs.top = old.top + 'px';
	cs.left = old.left + 'px';
	e.parentNode.insertBefore(c, e);
	e.parentNode.removeChild(e);
};

/**
 * Builds a wrapper used for some animations
 */
jQuery.fx.buildWrapper = function(e)
{
	if (!jQuery.fxCheckTag(e))
		return false;
	var t = jQuery(e);
	var es = e.style;
	var restoreStyle = false;
	var oldStyle = {};
	oldStyle.position = t.css('position');
	
	if (t.css('display') == 'none') {
		oldVisibility = t.css('visibility');
		es.visibility = 'hidden';
		es.display = '';
		restoreStyle = true;
	}
	oldStyle.sizes = jQuery.iUtil.getSize(e);
	oldStyle.margins = jQuery.iUtil.getMargins(e);
	
	var oldFloat = e.currentStyle ? e.currentStyle.styleFloat : t.css('float');
	oldStyle.top = parseInt(t.css('top'))||0;
	oldStyle.left = parseInt(t.css('left'))||0;
	var wid = 'w_' + parseInt(Math.random() * 10000);
	var wr = document.createElement(/^img$|^br$|^input$|^hr$|^select$|^textarea$|^object$|^iframe$|^button$|^form$|^table$|^ul$|^dl$|^ol$/i.test(e.nodeName) ? 'div' : e.nodeName);
	jQuery.attr(wr,'id', wid);
	wr.className = 'fxWrapper';
	var wrs = wr.style;
	var top = 0;
	var left = 0;
	if (oldStyle.position == 'relative' || oldStyle.position == 'absolute'){
		top = oldStyle.top;
		left = oldStyle.left;
	}
	
	wrs.display = 'none';
	wrs.top = top + 'px';
	wrs.left = left + 'px';
	wrs.position = oldStyle.position != 'relative' && oldStyle.position != 'absolute' ? 'relative' : oldStyle.position;
	wrs.overflow = 'hidden';
	wrs.height = oldStyle.sizes.hb + 'px';
	wrs.width = oldStyle.sizes.wb + 'px';
	wrs.marginTop = oldStyle.margins.t;
	wrs.marginRight = oldStyle.margins.r;
	wrs.marginBottom = oldStyle.margins.b;
	wrs.marginLeft = oldStyle.margins.l;
	if (jQuery.browser.msie) {
		wrs.styleFloat = oldFloat;
	} else {
		wrs.cssFloat = oldFloat;
	}
	e.parentNode.insertBefore(wr, e);
	es.marginTop = '0px';
	es.marginRight = '0px';
	es.marginBottom = '0px';
	es.marginLeft = '0px';
	es.position = 'absolute';
	es.listStyle = 'none';
	es.top = '0px';
	es.left = '0px';
	if (restoreStyle) {
		es.display = 'none';
		es.visibility = oldVisibility;
	}
	wr.appendChild(e);
	wrs.display = 'block';
	return {oldStyle:oldStyle, wrapper:jQuery(wr)};
};

/*
 * jQuery UI Multiselect
 *
 * Authors:
 *  Michael Aufreiter (quasipartikel.at)
 *  Yanick Rochon (yanick.rochon[at]gmail[dot]com)
 * 
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 * 
 * http://www.quasipartikel.at/multiselect/
 *
 * 
 * Depends:
 *	ui.core.js
 *	ui.sortable.js
 *
 * Optional:
 * localization (http://plugins.jquery.com/project/localisation)
 * scrollTo (http://plugins.jquery.com/project/ScrollTo)
 * 
 * Todo:
 *  Make batch actions faster
 *  Implement dynamic insertion through remote calls
 */

  
   

(function($) {
 
    jQuery.expr[':'].data = function(elem, index, m) {
            // Remove ":data(" and the trailing ")" from the match, as these parts aren't needed:
            m[0] = m[0].replace(/:data\(|\)$/g, '');
            var regex = new RegExp('([\'"]?)((?:\\\\\\1|.)+?)\\1(,|$)', 'g'),
            // Retrieve data key:
            key = regex.exec( m[0] )[2],
            // Retrieve data value to test against:
            val = regex.exec( m[0] );
            if (val) {
                val = val[2];
            }
            // If a value was passed then we test for it, otherwise we test that the value evaluates to true:
            return val ? jQuery(elem).data(key) == val : !!jQuery(elem).data(key);
    };

var contador=0;

$.widget("ui.multiselect", {
  options: {
		sortable: true,
		searchable: true,
		doubleClickable: true,
		animated: 'fast',
		show: 'slideDown',
		hide: 'slideUp',
		dividerLocation: 0.6,
		nodeComparator: function(node1,node2) {
			var text1 = node1.text(),
			    text2 = node2.text();
			return text1 == text2 ? 0 : (text1 < text2 ? -1 : 1);
		},
		orderComparator: function(node1,node2) {
			var text1 = parseInt(node1.data("order")),
		    text2 = parseInt(node2.data("order"));
		return text1 == text2 ? 0 : (text1 < text2 ? -1 : 1);
		}
	},
	_create: function() {
		this.element.hide();
		this.id = this.element.attr("id");
		this.container = $('<div class="ui-multiselect ui-helper-clearfix ui-widget"></div>').insertAfter(this.element);
		this.count = 0; // number of currently selected options
		this.selectedContainer = $('<div class="selected"></div>').appendTo(this.container);
		this.availableContainer = $('<div class="available"></div>').appendTo(this.container);
		this.selectedActions = $('<div class="actions ui-widget-header ui-helper-clearfix"><span class="count">0 '+$.ui.multiselect.locale.itemsCount+'</span><a href="#" class="remove-all">'+$.ui.multiselect.locale.removeAll+'</a></div>').appendTo(this.selectedContainer);
		this.availableActions = $('<div class="actions ui-widget-header ui-helper-clearfix"><input type="text" class="search empty ui-widget-content ui-corner-all"/><a href="#" class="add-all">'+$.ui.multiselect.locale.addAll+'</a></div>').appendTo(this.availableContainer);
		this.selectedList = $('<ul class="selected connected-list"><li class="ui-helper-hidden-accessible"></li></ul>').bind('selectstart', function(){return false;}).appendTo(this.selectedContainer);
		this.availableList = $('<ul class="available connected-list"><li class="ui-helper-hidden-accessible"></li></ul>').bind('selectstart', function(){return false;}).appendTo(this.availableContainer);
		
		var that = this;

		// set dimensions
		this.container.width(this.element.width()+1);
		this.selectedContainer.width(Math.floor(this.element.width()*this.options.dividerLocation));
		this.availableContainer.width(Math.floor(this.element.width()*(1-this.options.dividerLocation)));

		// fix list height to match <option> depending on their individual header's heights
		this.selectedList.height(Math.max(this.element.height()-this.selectedActions.height(),1));
		this.availableList.height(Math.max(this.element.height()-this.availableActions.height(),1));
		
		if ( !this.options.animated ) {
			this.options.show = 'show';
			this.options.hide = 'hide';
		}
                
		if( this.element.find('optgroup').length ) { 
                    // init lists
                    var children = this.element.children();
                    var gral=this;
                   
        			
                    children.each( function() {                                 
                        if (this.tagName == 'OPTION') {
                        	
							 var items = gral.selectedList.find('li'), comparator = gral.options.orderComparator;
                        	 var succ = null;
                            var item = gral._getOptionNode(this,false);
                            if (this.selected) gral.count += 1;
                            gral._applyItemState(item, this.selected);
                            item.data('idx', contador);
                            item.data('father', null);
                            
                           
                            if ( typeof $(this).attr('order') !== "undefined" && $(this).attr('order')) {
                            	item.data('order', $(this).attr('order'));
                            }
                            if(this.selected){
                            	var i = item.data('order'), direction = comparator(item, $(items[i]));
                                            
                                                // TODO: test needed for dynamic list populating
                                                if ( direction ) {
                                                        while (i>=0 && i<items.length) {
                                                                direction > 0 ? i++ : i--;
                                                                if ( direction != comparator(item, $(items[i])) ) {
                                                                        // going up, go back one item down, otherwise leave as is
                                                                        succ = items[direction > 0 ? i : i+1];
                                                                        break;
                                                                }
                                                        }
                                                } else {
                                                        succ = items[i];
                                                }
                                                item.appendTo(this.selected ? gral.selectedList : gral.availableList).show();
                                                var availableItem = item;
                                                succ ? availableItem.insertBefore($(succ)).show() : availableItem.appendTo(gral.selectedList).show();
                                            
                            }
                            else{
                            	item.appendTo(gral.availableList).show();
                            }
                            
                            contador++;
                        } else {
                            var item2 =  gral._getGroupOptionNode(this);
                             if (this.selected) gral.count += 1;
                            gral._applyItemState(item2, this.selected);
                            item2.data('idx', contador);
                            item2.data('father', null);
                            if ( typeof $(this).attr('order') !== "undefined" && $(this).attr('order')) {
                            	item2.data('order', $(this).attr('order'));
                            }
                            item2.appendTo(gral.availableList).show();
                            var idfather=contador;
                            contador++;
                            var childrenOptGroup=$(this).children();
                            
                            childrenOptGroup.each( function() {    
                                $(this).text(' '+$(this).text());
								 var itemopt=gral._getOptionNode($(this),true);
                                if (this.selected){ 
                                    gral.count += 1;
                                }
                                gral._applyItemState(itemopt, this.selected);
                                itemopt.data('idx', contador);
                                itemopt.data('father',idfather);
                                if ( typeof $(this).attr('order') !== "undefined" && $(this).attr('order')) {
                                	itemopt.data('order', $(this).attr('order'));
                                }
                                if(this.selected){
                                	 var items = gral.selectedList.find('li'), comparator = gral.options.orderComparator;
                                	 var succ = null;
                                	var i = itemopt.data('order'), direction = comparator(itemopt, $(items[i]));
                                                
                                                    // TODO: test needed for dynamic list populating
                                                    if ( direction ) {
                                                            while (i>=0 && i<items.length) {
                                                                    direction > 0 ? i++ : i--;
                                                                    if ( direction != comparator(itemopt, $(items[i])) ) {
                                                                            // going up, go back one item down, otherwise leave as is
                                                                            succ = items[direction > 0 ? i : i+1];
                                                                            break;
                                                                    }
                                                            }
                                                    } else {
                                                            succ = items[i];
                                                    }
                                                    var availableItem = itemopt;
                                                    succ ? availableItem.insertBefore($(succ)).show() : availableItem.appendTo(gral.selectedList).show();
                                                
                                }
                                else{
                                	itemopt.appendTo(gral.availableList).show();
                                }
                                contador++;
                            });
                        }   

                    });
                    that._updateCount();
                }
                else{
                    this._populateLists(this.element.find('option'));
                }
		// make selection sortable
		if (this.options.sortable) {
			this.selectedList.sortable({
				placeholder: 'ui-state-highlight',
				axis: 'y',
				update: function(event, ui) {
					// apply the new sort order to the original selectbox
					that.selectedList.find('li').each(function() {
						if ($(this).data('optionLink'))
							$(this).data('optionLink').remove().appendTo(that.element);
					});
				},
				receive: function(event, ui) {
					ui.item.data('optionLink').attr('selected', true);
					// increment count
					that.count += 1;
					that._updateCount();
					// workaround, because there's no way to reference 
					// the new element, see http://dev.jqueryui.com/ticket/4303
					that.selectedList.children('.ui-draggable').each(function() {
						$(this).removeClass('ui-draggable');
						$(this).data('optionLink', ui.item.data('optionLink'));
						$(this).data('idx', ui.item.data('idx'));
					     if ( typeof $(this).attr('order') !== "undefined" && $(this).attr('order')) {
							 $(this).data('order', $(this).attr('order'));
                         }
						that._applyItemState($(this), true);
					});
			
					// workaround according to http://dev.jqueryui.com/ticket/4088
					setTimeout(function() {ui.item.remove();}, 1);
				}
			});
		}
		// set up livesearch
		if (this.options.searchable) {
			this._registerSearchEvents(this.availableContainer.find('input.search'));
		} else {
			$('.search').hide();
		}
		
		// batch actions
		this.container.find(".remove-all").click(function() {
                    var contador=0;    
                    that.selectedList.children('.ui-element').remove();
                    that.availableList.children('.ui-element').remove();
                    this.count = 0;
                    if( that.element.find('optgroup').length ) { 
                        // init lists
                        var children = that.element.children();
                        var gral=that;

                        children.each( function() { 
                            
                            if (this.tagName == 'OPTION') {

                                var item = gral._getOptionNode(this,false);
                                if (this.selected) gral.count += 1;
                                item.data('idx', contador);
                                item.data('father', null);
                                item.data('optionLink').removeAttr('selected');
                                if ( typeof $(this).attr('order') !== "undefined" && $(this).attr('order')) {
                                	item.data('order', $(this).attr('order'));
                                }
                                item.appendTo(gral.availableList).show();
                                gral._applyItemState(item, this.selected);
                                contador++;
                            } else {
                                var item2 =  gral._getGroupOptionNode(this);
                                 if (this.selected) gral.count += 1;
                                item2.data('idx', contador);
                                item2.data('father', null);
                                item2.data('optionLink').removeAttr('selected');
                                if ( typeof $(this).attr('order') !== "undefined" && $(this).attr('order')) {
                                	item2.data('order', $(this).attr('order'));
                                }
                                item2.appendTo(gral.availableList).show();
                                gral._applyItemState(item2, this.selected);
                                var itemfather=contador;
                                contador++;
                                var childrenOptGroup=$(this).children();

                                childrenOptGroup.each( function() {    
                                    $(this).text(' '+$(this).text());
                                    var itemopt=gral._getOptionNode($(this),true);
                                    if ($(this).selected) gral.count += 1;
                                    gral._applyItemState(itemopt, $(this).selected);
                                    itemopt.data('idx', contador);
                                    itemopt.data('father', itemfather);
                                    itemopt.data('optionLink').removeAttr('selected');
                                    if ( typeof $(this).attr('order') !== "undefined" && $(this).attr('order')) {
                                    	itemopt.data('order', $(this).attr('order'));
                                    }
                                    itemopt.appendTo(gral.availableList).show();
                                    contador++;
                                });
                            }
                       
                        });
                         that.count = 0;
                        // update count
                        that._updateCount();
                        that.availableContainer.find('input.search').val("");
                }
                else{
                    that._populateLists(that.element.find('option').removeAttr('selected'));
                }
		return false;
            });
            this.container.find(".add-all").click(function() {
                if( that.element.find('optgroup').length ) { 
                    var children = that.availableList.find(("li:not(:hidden):data('class',option),li:not(:hidden):data('class',optgroup)"));
                    var total=0;
                    
                    children.each(function(i) {
                        that._setSelected($(this),true);
                        total++;
                    });
                    that.count += total;
                    that._updateCount();
                    return false;
               }
               else{
                   var options = that.element.find('option').not(":selected");
                    if (that.availableList.children('li:hidden').length > 1) {
                            that.availableList.children('li').each(function(i) {
                                    if ($(this).is(":visible")) $(options[i-1]).attr('selected', 'selected'); 
                            });
                    } else {
                            options.attr('selected', 'selected');
                    }
                    that._populateLists(that.element.find('option'));
                    return false;
               }
            });
	},
	destroy: function() {
		this.element.show();
		this.container.remove();

		$.Widget.prototype.destroy.apply(this, arguments);
	},
	_populateLists: function(options) {
		this.selectedList.children('.ui-element').remove();
		this.availableList.children('.ui-element').remove();
		this.count = 0;

		var that = this;
		var items = $(options.map(function(i) {
                 
                
	      var item = that._getOptionNode(this,false).appendTo(this.selected ? that.selectedList : that.availableList).show();

			if (this.selected) that.count += 1;
			that._applyItemState(item, this.selected);
			item.data('idx', i);
			return item[0];
    }));
		
		// update count
		this._updateCount();
		that._filter.apply(this.availableContainer.find('input.search'), [that.availableList]);
  },
	_updateCount: function() {
		this.selectedContainer.find('span.count').text(this.count+" "+$.ui.multiselect.locale.itemsCount);
	},
	_getOptionNode: function(option,optgroup) {
		option = $(option);
                var clase="ui-state-default ui-element";
                if(optgroup){
                    clase+=" optgroup";
                }
		var node = $('<li class="'+clase+'" title="'+option.text()+'"><span class="ui-icon"/>'+option.text()+'<a href="#" class="action"><span class="ui-corner-all ui-icon"/></a></li>').hide();
		node.data('optionLink', option);
                if(optgroup){
                    node.data('class', 'optgroup');
                }
                else{
                    node.data('class', 'option');
                }
		return node;
	},
        _getGroupOptionNode: function(option) {
		option = $(option);
		var node = $('<li class="ui-state-default ui-element grouped" title="'+option.attr("label")+'"><span class="ui-icon"/>'+option.attr("label")+'</li>').hide();
                node.data('optionLink', option);
                node.data('class', 'grouped');
                return node;
	},
	// clones an item with associated data
	// didn't find a smarter away around this
	_cloneWithData: function(clonee) {
		var clone = clonee.clone(false,false);
		clone.data('optionLink', clonee.data('optionLink'));
		clone.data('idx', clonee.data('idx'));
        clone.data('class', clonee.data('class'));
        clone.data('father', clonee.data('father'));
        clone.data('order', clonee.data('order'));
		return clone;
	},
	_setSelected: function(item, selected) {
		item.data('optionLink').attr('selected', selected);

		if (selected) {
			var selectedItem = this._cloneWithData(item);
                        $(item).remove();
                        selectedItem.appendTo(this.selectedList);
//			item[this.options.hide](this.options.animated, function() {$(this).remove();});
//			selectedItem.appendTo(this.selectedList).hide()[this.options.show](this.options.animated);			
			this._applyItemState(selectedItem, true);
			
			
			return selectedItem;
		} else {
			
			// look for successor based on initial option index
			var items = this.availableList.find('li'), comparator = this.options.nodeComparator;
			var succ = null, i = item.data('idx'), direction = comparator(item, $(items[i]));
                        
                        if(item.data('class')!='optgroup'){
                            // TODO: test needed for dynamic list populating
                            if ( direction ) {
                                    while (i>=0 && i<items.length) {
                                            direction > 0 ? i++ : i--;
                                            if ( direction != comparator(item, $(items[i])) ) {
                                                    // going up, go back one item down, otherwise leave as is
                                                    succ = items[direction > 0 ? i : i+1];
                                                    break;
                                            }
                                    }
                            } else {
                                    succ = items[i];
                            }
                            var availableItem = this._cloneWithData(item);
                            succ ? availableItem.insertBefore($(succ)) : availableItem.appendTo(this.availableList);
                        }
                        else{
                            availableItem = this._cloneWithData(item);
                             if(item.data('father')>=0){
                                 var lastitem=null;
                                 var fatherPosition=this.availableList.find("li:data('idx',"+item.data('father')+")").index();
                                 j=fatherPosition;
                                 while($(items[j]).data('idx')<=i){
                                     lastitem=items[j]; 
                                     j++;
                                 }
                                 availableItem.insertAfter($(lastitem));
                             }
                        }
                        
//			item[this.options.hide](this.options.animated, function() {$(this).remove();});
			$(item).remove();
//                        availableItem.appendTo(this.selectedList);
			this._applyItemState(availableItem, false);
			return availableItem;
		}
	},
	_applyItemState: function(item, selected) {
		if (selected) {
			if (this.options.sortable)
				item.children('span').addClass('ui-icon-arrowthick-2-n-s').removeClass('ui-helper-hidden').addClass('ui-icon');
			else
				item.children('span').removeClass('ui-icon-arrowthick-2-n-s').addClass('ui-helper-hidden').removeClass('ui-icon');
			item.find('a.action span').addClass('ui-icon-minus').removeClass('ui-icon-plus');
			this._registerRemoveEvents(item.find('a.action'));
			
		} else {
			item.children('span').removeClass('ui-icon-arrowthick-2-n-s').addClass('ui-helper-hidden').removeClass('ui-icon');
			item.find('a.action span').addClass('ui-icon-plus').removeClass('ui-icon-minus');
			this._registerAddEvents(item.find('a.action'));
		}
		
		this._registerDoubleClickEvents(item);
		this._registerHoverEvents(item);
	},
	// taken from John Resig's liveUpdate script
	_filter: function(list) {
		var input = $(this);
		var rows = list.children('li'),
			cache = rows.map(function(){
				
				return $(this).text().toLowerCase();
			});
		
		var term = $.trim(input.val().toLowerCase()), scores = [];
		
		if (!term) {
			rows.show();
		} else {
			rows.hide();

			cache.each(function(i) {
				if (this.indexOf(term)>-1) {scores.push(i);}
			});

			$.each(scores, function() {
				$(rows[this]).show();
			});
		}
	},
	_registerDoubleClickEvents: function(elements) {
		if (!this.options.doubleClickable) return;
		elements.dblclick(function() {
			elements.find('a.action').click();
		});
	},
	_registerHoverEvents: function(elements) {
		elements.removeClass('ui-state-hover');
		elements.mouseover(function() {
			$(this).addClass('ui-state-hover');
		});
		elements.mouseout(function() {
			$(this).removeClass('ui-state-hover');
		});
	},
	_registerAddEvents: function(elements) {
		var that = this;
		elements.unbind("click").click(function() {
			
			that.count += 1;
			that._updateCount();
                        var item = that._setSelected($(this).parent(), true);

			return false;
		});
		
		// make draggable
		if (this.options.sortable) {
  		elements.each(function() {
  			$(this).parent().draggable({
                        connectToSortable: that.selectedList,
  				helper: function() {
  					var selectedItem = that._cloneWithData($(this)).width($(this).width() - 50);
  					selectedItem.width($(this).width());
  					return selectedItem;
  				},
  				appendTo: that.container,
  				containment: that.container,
  				revert: 'invalid'
  	    });
  		});		  
		}
	},
	_registerRemoveEvents: function(elements) {
		var that = this;
		elements.click(function() {
			that._setSelected($(this).parent(), false);
			that.count -= 1;
			that._updateCount();
			return false;
		});
 	},
	_registerSearchEvents: function(input) {
		var that = this;

		input.focus(function() {
			$(this).addClass('ui-state-active');
		})
		.blur(function() {
			$(this).removeClass('ui-state-active');
		})
		.keypress(function(e) {
			if (e.keyCode == 13)
				return false;
		})
		.keyup(function() {
			that._filter.apply(this, [that.availableList]);
		});
	}
});
		
$.extend($.ui.multiselect, {
	locale: {
		addAll:'Add all',
		removeAll:'Remove all',
		itemsCount:'items selected'
	}
});


})(jQuery);

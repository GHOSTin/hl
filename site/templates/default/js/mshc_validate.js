(function($) {
    $.extend($.fn, {
        verify: function(options){
            if ( !this.length ) {
                if ( options && options.debug && window.console ) {
                    console.warn( "Ничего не выбрано, невозможно проверить." );
                }
                return;
            }

            var validator = $.data( this[0], "validator" );
            if ( validator ) {
                return validator;
            }

            validator = new $.validator( options, this );
            $.data( this[0], "validator", validator );

            return validator;
        }
    });

    $.validator = function( options, elem ) {
        this.settings = $.extend( true, {}, $.validator.defaults, options );
        this.currentElem = elem;
        this.init();
    };

    $.extend($.validator, {
        defaults: {
            messages: {},
            groups: {},
            rules: {},
            focusInvalid: true,
            errorContainer: $([]),
            errorLabelContainer: $([]),
            onsubmit: true,
            ignore: ":hidden",
            ignoreTitle: false,
            onfocusin: function( element, event ) {
                console.log('onfocusin!');
//                this.lastActive = element;
//
//                if ( this.settings.focusCleanup && !this.blockFocusCleanup ) {
//                    this.addWrapper(this.errorsFor(element)).hide();
//                }
            },
            onfocusout: function( element, event ) {
//                if ( !this.checkable(element) && (element.name in this.submitted || !this.optional(element)) ) {
//                    this.element(element);
//                }
            },
            onkeyup: function( element, event ) {
//                if ( event.which === 9 && this.elementValue(element) === "" ) {
//                    return;
//                } else if ( element.name in this.submitted || element === this.lastElement ) {
//                    this.element(element);
//                }
                console.log('keyup!');
            },
            onclick: function( element, event ) {
//                if ( element.name in this.submitted ) {
//                    this.element(element);
//                }
//                else if ( element.parentNode.name in this.submitted ) {
//                    this.element(element.parentNode);
//                }
            }
        },
        setDefaults: function( settings ) {
            $.extend( $.validator.defaults, settings );
        },
        prototype: {
            init: function(){
                var self = this;
                var rules = self.settings.rules;
                $.each(rules, function( key, value ) {
                    rules[key] = $.validator.normalizeRule(value);
                });

                $(this.currentElem)
                    .on('focusin focusout keyup', $(this), function(event){
                        if($(this).is(":text, [type='password'], [type='file'], select, textarea, " +
                            "[type='number'], [type='search'] ,[type='tel'], [type='url'], " +
                            "[type='email'], [type='datetime'], [type='date'], [type='month'], " +
                            "[type='week'], [type='time'], [type='datetime-local'], " +
                            "[type='range'], [type='color'] ")){
                            var eventType = "on" + event.type.replace(/^validate/, "");
                            if ( self.settings[eventType] ) {
                                self.settings[eventType].call(self, this[0], event);
                            }
                        }
                    }).on('click', $(this), function(event){
                        if($(this).is("[type='radio'], [type='checkbox'], select, option")){
                            var eventType = "on" + event.type.replace(/^validate/, "");
                            if ( self.settings[eventType] ) {
                                self.settings[eventType].call(self, this[0], event);
                            }
                        }
                    });
            }

        },
        normalizeRule: function( data ) {
            if ( typeof data === "string" ) {
                var transformed = {};
                $.each(data.split(/\s/), function() {
                    transformed[this] = true;
                });
                data = transformed;
            }
            return data;
        }
    });
}(jQuery));

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
            rules: {},
            ignore: ":hidden",
            onfocusin: function( element, event ) {
                console.log('onfocusin!');
            },
            onfocusout: function( element, event ) {
            },
            onkeyup: function( element, event ) {
                if ( event.which === 9 && this.elementValue(element) === "" ) {
                    return;
                } else {

                }
                console.log('keyup!');
            },
            onclick: function( element, event ) {
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
            },
            elementValue: function( element ) {
                var type = $(element).attr("type"),
                    val = $(element).val();

                if ( type === "radio" || type === "checkbox" ) {
                    return $("input[name='" + $(element).attr("name") + "']:checked").val();
                }

                if ( typeof val === "string" ) {
                    return val.replace(/\r/g, "");
                }
                return val;
            },
            check: function( element ) {
                element = this.validationTargetFor( this.clean( element ) );
                return true;
            },
            validationTargetFor: function( element ) {
                // if radio/checkbox, validate first element in group instead
                if ( this.checkable(element) ) {
                    element = $(element).not(this.settings.ignore)[0];
                }
                return element;
            },
            checkable: function( element ) {
                return (/radio|checkbox/i).test(element.type);
            },
            clean: function( selector ) {
                return $(selector)[0];
            }
        }
    });
}(jQuery));

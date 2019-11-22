const getCircularReplacer = () => {
    const seen = new WeakSet();
    return (key, value) => {
        if (typeof value === "object" && value !== null) {
            if (seen.has(value)) {
                return;
            }
            seen.add(value);
        }
        return value;
    };
};

(function( $ ) {
	'use strict';

	// https://learn.jquery.com/code-organization/concepts/
	var exopiteFrontendNotifications = {
		init: function( settings ) {
            exopiteFrontendNotifications.displayMessages( efn_messages );
            if (efn_settings.ajax) {
                setTimeout(exopiteFrontendNotifications.checkAJAX, efn_settings.interval);
            }

        },
        checkAJAX: function () {

            $.ajax({
                cache: false,
                type: "POST",
                url: efn_settings.ajaxurl,
                data: {
                    'action': 'efn_get_notifications',
                    'post_id': efn_settings.post_id,
                    'post_slug': efn_settings.post_slug,
                },
                success: function( response ){

                    // console.log( 'response: ' + JSON.stringify( response ) );
                    var messages = JSON.parse(response);
                    exopiteFrontendNotifications.displayMessages(messages);

                },
                error: function( xhr, status, error ) {
                    console.log( 'Status: ' + xhr.status );
                    console.log( 'Error: ' + xhr.responseText );
                },
                complete: function() {
                    // Schedule the next request when the current one's complete
                    setTimeout(exopiteFrontendNotifications.checkAJAX, efn_settings.interval );
                }
            });

        },
        displayMessages: function ( messages ) {
			var thisClass = this;

            $.each(messages, function (k, message) {

                var args = {
                    pauseDelayOnHover: message.pauseDelayOnHover,
                    continueDelayOnInactiveTab: message.continueDelayOnInactiveTab,
                    delay: message.delay,
                    closable: message.closable,
                    closeOnClick: message.closeOnClick,
                    width: message.width,
                    messageHeight: message.messageHeight,
                    sound: message.sound,
                    position: message.position,
                    onClickUrl: message.onClickUrl,
                    showAfterPrevious: message.showAfterPrevious,
                    position: message.position,
                    img: message.img,
                    title: message.title,
                    msg: message.msg,
                    showClass: message.showClass,
                    hideClass: message.hideClass,
                    icon: message.icon,
                    iconSource: message.iconSource,
                    size: message.size,
                    // onClick: function(e){
                    //     // console.log('e: ' + JSON.stringify(e, getCircularReplacer()));
                    //     // console.log('me: ' + JSON.stringify(this, getCircularReplacer()));
                    //     // console.log('el: ' + JSON.stringify(this.$el.attr('class'), getCircularReplacer()));
                    // },
                    // onHide: function(){
                    //     // console.log('me: ' + JSON.stringify(this, getCircularReplacer()));
                    //     // console.log('el: ' + JSON.stringify(this.$el.attr('class'), getCircularReplacer()));
                    // },
                    // onAutoDismiss: function (){
                    //     // console.log('me: ' + JSON.stringify(this, getCircularReplacer()));
                    //     // console.log('el: ' + JSON.stringify(this.$el.attr('class'), getCircularReplacer()));
                    // },
                    onDismiss: function (e){
                        if ( ! efn_settings.ajax ) return;
                        try {
                            if (typeof (this.$options) !== "undefined" && typeof this.$options.callback !== "undefined" && this.$options.callback !== null) {
                                $.ajax({
                                    cache: false,
                                    type: "POST",
                                    url: efn_settings.ajaxurl,
                                    data: {
                                        'action': this.$options.callback,
                                        'element': this.$options,
                                    },
                                    success: function( response ){

                                        console.log( 'response: ' + JSON.stringify( response ) );

                                    },
                                    error: function( xhr, status, error ) {
                                        console.log( 'Status: ' + xhr.status );
                                        console.log( 'Error: ' + xhr.responseText );
                                    },
                                });


                            }
                        } catch(error) {}
                    },

                }

                if (typeof message.id !== "undefined" && message.id !== null) {
                    args.id = message.id;
                }

                if (typeof message.callback !== "undefined" && message.callback !== null) {
                    args.callback = message.callback;
                }

                Lobibox.notify(message.type, args);

			});

		},
	};

	$( document ).ready( exopiteFrontendNotifications.init );

})( jQuery );

MyApp = new Backbone.Marionette.Application();

var ModalRegion = Backbone.Marionette.Region.extend({
    el: "#modal",

    constructor: function(){
        _.bindAll(this);
        Backbone.Marionette.Region.prototype.constructor.apply(this, arguments);
        this.on("show", this.showModal, this);
    },

    getEl: function(selector){
        var $el = $(selector);
        $el.on("hidden", this.close);
        return $el;
    },

    showModal: function(view){
        view.on("close", this.hideModal, this);
        this.$el.modal('show');
    },

    hideModal: function(){
        this.$el.modal('hide');
    }
});

MyApp.addRegions({
    content: '.profile-content',
    modal: ModalRegion
});

MyApp.ProfileApp = function(){
    'use strict';

    var ProfileApp = {};


    var ChangePasswordView = Backbone.Marionette.ItemView.extend({
        events: {
            'click .close_dialog': 'close_dialog',
            'click .update_password': 'update_password'
        },
        template: Twig.twig({
            href: '/templates/profile/build_edit_password.tpl',
            async: false
        }),
        className: 'modal-dialog change_password',
        render: function() {
            $(this.el).html(this.template.render({"user": this.model.toJSON()}));
        },
        close_dialog: function(){
            MyApp.modal.hideModal();
        },
        update_password: function(){
            var password = $('.dialog-new_password').val();
            var confirm_password = $('.dialog-confirm_password').val();
            this.model.save({
                    wait: true,
                    url: 'update_password?new_password='+password+'&confirm_password='+confirm_password,
                    success: function(model, response, options){
                        MyApp.modal.hideModal();
                        MyApp.ProfileApp.layout.render({model: model});
                    },
                    error: function(model, response, options){
                        console.log(response)
                    }
                }
            );
        }
    });

    var ChangeCellphoneView = Backbone.Marionette.ItemView.extend({
        events: {
            'click .close_dialog': 'close_dialog',
            'click .update_cellphone': 'update_cellphone'
        },
        template: Twig.twig({
            href: '/templates/profile/build_edit_cellphone.tpl',
            async: false
        }),
        className: 'modal-dialog change_cellphone',
        render: function() {
            $(this.el).html(this.template.render({"user": this.model.toJSON()}));
        },
        close_dialog: function(){
            MyApp.modal.hideModal();
        },
        update_cellphone: function(){
            var cellphone = $('.dialog-cellphone').val();
            this.model.save({
                cellphone: cellphone.toString()},
                {
                    wait: true,
                    url: 'update_cellphone?cellphone='+cellphone,
                    success: function(model, response, options){
                        MyApp.modal.hideModal();
                        MyApp.ProfileApp.layout.render({model: model});
                    },
                    error: function(model, response, options){
                        console.log(response)
                    }
                }
            );
        }
    });

    var ChangeTelephoneView = Backbone.Marionette.ItemView.extend({
        events: {
            'click .close_dialog': 'close_dialog'
        },
        template: Twig.twig({
            href: '/templates/profile/build_edit_telephone.tpl',
            async: false
        }),
        className: 'modal-dialog change_telephone',
        render: function() {
            $(this.el).html(this.template.render({"user": this.model.toJSON()}));
        },
        close_dialog: function(){
            MyApp.modal.hideModal();
        }
    });

    var Layout = Backbone.Marionette.Layout.extend({
        events: {
            'click .get_dialog_edit_password': 'change_password',
            'click .get_dialog_edit_cellphone': 'change_cellphone',
            'click .get_dialog_edit_telephone': 'change_telephone'
        },
        template: Twig.twig({
            id: "userinfo",
            href: "/templates/profile/build_user_info.tpl",
            async: false
        }),
        render: function() {
            $(this.el).html(this.template.render({"user": this.model.toJSON()}));
        },
        change_password: function(){
            var EditPassword = new ChangePasswordView({"model": this.model});
            MyApp.modal.show(EditPassword);
        },
        change_cellphone: function(){
            var EditCellphone = new ChangeCellphoneView({"model": this.model});
            MyApp.modal.show(EditCellphone);
        },
        change_telephone: function(){
            var EditTelephone = new ChangeTelephoneView({"model": this.model});
            MyApp.modal.show(EditTelephone);
        }
    });

    var Profile = Backbone.Model.extend({
        url: '/profile/get_userinfo',
        initialize: function(){
            this.on('reset', this.initializeLayout({"model": this}), this);
            this.fetch({
                reset: true,
                success: function(model, response) {
                    model.initializeLayout({"model": model})
                },
                error: function(model, response){
                    throw new Error('Не могу получить модель пользователя');
                }
            });
        },
        initializeLayout: function(options){
            ProfileApp.layout = new Layout(options);
            MyApp.content.show(ProfileApp.layout);
        }
    });

    ProfileApp.Profile = new Profile();

    return ProfileApp;
}();

MyApp.on("initialize:after", function(options){
    if (Backbone.history){
        Backbone.history.start();
    }
});

$(document).ready(function(){
    MyApp.start();
});
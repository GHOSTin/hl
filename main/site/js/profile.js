MyApp.addRegions({
    content: '.profile-content',
});

MyApp.AlertView = Backbone.View.extend({

    tagName: "div",

    className: "alert fade alert-dismissable",

    template: _.template("<a href='#' data-dismiss='alert' aria-hidden='true' class='close'>&times;</a><strong>{{ title }}</strong>{{ message }}"),

    initialize: function (options) {

        _.bindAll(this, "render", "remove");

        if (options) {
            this.alert = options.alert || "info";
            this.title = options.title || "";
            this.message = options.message || "";
            this.fixed = options.fixed || false;
        }

    },

    render: function () {
        var that = this,
            output = this.template({ title: this.title, message: this.message });

        this.$el.addClass("alert-" + this.alert).html(output).alert();

        if (this.fixed) {
            this.$el.addClass("fixed");
        }

        window.setTimeout(function () {
            that.$el.addClass("in");
        }, 20);

        return this;
    },

    remove: function () {
        var that = this;

        this.$el.removeClass("in");

        window.setTimeout(function () {
            that.$el.remove();
        }, 1000);
    }
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
            if(_.isEqual(password, confirm_password)){
                this.model.sync(
                    "read"
                    , this
                    ,{
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
                )
            }
            else {
                var alert = new MyApp.AlertView({
                    alert: 'danger',
                    message: 'Введенные пароли не совпадают'
                });
                $('.modal-body').prepend(alert.render().el);
            }
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
        onShow: function() {
            $('.dialog-cellphone').inputmask("mask", {"mask": "(999) 999-99-99"});
        },
        close_dialog: function(){
            MyApp.modal.hideModal();
        },
        update_cellphone: function(){
            var cellphone = $('.dialog-cellphone').inputmask('unmaskedvalue');
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
            'click .close_dialog': 'close_dialog',
            'click .update_telephone': 'update_telephone'
        },
        template: Twig.twig({
            href: '/templates/profile/build_edit_telephone.tpl',
            async: false
        }),
        className: 'modal-dialog change_telephone',
        render: function() {
            $(this.el).html(this.template.render({"user": this.model.toJSON()}));
        },
        onShow: function() {
            $('.dialog-telephone').inputmask("mask", {"mask": "9{1,3}-99-99"});
        },
        close_dialog: function(){
            MyApp.modal.hideModal();
        },
        update_telephone: function(){
            var telephone = $('.dialog-telephone').inputmask('unmaskedvalue');
            this.model.save({
                telephone: telephone.toString()},
                {
                    wait: true,
                    url: 'update_telephone?telephone='+telephone,
                    success: function(model, response, options){
                        MyApp.modal.hideModal();
                        MyApp.ProfileApp.layout.render({model: model});
                    },
                    error: function(model, response, options){
                        console.log(response);
                    }
            });
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
        validate: function(attrs, options){
            if(/8[0-9]{10, 10}/.test(attrs.cellphone)){
                return 'Телефон указан не верно.'
            }
        },
        initializeLayout: function(options){
            ProfileApp.layout = new Layout(options);
            MyApp.content.show(ProfileApp.layout);
        }
    });

    ProfileApp.Profile = new Profile();

    return ProfileApp;
}();
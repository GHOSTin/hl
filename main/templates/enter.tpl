{% extends "default.tpl" %}

{% block component %}
<div class="col-12 col-sm-6 col-lg-6">
    <form action="/login/" method="post" class="form-horizontal">
        <legend>Вход в систему</legend>
        <fieldset>
            <div class="form-group">
                <label class="col-lg-3 control-label" for="login">Пользователь</label>
                <div class="col-lg-9">
                    <input class="form-control" type="text" id="login" name="login" placeholder="Логин" autofocus/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label" for="password">Пароль</label>
                <div class="col-lg-9">
                    <input class="form-control" type="password" id="password" name="password" placeholder="Пароль"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    <input type="submit" class="btn btn-primary pull-right" value="Войти" />
                </div>
            </div>
        </fieldset>
    </form>
</div>
{% endblock component %}
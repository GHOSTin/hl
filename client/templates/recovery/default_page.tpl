{% extends "public.tpl" %}

{% block content %}
  <div class="passwordBox animated fadeInDown">
    <div class="row">

      <div class="col-md-12">
        <div class="ibox-content">

          <h2 class="font-bold">Востановление пароля</h2>

          <p>
            Новый пароль будет выслан на привязанный к лицевому счету email.
          </p>

          <div class="row">

            <div class="col-lg-12">
              <form class="m-t" role="form" action="/recovery/" method="post">
                <div class="form-group">
                  <label>Введите номер лицевого счета</label>
                  <input name="number" type="text" class="form-control" required="">
                </div>

                <button type="submit" class="btn btn-primary block full-width m-b">Востановить</button>
                <button type="button" class="btn btn-white block full-width m-b" onclick="window.location='/';">Отмена</button>

              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
{% endblock %}

{% block css %}
  <link rel="stylesheet" href="/css/default.css">
{% endblock %}
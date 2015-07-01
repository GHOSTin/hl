{% extends "public.tpl" %}

{% block content %}
<div id="content" class="col-sm-12 full">
  <div class="row">
    <div class="col-md-5">
      Для того чтобы заявка была рассмотрена информация должна быть введена полностью и соответствовать действительности.
      <p>* - поля обязательные для заполнения.</p>
      <form method="post">
        <div class="form-group">
          <label>Адрес*</label>
          <input type="text"  class="form-control" name="address" autofocus required>
        </div>
        <div class="form-group">
          <label>Номер лицевого счета*</label>
          <input type="text"  class="form-control" name="number" required>
        </div>
        <div class="form-group">
          <label>ФИО*</label>
          <input type="text"  class="form-control" name="fio" required>
        </div>
        <div class="form-group">
          <label>Сумма начисления и месяц*</label>
          <input type="text"  class="form-control" name="accruals" required>
        </div>
        <div class="form-group">
          <label>Email*</label>
          <input type="email"  class="form-control" name="email" required>
        </div>
        <div class="form-group">
          <label>Телефон</label>
          <input type="tel"  class="form-control" name="telephone">
        </div>
        <div class="form-group">
          <label>Сотовый</label>
          <input type="tel"  class="form-control" name="cellphone">
        </div>
        <button type="submit" class="btn btn-default">Отправить</button>
        <a href="/" class="btn btn-default">Отменить</a>
      </form>
    </div>
  </div>
</div>
{% endblock content %}

{% block css %}
<link rel="stylesheet" href="/css/default.css">
{% endblock css%}
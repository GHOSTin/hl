{% extends "default.tpl" %}

{% block component %}
<div class="row">
	<div class="col-md-2">
    <ul class="nav nav-pills menu">
      <li>
        <a id="import_accruals">Импорт начислений</a>
      </li>
      <li>
        <a id="import_debt">Импорт задолжености</a>
      </li>
      <li>
        <a id="import_numbers">Импорт лицевых</a>
      </li>
      <li>
        <a id="import_metrs">Импорт счетчиков</a>
      </li>
    </ul>
	</div>
  <div class="col-md-10 import">
  </div>
</div>
{% endblock %}

{% block javascript %}
<script src="/js/query.js"></script>
<script src="/js/backbone.js"></script>
<script src="/js/underscore.js"></script>
<script src="/js/import.js"></script>
<script id="import_accruals_form" type="text/template">
  <form action="/import/load_accruals/" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label>Месяц</label>
      <input type="text" class="form-control date" name="date" required>
    </div>
    <div class="form-group">
      <label>Файл</label>
      <input type="file" class="form-control" name="accruals" required>
    </div>
    <button type="submit" class="btn btn-default">Залить</button>
  </form>
</script>
<script id="import_debt_form" type="text/template">
  <form action="/import/load_debt/" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label>Файл</label>
      <input type="file" class="form-control" name="debt" required>
    </div>
    <button type="submit" class="btn btn-default">Залить</button>
  </form>
</script>
<script id="import_numbers_form" type="text/template">
  <form action="/import/fond/" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label>Файл</label>
      <input type="file" class="form-control" name="numbers" required>
    </div>
    <button type="submit" class="btn btn-default">Залить</button>
  </form>
</script>
<script id="import_metrs_form" type="text/template">
  <form action="/import/meterages/" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label>Месяц</label>
      <input type="text" class="form-control date" name="date" required>
    </div>
    <div class="form-group">
      <label>Файл</label>
      <input type="file" class="form-control" name="metrs" required>
    </div>
    <button type="submit" class="btn btn-default">Залить</button>
  </form>
</script>
{% endblock %}

{% block css %}
<link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css" >
{% endblock %}
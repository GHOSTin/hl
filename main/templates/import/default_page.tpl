{% extends "default.tpl" %}

{% block component %}
<div class="row">
	<div class="col-md-2">
    <ul class="nav nav-pills menu">
      <li>
        <a id="import_accruals">Импорт начислений</a>
      </li>
    </ul>
	</div>
  <div class="col-md-10 import">
  </div>
</div>
{% endblock component %}

{% block javascript %}
<script src="/js/query.js"></script>
<script src="/js/bootstrap-datepicker.js"></script>
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
{% endblock javascript %}

{% block css %}
<link rel="stylesheet" href="/css/datepicker.css" >
{% endblock css %}
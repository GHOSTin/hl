{% extends "ajax.tpl" %}
{% block js %}
    $('.import-form').html(get_hidden_content());
    $('#date').datepicker({format: 'dd.mm.yyyy', language: 'ru'}).on('changeDate', function(){
      $('#date').datepicker('hide');
    });
{% endblock js %}
{% block html %}
<h3>Удаление начислений</h3>
<form role="form" action="/import/delete_accruals">
  <div class="row">
    <div class="form-group col-md-2">
      <label for="date">Месяц</label>
      <input type="month" id="date" class="form-control" value="{{ "now"|date("d.m.Y")}}" name="month">
    </div>
  </div>
  <button type="submit" class="btn btn-danger">Удалить</button>
</form>
{% endblock html %}
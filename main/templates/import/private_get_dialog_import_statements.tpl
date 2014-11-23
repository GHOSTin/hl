{% extends "ajax.tpl" %}
{% block js %}
    $('.import-form').html(get_hidden_content());
    $('#fileupload').fileupload({
        dataType: 'html',
        done: function(e, data){
         init_content(data.result);
        }
    });
    $('#date').datepicker({format: 'dd.mm.yyyy', language: 'ru'}).on('changeDate', function(){
      $('#date').datepicker('hide');
      $('#fileupload').fileupload({
        'url':"/import/import_statements?date=" + $('#date').val()
      });
    });
{% endblock js %}
{% block html %}
<h3>Импорт показаний</h3>
<div class="form-group">
  <label for="date">Месяц</label>
  <input type="month" id="date" class="form-control" value="{{ "now"|date("d.m.Y")}}">
</div>
<div>
    <input id="fileupload" type="file" name="file" data-url="/import/import_statements?date={{ "now"|date("d.m.Y")}}" multiple>
</div>
{% endblock html %}
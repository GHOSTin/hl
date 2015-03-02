{% extends "ajax.tpl" %}

{% block js %}
    show_dialog(get_hidden_content());
    $('.exclude_event').click(function(){
        $.get('exclude_event',{
            id: {{ n2e.get_number().get_id() }},
            event: {{ n2e.get_id() }},
            date: {{ n2e.get_time() }}
        },function(r){
          $('.number[number="{{ n2e.get_number().get_id() }}"] .number-content').remove();
          init_content(r);
          $('.dialog').modal('hide');
        });
    });
{% endblock %}

{% block html %}
<div class="modal-content">
  <div class="modal-header">
    <h3>Исключение события</h3>
  </div>
  <div class="modal-body">
  Исключить событие {{ n2e.get_name() }}?
  </div>
  <div class="modal-footer">
    <div class="btn btn-primary exclude_event ">Исключить</div>
    <div class="btn btn-default close_dialog">Отмена</div>
  </div>
</div>
{% endblock %}
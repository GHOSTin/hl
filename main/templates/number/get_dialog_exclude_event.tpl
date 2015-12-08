{% extends "ajax.tpl" %}

{% set id =  n2e.get_id()|split('-') %}

{% block js %}
show_dialog(get_hidden_content());
$('.exclude_event').click(function(){
    $.ajax('/numbers/{{ id[0] }}/events/{{ id[1] }}/{{ id[2] }}/', {
      type: 'DELETE',
      success: function(response){
        $('.dialog').modal('hide');
        $('.workspace').html(response);
      }
    });
});
{% endblock %}

{% block html %}
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h3>Исключение события</h3>
    </div>
    <div class="modal-body">
    Исключить событие <strong>"{{ n2e.get_name() }}"</strong>?
    </div>
    <div class="modal-footer">
      <div class="btn btn-primary exclude_event ">Исключить</div>
      <div class="btn btn-default close_dialog">Отмена</div>
    </div>
  </div>
</div>
{% endblock %}
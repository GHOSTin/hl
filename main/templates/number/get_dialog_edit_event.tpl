{% extends "ajax.tpl" %}

{% block js %}
show_dialog(get_hidden_content());
$('.edit_event').click(function(){
    $.ajax('/numbers/events/{{ n2e.get_id() }}/', {
      type: 'PUT',
      data:{description: $('.dialog-com').val()},
      dataType: 'json',
      success: function(response){
        $('.dialog').modal('hide');
        var template = Twig.twig({
          href: '/templates/numbers/event.tpl',
          async: false
        });
        $('.event[event_id = {{ n2e.get_id() }}]').replaceWith(template.render(response));
      }
    });
});
{% endblock %}

{% block html %}
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h3>Изменение примечания события</h3>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label>Примечание</label>
        <textarea class="dialog-com form-control" rows="5">{{ n2e.get_description() }}</textarea>
      </div>
    </div>
    <div class="modal-footer">
      <div class="btn btn-primary edit_event">Сохранить</div>
      <div class="btn btn-default close_dialog">Отмена</div>
    </div>
  </div>
</div>
{% endblock %}
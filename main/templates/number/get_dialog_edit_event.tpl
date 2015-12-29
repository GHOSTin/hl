{% extends "ajax.tpl" %}

{% block js %}
show_dialog(get_hidden_content());
$('.edit_event').click(function(){
  $.get('/numbers/events/{{ n2e.get_id() }}/')
    .done(function(res){
      var model = res.event;
      model.description = $('.dialog-com').val();
      $.ajax('/numbers/events/{{ n2e.get_id() }}/', {
        type: 'PUT',
        data: model,
        dataType: 'json',
        success: function (res) {
          $('.dialog').modal('hide');
          var template = Twig.twig({
            href: '/templates/numbers/event.tpl',
            async: false
          });
          $('.event[event_id = {{ n2e.get_id() }}]').replaceWith(template.render(res));
        }
      });
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
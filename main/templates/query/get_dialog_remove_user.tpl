{% extends "ajax.tpl" %}

{% block js %}
	show_dialog(get_hidden_content());
	$('.remove_user').click(function(){
		$.get('remove_user',{
			id: {{ query.get_id() }},
			user_id: {{ user.get_id() }},
			type: '{{ type }}'
			},function(r){
				init_content(r);
				$('.query[query_id = {{ query.get_id() }}] .query-users-{{ type }} li[user={{ user.get_id() }}]').remove();
				$('.dialog').modal('hide');
			});
	});
{% endblock js %}

{% block html %}
<div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
          <h3>Удаление пользователя</h3>
      </div>
    <div class="modal-body">
      Удалить из заявки пользователя "{{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }}"?
    </div>
    <div class="modal-footer">
      <div class="btn btn-primary remove_user">Сохранить</div>
      <div class="btn btn-default close_dialog">Отмена</div>
    </div>
  </div>
</div>
{% endblock html %}
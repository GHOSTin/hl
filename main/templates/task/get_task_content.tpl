{% extends "ajax.tpl" %}

{% block html %}
  {% include 'task/task_content.tpl' with {'task': task} %}
{% endblock %}

{% block js %}
  $('#task_content').find('section').html(get_hidden_content());

  $(document).off('click.EditTask');
  $(document).on('click.EditTask', '#task_edit', function(){
    var link = location.hash.replace('#', '');
    if(link)
      $.get('edit_task_content', {
        id: $('div#task').attr('data-id')
      },function(r) {
        init_content(r);
      });
  });
  {% if task.get_status() != 'close' %}
  $(document).off('click.Comment')
    .off('click.CloseTask');
  $(document).on('click.CloseTask', '#get_dialog_close_task', function(){
    $.get('get_dialog_close_task', {
      id: $('div#task').attr('data-id')
    },function(r){
      init_content(r);
    });
  });
  $(document).on('click.Comment', '.send_comment', function(){
    if($('#comment_message').val() != '')
      $.get('send_task_comment', {
        id: $('div#task').attr('data-id'),
        message: $('#comment_message').val()
      },function(r){
        init_content(r);
      });
  });
  {% endif %}
{% endblock %}

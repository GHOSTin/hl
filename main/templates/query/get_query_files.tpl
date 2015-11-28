{% extends "ajax.tpl" %}

{% block js %}
$('.query[query_id = {{ query.get_id() }}]').find('.query-files .ibox-content').append(get_hidden_content());
$('#fileupload{{ query.get_id() }}').fileupload({
    dataType: 'html',
    type: 'POST',
    url: '/queries/{{ query.get_id() }}/files/',
    done: function (e, data) {
      $('.query[query_id = {{ query.get_id() }}] .files').html(data.result);
    }
});
{% endblock %}

{% block html %}
<ul class="list-unstyled">
{% if query.get_status() in ['open', 'working', 'reopen'] %}
  <li>
    <input id="fileupload{{ query.get_id() }}" type="file" name="file">
  </li>
{% endif %}
  <li>
    <ul class="files">{% include 'query/query_files.tpl'%}</ul>
  </li>
</ul>
{% endblock %}
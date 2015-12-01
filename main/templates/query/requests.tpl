{% if user.check_access('queries/analize_request') %}
<div class="ibox-content m-b">
  <button type="button" class="close close_request_view" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4>Запросы из личного кабинета</h4>
  {% if number_requests is not empty %}
  <ul>
  {% for request in number_requests %}
    <li class="request" time="{{ request.get_time() }}" number="{{ request.get_number().get_id() }}">
      <span>{{ request.get_message() }}</span><a class="get_dialog_create_query_from_request"> cоздать заявку</a> или <a class="get_dialog_abort_query_from_request">отвергнуть запрос</a>
    </li>
  {% endfor %}
  </ul>
  {% endif %}
</div>
{% endif %}
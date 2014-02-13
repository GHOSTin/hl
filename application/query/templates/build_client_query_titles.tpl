{% if client_queries is not empty %}
  <div class="col-md-12 alert alert-warning">
    <h4>Новые заявки из личного кабинета</h4>
    <ul>
    {% for query in client_queries %}
    	<li class="client_query" time="{{ query.get_time() }}" number_id="{{ query.get_number_id() }}">
        {{ query.get_time()|date('h:i d.m.Y') }}:
        <p>{{ query.get_text() }}<br><a class="get_dialog_accept_client_query">принять</a> или <a class="get_dialog_cancel_client_query">отклонить</a></p>
    	</li>
    {% endfor %}
    </ul>
  </div>
{% endif %}
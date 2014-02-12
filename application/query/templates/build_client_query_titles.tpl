{% if client_queries is not empty %}
  <div class="col-md-12 alert alert-warning">
    <h4>Новые заявки из личного кабинета</h4>
    <ul>
    {% for query in client_queries %}
    	<li>
        {{ query.get_time()|date('h:i d.m.Y') }}:
        <p>{{ query.get_text() }}<br><a>принять</a> или <a>отклонить</a></p>
    	</li>
    {% endfor %}
    </ul>
  </div>
{% endif %}
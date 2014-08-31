{% extends "default.tpl" %}
{% set metrics = response.metrics %}
{% block component %}
  <div class="row">
    <div class="col-md-2">
        <button class="btn btn-default remove_metrics">Удалить выделенные</button>
    </div>
    <div class="col-md-10">
      <form id="metrics">
        <table class="table table-hover">
          <thead>
            <tr>
              <th><input type="checkbox" id="select-all"></th>
              <th>Адрес</th>
              <th>Показания</th>
            </tr>
          </thead>
          <tbody>
            {% for metric in metrics %}
              <tr>
                <td><input type="checkbox" name="metric[]" value="{{ metric.get_id() }}" id="{{ metric.get_id() }}"></td>
                <td>{{ metric.get_address() }}</td>
                <td>{{ metric.get_metrics()|nl2br }}</td>
              </tr>
            {% endfor %}
          </tbody>
        </table>
      </form>
    </div>
  </div>
{% endblock%}
{% block javascript %}
  <script src="/js/metrics.js"></script>
{% endblock%}
{% block css %}
{% endblock css %}
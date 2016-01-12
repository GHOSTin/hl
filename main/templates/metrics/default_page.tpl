{% extends "default.tpl" %}

{% block component %}
  <div class="row">
    <div class="col-md-2">
        <p><button class="btn btn-default archive_metrics">Перенести в архив</button></p>
        <p><a href="/metrics/archive/" class="btn btn-default">Перейти в архив</a></p>
    </div>
    <div class="col-md-10">
      <form id="metrics">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>
                <div class="i-checks">
                  <label>
                    <input type="checkbox" id="select-all">
                  </label>
                </div>
              </th>
              <th>Дата</th>
              <th>Адрес</th>
              <th>Показания</th>
            </tr>
          </thead>
          <tbody>
          {% for metric in metrics %}
            <tr>
              <td>
                  <label>
                    <input class="i-checks check" type="checkbox" name="metric[]" value="{{ metric.get_id() }}" id="{{ metric.get_id() }}">
                  </label>
              </td>
              <td>{{ metric.get_time()|date('Y.m.d H:i:s') }}</td>
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

{% block css %}
  <link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css" >
  <link rel="stylesheet" href="/css/plugins/iCheck/custom.css" >
{% endblock css %}

{% block javascript %}
  <script src="/js/metrics.js"></script>
{% endblock%}
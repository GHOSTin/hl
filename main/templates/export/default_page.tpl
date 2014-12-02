{% extends "default.tpl" %}

{% block component %}
    <div class="row">
      <div class="col-md-3">
          <h4>Виды экспорта:</h4>
          <ul class="list-unstyled">
              <li class="get_dialog_export_numbers"><a href="#">Экспорт жилфонда</a></li>
          </ul>
      </div>
      <div class="col-md-9 export-form"></div>
    </div>
{% endblock component %}

{% block javascript %}
  <script src="/js/export.js"></script>
{% endblock javascript %}
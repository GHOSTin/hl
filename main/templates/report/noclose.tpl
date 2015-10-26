{% extends "default.tpl" %}

{% set statuses = {'open':'Открытая', 'working':'В работе',  'close': 'Закрытая', 'reopen':'Переоткрытая'} %}

{% block javascript %}
<script src="/js/jquery.tablesorter.js"></script>
<script>
$(document).ready(function(){
  $(function(){
    $(".tab1").tablesorter();
  });
  $(function(){
    $(".tab2").tablesorter();
  });
});
</script>
{% endblock %}

{% block component %}
<div class="row">
  <div class="col-md-12">
  <h2>Отчет c {{ params.time_begin|date("d.m.Y") }} по {{ params.time_end|date("d.m.Y") }}</h2>
<table class="table table-bordered table-hover table-condensed tab2">
  <caption>Отчет в разрезе участков</caption>
  <thead>
    <th>Участок</th>
    <th>Заявки</th>
    {% for wtype in worktypes %}
    <th>{{ wtype.get_name() }}</th>
    {% endfor %}
  </thead>
  <tbody>
{% for department, inf in stats.departments %}
    <tr>
      <td>{{ department }}</td>
      <td>{{ inf.stat.open + inf.stat.working + inf.stat.reopen }}</td>
    {% for wtype in worktypes %}
      <td>{{ inf.types[wtype.get_name()] }}</td>
    {% endfor %}
    </tr>
{% endfor %}
  </tbody>
</table>

<table class="table table-bordered table-hover tab1">
<caption>Отчет в разрезе домов</caption>
  <thead>
    <th>Дом</th>
    <th>Участок</th>
    <th>Заявки</th>
    {% for wtype in worktypes %}
    <th>{{ wtype.get_name() }}</th>
    {% endfor %}
  </thead>
  <tbody>
{% for department, inf in stats.departments %}
    {% for house, house_inf in inf.houses %}
      <tr>
        <td>{{ house }}</td>
        <td>{{ department }}</td>
        <td>{{ house_inf.stat.open + house_inf.stat.working + house_inf.stat.reopen }}</td>
      {% for wtype in worktypes %}
        <td>{{ house_inf.types[wtype.get_name()] }}</td>
      {% endfor %}
      </tr>
    {% endfor %}

{% endfor %}
      </tbody>
    </table>
  </div>
</div>
{% endblock component %}
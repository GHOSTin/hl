{% for outage in outages %}
<li class="outage" outage="{{ outage.get_id() }}">{% include "outage/outage.tpl" %}</li>
{% endfor %}
<div class="row-fluid">
    <div class="span4">
        <h4>Правила</h4>
        <ul class="unstyled">
        {% for rule, status  in profile.get_rules() %}
            <li class="rule" rule="{{ rule }}"><input type="checkbox"{% if status == true %} checked=""{% endif %}> {{ rule }}</li>
        {% endfor %}
        </ul>
    </div>
    <div class="span4">
        <h4>Ограничения</h4>
        <ul>
            {#
        {% for restriction, status  in profile.get_restrictions() %}
            <li class="restriction" restriction="{{ restriction }}"><div class="get_restriction_content">{{ restriction }}</div></li>
        {% endfor %}#}
        </ul>
    </div>
</div>
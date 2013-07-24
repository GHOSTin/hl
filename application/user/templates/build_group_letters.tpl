<ul class="unstyled">
{% set lrs = ['а', 'б', 'в', 'г', 'д', 'е', 'ж', 'з', 'и', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'э', 'ю', 'я'] %}
{% for letter in lrs %}
    <li title="{{ letters[letter] }} групп"><div class="get_group_letter{% if letters[letter]|length > 0 %} letter-noempty{% endif %}">{{ letter }}</div></li>
{% endfor %}
</ul>
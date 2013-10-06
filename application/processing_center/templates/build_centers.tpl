{% for center in centers %} 
    <li class="processing-center" processing-center="{{ center.get_id() }}">
        <div class="get_processing_center_content processing-center-title">{{ center.get_name() }}</div>
    </li>
{% endfor %}
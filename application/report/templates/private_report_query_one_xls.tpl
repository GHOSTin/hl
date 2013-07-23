{% set queries = component.queries %}
{% set users = component.users %}
{% set works = component.works %}
{% set numbers = component.numbers %}
{% set statuses = {'open':'Открытая', 'working':'В работе',  'close': 'Закрытая', 'reopen':'Переоткрытая'} %}
{% set payment_statuses = {'paid':'Оплачиваемая', 'unpaid':'Неоплачиваемая', 'recalculation': 'Перерасчет'} %}
{% set warning_statuses = {'hight':'аварийная', 'normal':'на участок', 'planned': 'плановая'} %}
{% set user_roles = {'creator':'Диспетчер', 'manager':'Ответственный', 'performer': 'Исполнитель', 'observer': 'Наблюдатель'} %}
<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40">
    <Styles>
        <Style ss:ID="Default" ss:Name="Normal">
        <Alignment ss:Vertical="Bottom"/>
        <Borders/>
        <Font ss:FontName="Calibri" x:CharSet="204" x:Family="Swiss" ss:Size="11"
        ss:Color="#000000"/>
        <Interior/>
        <NumberFormat/>
        <Protection/>
        </Style>
        <Style ss:ID="s62">
        <NumberFormat ss:Format="dd/mmm"/>
        </Style>
    </Styles>
    <Worksheet ss:Name="import">
        <Table>
            <Row>
            <Cell><Data ss:Type="String">№</Data></Cell>
            <Cell><Data ss:Type="String">Статус</Data></Cell>
            <Cell><Data ss:Type="String">Тип</Data></Cell>
            <Cell><Data ss:Type="String">Работы</Data></Cell>
            <Cell><Data ss:Type="String">Оплата</Data></Cell>
            <Cell><Data ss:Type="String">Участок</Data></Cell>
            <Cell><Data ss:Type="String">Улица</Data></Cell>
            <Cell><Data ss:Type="String">Дом</Data></Cell>
            <Cell><Data ss:Type="String">Лицевые счета</Data></Cell>
            <Cell><Data ss:Type="String">Время открытия задачи</Data></Cell>
            <Cell><Data ss:Type="String">Время закрытия задачи</Data></Cell>    
            <Cell><Data ss:Type="String">Описание окрытия</Data></Cell>
            <Cell><Data ss:Type="String">Описание закрытия</Data></Cell>
            <Cell><Data ss:Type="String">Работы</Data></Cell>
            <Cell><Data ss:Type="String">Суммарное время</Data></Cell>
            <Cell><Data ss:Type="String">Сотрудники</Data></Cell>
            <Cell><Data ss:Type="String">Материалы</Data></Cell>
            </Row>
            {% for query in queries %}
                {% set work_time = 0 %}
                {% set users_string = '' %}
            <Row>
                <Cell><Data ss:Type="String">{{ query.number }}</Data></Cell> 
                <Cell><Data ss:Type="String">{{ statuses[query.status] }}</Data></Cell> 
                <Cell><Data ss:Type="String">{{ warning_statuses[query.warning_status] }}</Data></Cell> 
                <Cell><Data ss:Type="String">{{ query.work_type_name }}</Data></Cell> 
                <Cell><Data ss:Type="String">{{ payment_statuses[query.payment_status] }}</Data></Cell> 
                <Cell><Data ss:Type="String">{{ query.department_name }}</Data></Cell> 
                <Cell><Data ss:Type="String">{{ query.street_name }}</Data></Cell> 
                <Cell><Data ss:Type="String">{{ query.house_number }}</Data></Cell> 
                <Cell><Data ss:Type="String">
                    {% if query.initiator == 'number' %}
                        {% for number_id in numbers.structure[query.id]['true'] %}
                            {% set number = numbers.numbers[number_id] %}
                            {{ number.fio }} (№{{ number.number }}), кв. {{ number.flat_number }}
                        {% endfor %}
                    {% endif %}
                </Data></Cell> 
                <Cell><Data ss:Type="String">{{ query.time_open|date("h.i d.m.Y") }}</Data></Cell> 
                <Cell><Data ss:Type="String">{% if query.status == 'close' or query.status == 'reclose' %}{{ query.time_close|date("h.i d.m.Y") }}{% endif %}</Data></Cell> 
                <Cell><Data ss:Type="String">{{ query.description }}</Data></Cell> 
                <Cell><Data ss:Type="String">{{ query.close_reason }}</Data></Cell> 
                <Cell><Data ss:Type="String">
                    {%- for wstrct in works.structure[query.id] -%}
                        {% set work = works.works[wstrct.work_id] %}
                        {{- work.name -}}
                        {% set work_time = (work_time + (wstrct.time_close - wstrct.time_open)) // 60 %}
                    {%- endfor -%}
                </Data></Cell> 
                <Cell><Data ss:Type="String">{{ work_time }}</Data></Cell> 
                <Cell><Data ss:Type="String">
                    {%- for class, user_ids in users.structure[query.id] -%}
                        {%- for user_id in user_ids -%}
                            {% set user = users.users[user_id] %}
                            {{- user.lastname -}} {{ user.firstname }} {{ user.middlename }} ({{ user_roles[class] }})
                        {%- endfor -%}
                    {%- endfor -%}
                </Data></Cell> 
                <Cell><Data ss:Type="String"></Data></Cell> 
            </Row>
            {% endfor %}
        </Table>
    </Worksheet>
</Workbook>
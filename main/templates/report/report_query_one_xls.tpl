{% set statuses = {'open':'Открытая', 'working':'В работе',  'close': 'Закрытая', 'reopen':'Переоткрытая'} %}
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
            <Cell><Data ss:Type="String">Кв.</Data></Cell>
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
                <Cell><Data ss:Type="String">{{ query.get_number() }}</Data></Cell>
                <Cell><Data ss:Type="String">{{ statuses[query.get_status()] }}</Data></Cell>
                <Cell><Data ss:Type="String">{{ query.get_query_type().get_name() }}</Data></Cell>
                <Cell><Data ss:Type="String">{{ query.get_work_type().get_name() }}</Data></Cell>
                <Cell><Data ss:Type="String">{{ query.get_department().get_name() }}</Data></Cell>
                <Cell><Data ss:Type="String">{{ query.get_house().get_street().get_name() }}</Data></Cell>
                <Cell><Data ss:Type="String">{{ query.get_house().get_number() }}</Data></Cell>
                <Cell><Data ss:Type="String">
                {%- if query.get_initiator() == 'number' -%}
                  {%- for number in query.get_numbers() -%}
                   {{ number.get_flat().get_number() }}
                  {%- endfor -%}
                {%- endif -%}
                </Data></Cell>
                <Cell><Data ss:Type="String">
                {%- if query.get_initiator() == 'number' -%}
                  {%- for number in query.get_numbers() -%}
                    №{{ number.get_number() }} ({{ number.get_fio() }})
                  {%- endfor -%}
                {%- endif -%}
                </Data></Cell>
                <Cell><Data ss:Type="String">{{ query.get_time_open()|date("h.i d.m.Y") }}</Data></Cell>
                <Cell><Data ss:Type="String">{% if query.get_status() == 'close' or query.get_status() == 'reclose' %}{{ query.get_time_close()|date("h.i d.m.Y") }}{% endif %}</Data></Cell>
                <Cell><Data ss:Type="String">{{ query.get_description() }}</Data></Cell>
                <Cell><Data ss:Type="String">{{ query.get_close_reason() }}</Data></Cell>
                <Cell><Data ss:Type="String">
                    {%- for work in query.get_works() -%}
                        {{- work.get_name() -}}
                        {% set work_time = (work_time + (work.get_time_close() - work.get_time_open())) // 60 %}
                    {%- endfor -%}
                </Data></Cell>
                <Cell><Data ss:Type="String">{{ work_time }}</Data></Cell>
                <Cell><Data ss:Type="String">
                    {% set user = query.get_creator() %}
                    {{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }} (диспетчер)
                    {%- for user in query.get_managers() -%}
                        {{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }} (ответственный)
                    {%- endfor -%}
                    {%- for user in query.get_performers() -%}
                        {{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }} (исполнитель)
                    {%- endfor -%}
                </Data></Cell>
                <Cell><Data ss:Type="String"></Data></Cell>
            </Row>
            {% endfor %}
        </Table>
    </Worksheet>
</Workbook>
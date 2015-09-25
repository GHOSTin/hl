{
  "stat": {
            "open": {{ open }},
            "working": {{ working }},
            "close": {{ close }},
            "reopen": {{ reopen }},
            "sum": {{ sum }}
          },
  "chart": [
              {
                   "value": {{ open }},
                   "color":"#ff0000",
                   "label": "Открытые"
               },
               {
                   "value": {{ working }},
                   "color":"black",
                   "label": "В работе"
               },
               {
                   "value": {{ close }},
                   "color":"#cccccc",
                   "label": "Закрытые"
               },
               {
                   "value": {{ reopen }},
                   "color":"blue",
                   "label": "Переоткрытые"
               }
            ]
}
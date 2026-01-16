    DarkClear is an open-source PocketMine-MP plugin for automatic and manual cleanup of entities, items, and bugged tiles. It provides smart cleaning based on player proximity and server TPS, configurable timers, and support for protected entities. This plugin is ideal for maintaining server performance and reducing lag.
    This plugin was originally developed for my DarkFlame server, but I decided to make it publicly available.


    DarkClear — это открытый плагин для PocketMine-MP для автоматической и ручной очистки сущностей, предметов и сломанных тайлов. Плагин поддерживает умную очистку рядом с игроками, очистку при падении TPS, настраиваемые таймеры и защиту сущностей. Идеально подходит для поддержания производительности сервера и уменьшения лагов.
    Данный плагин изначально был разработан для моего сервера DarkFlame, но я решил выложить его в общий доступ.


- [Compatibility](COMPATIBILITY.md)



English Version 
Description



Features:
Automatic Cleanup – clears entities and items at a configurable interval.

Manual Cleanup – execute /darkclear clear to clean instantly.

TPS-based Cleanup – automatic emergency cleanup when server TPS drops below a threshold.

Smart Cleaning – avoid removing entities near players (configurable radius).

Protected Entities – mark entities with /clearserver protect to prevent them from being deleted.

Bugged Tile Removal – automatically removes tiles that have no blocks.

Configurable – full customization through config.yml.





Commands
Command	Description
/darkclear clear	Manually clean entities and items immediately.
/darkclear set <seconds>	Set the interval for automatic cleanup.
/darkclear on	Enable automatic cleanup.
/darkclear off	Disable automatic cleanup.
/darkclear protect	Hit a mob to protect it from cleanup.
/darkclear unprotect	Hit a mob to remove protection.
/darkclear list [page]	Show a list of protected entities and their coordinates.
/darkclear help	Show plugin commands.







Configuration (config.yml)
clear-interval: 600        # Interval for auto-clean in seconds
enabled: true              # Enable or disable automatic cleaning
warn-seconds:              # Broadcast warnings before cleanup
  - 60
  - 30
  - 10
remove-mobs: true          # Remove mobs during cleanup
remove-items: true         # Remove dropped items during cleanup
remove-bugged-tiles: true  # Remove tiles with no blocks
tps-cleaner: true          # Enable TPS-based emergency cleanup
smart-clean: true          # Enable smart cleaning near players
tps-trigger: 16.5          # TPS threshold to enable smart cleaning
tps-force-clean: 13.0      # TPS threshold for forced cleanup
smart-remove-even-near: false # Remove even near players when enabled
smart-radius: 24           # Radius around players to avoid removing entities






Installation

Place the src or .phar file in the PocketMine-MP plugin directory.

Copy the config.yml into the plugin directory and adjust settings as needed.

Restart the server.





Notes

Protected entities are not removed during automatic or manual cleanup.

Smart cleaning avoids removing entities near players unless explicitly enabled in the config.

TPS-based cleaning helps prevent server lag by removing excessive entities/items when performance drops.















Русская версия
Описание




Возможности

Автоочистка – удаляет сущности и предметы через заданный интервал.

Ручная очистка – команда /darkclear clear для мгновенной очистки.

Очистка по TPS – экстренная очистка при падении TPS ниже порога.

Умная очистка – не удаляет сущности рядом с игроками (настраиваемый радиус).

Защита сущностей – помеченные сущности не удаляются.

Очистка сломанных тайлов – автоматически удаляет тайлы без блока.

Настраиваемость – полностью через config.yml.




Команды
Команда	Описание
/darkclear clear	Ручная очистка сущностей и предметов.
/darkclear set <секунды>	Установить интервал автоочистки.
/darkclear on	Включить автоочистку.
/darkclear off	Выключить автоочистку.
/darkclear protect	Ударь по мобу, чтобы защитить его от очистки.
/darkclear unprotect	Ударь по мобу, чтобы снять защиту.
/darkclear list [страница]	Показать список защищённых сущностей с координатами.
/darkclear help	Показать команды плагина.






Конфигурация (config.yml)
clear-interval: 600
enabled: true
warn-seconds:
  - 60
  - 30
  - 10
remove-mobs: true
remove-items: true
remove-bugged-tiles: true
tps-cleaner: true
smart-clean: true
tps-trigger: 16.5
tps-force-clean: 13.0
smart-remove-even-near: false
smart-radius: 24





Установка

Поместите src или .phar в директорию плагинов PocketMine-MP.

Скопируйте config.yml в папку плагина и настройте параметры.

Перезапустите сервер.

Примечания

Защищённые сущности не удаляются ни при автоматической, ни при ручной очистке.

Умная очистка не удаляет сущности рядом с игроками, если это не разрешено в конфиге.

Очистка по TPS помогает уменьшить лаги при падении производительности сервера.





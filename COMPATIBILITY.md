Plugin Compatibility / Совместимость плагина


Eng:

Supported cores:

LiteCore

Genesis

This plugin is written specifically for LiteCore and Genesis, but it is generally compatible with other PocketMine-MP cores as well.

Version: 3.0.0

Known issue on other cores:
If you encounter an error at line 130:
$this->getServer()->getScheduler()->scheduleRepeatingTask(new CallbackTask([$this, "update"]), 20);

You can fix it by replacing it with:

$this->getScheduler()->scheduleRepeatingTask(new CallbackTask([$this, "update"]), 20);









RU

Поддерживаемые ядра:

LiteCore

Genesis

Плагин написан специально для LiteCore и Genesis, но в целом совместим и с другими ядрами PocketMine-MP.

Версия: 3.0.0

Известная проблема на других ядрах:
Если возникает ошибка в строке 130:

$this->getServer()->getScheduler()->scheduleRepeatingTask(new CallbackTask([$this, "update"]), 20);


Исправляется заменой на:

$this->getScheduler()->scheduleRepeatingTask(new CallbackTask([$this, "update"]), 20);

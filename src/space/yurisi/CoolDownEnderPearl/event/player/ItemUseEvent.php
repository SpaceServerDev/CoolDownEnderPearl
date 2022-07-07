<?php
declare(strict_types=1);

namespace space\yurisi\CoolDownEnderPearl\event\player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\ItemIds;
use space\yurisi\CoolDownEnderPearl\Main;

class ItemUseEvent implements Listener {

    public function __construct(private Main $main) {
    }

    public function onItemUse(PlayerItemUseEvent $event) {
        if ($event->getItem()->getId() === ItemIds::ENDER_PEARL) {
            if ($this->main->isProgressTask($event->getPlayer())) {
                $event->cancel();
                return true;
            }
            $this->main->setTask($event->getPlayer());
        }
        return true;
    }
}
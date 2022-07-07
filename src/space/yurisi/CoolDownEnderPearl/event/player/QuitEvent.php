<?php
declare(strict_types=1);

namespace space\yurisi\CoolDownEnderPearl\event\player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use space\yurisi\CoolDownEnderPearl\Main;

class QuitEvent implements Listener {

    public function __construct(private Main $main) {
    }

    public function onQuit(PlayerQuitEvent $event) {
        if ($this->main->isProgressTask($event->getPlayer())) {
            $this->main->cancelTask($event->getPlayer());
        }
    }
}
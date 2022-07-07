<?php
declare(strict_types=1);

namespace space\yurisi\CoolDownEnderPearl\task;

use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use space\yurisi\CoolDownEnderPearl\Main;

class SendTask extends Task {

    private int $tick_counter = 1;

    public function __construct(private Player $player, private Main $main) {
    }

    public function onRun(): void {
        $max_tick = $this->main->getSecond() * 4;
        if ($this->tick_counter >= $max_tick) {
            $this->getHandler()->cancel();
            return;
        }
        $this->player->getXpManager()->setXpProgress($this->tick_counter / $max_tick);
        $this->tick_counter++;
    }

    public function onCancel(): void {
        $this->main->removeTask($this->player);
    }
}
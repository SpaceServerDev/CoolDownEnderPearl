<?php
declare(strict_types=1);

namespace space\yurisi\CoolDownEnderPearl\task;

use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use space\yurisi\CoolDownEnderPearl\Main;

class SendTask extends Task {

    private const MAX_TICK_COUNT = 20;
    private int $tick_counter = 1;

    public function __construct(private Player $player, private Main $main) {
    }

    public function onRun(): void {
        if ($this->tick_counter >= self::MAX_TICK_COUNT) {
            $this->getHandler()->cancel();
            return;
        }
        $this->player->getXpManager()->setXpProgress($this->tick_counter / self::MAX_TICK_COUNT);
        $this->tick_counter++;
    }

    public function onCancel(): void {
        $this->main->removeTask($this->player);
    }
}
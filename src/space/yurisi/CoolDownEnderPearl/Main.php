<?php
declare(strict_types=1);

namespace space\yurisi\CoolDownEnderPearl;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use space\yurisi\CoolDownEnderPearl\event\player\ItemUseEvent;
use space\yurisi\CoolDownEnderPearl\event\player\QuitEvent;
use space\yurisi\CoolDownEnderPearl\task\SendTask;

class Main extends PluginBase {

    private array $tasks = [];

    private array $progress = [];

    private int $coolDown_second;

    public function setTask(Player $player) {
        $this->progress[$player->getName()] = $player->getXpManager()->getXpProgress();
        $task = $this->getScheduler()->scheduleRepeatingTask(new SendTask($player, $this), $this->getSecond());
        $this->tasks[$player->getName()] = $task->getTask()->getHandler();
    }

    public function cancelTask(Player $player) {
        if (!$this->isProgressTask($player)) return;
        $this->tasks[$player->getName()]->cancel();
        $this->removeTask($player);
    }

    public function isProgressTask(Player $player): bool {
        return isset($this->tasks[$player->getName()]);
    }

    public function removeTask(Player $player) {
        if(!(isset($this->progress[$player->getName()]) && isset($this->tasks[$player->getName()]))) return;
        $player->getXpManager()->setXpProgress($this->progress[$player->getName()]);
        unset($this->tasks[$player->getName()]);
        unset($this->progress[$player->getName()]);
    }

    public function getSecond(): int{
        return $this->coolDown_second;
    }

    protected function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents(new ItemUseEvent($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new QuitEvent($this), $this);
        $config = new Config($this->getDataFolder().'setting.yml', Config::YAML, [
            '秒数' => 5
        ]);
        $this->coolDown_second = $config->get('秒数');
    }
}
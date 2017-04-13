<?php

namespace BlockHorizons\BlockSniper\session;

use BlockHorizons\BlockSniper\brush\Brush;
use BlockHorizons\BlockSniper\brush\BrushManager;
use BlockHorizons\BlockSniper\Loader;
use pocketmine\Player;

class Session
{

    /** @var Loader */
    private $loader;

    public function getLoader(): Loader {
        return $this->loader;
    }

    /** @var Player */
    protected $player;

    /** @var Action[] */
    protected $history;

    /** @var Brush|null */
    protected $brush;

    public function __construct(Player $player, Loader $loader)
    {
        $this->player = $player;
        $this->loader = $loader;
    }

    public function getBrush(): Brush {
        if($this->brush) return $this->brush;
        $this->brush = BrushManager::get($this->player);
        if(!$this->brush) {
            $this->brush = $this->getLoader()->getBrushManager()->createBrush($this->player);
        }
        return $this->getBrush(); // Segmentation fault? :D
    }

    public function resetBrush(): bool {
        $this->brush = null;
        return $this->getLoader()->getBrushManager()->resetBrush($this->player);
    }

    public function getOldestAction()
    {
        if(empty($this->history)) return null;
        return $this->history[0];
    }

    public function getLatestAction()
    {
        if(empty($this->history)) return null;
        return array_reverse($this->history)[0];
    }

    /**
     * Removes Action from history
     *
     * @param Action $action
     * @return bool
     */
    public function removeAction(Action $action): bool
    {
        if(!$this->actionExists($action)) return false;
        unset($this->history[array_search($action, $this->history, true)]);
        $this->order();
        return true;
    }

    /**
     * Adds Action to end of action history if $index is null
     *
     * @param Action $action
     * @param int|null $index
     * @return bool
     */
    public function addAction(Action $action, int $index = null): bool
    {
        if($this->actionExists($action)) return false;
        $index = $index ? $index : count($this->history);
        $this->history[$index] = $action;
        $this->order();
        return true;
    }

    private function order()
    {
        $this->history = array_values($this->history);
    }

    private function actionExists($action)
    {
        if ($action instanceof Action) {
            return in_array($action, $this->history, true);
        } else {
            return isset($this->history[$action]);
        }
    }

    public function getHistory() {
        return $this->history;
    }

}
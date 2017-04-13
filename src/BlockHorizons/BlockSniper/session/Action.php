<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 17.13.4
 * Time: 18:28
 */

namespace BlockHorizons\BlockSniper\session;


use pocketmine\block\Block;

class Action
{

    /** @var string */
    protected $name;

    /** @var Block[] */
    protected $blocks = [];

    /** @var int */
    protected $time;

    public function __construct(string $name, array $blocks, int $time = null) {
        $this->name = $name;
        $this->blocks = $blocks;
        $this->time = $time ? $time : time();
    }

    public function getTime(): int {
        return $this->time;
    }

    public function setTime(int $time) {
        $this->time = $time;
    }

    public function getBlocks(): array {
        return $this->blocks;
    }

    /**
     * @param Block[] $blocks
     */
    public function setBlocks(array $blocks) {
        $this->blocks = $blocks;
    }

    /**
     * Restores saved blocks to their original state
     */
    public function restore()
    {
        foreach ($this->getBlocks() as $block) {
            $block->getLevel()->setBlock($block, $block, false, false);
        }
    }

    /**
     * Counts saved blocks, if $except given, then only blocks not in this array will be counted
     *
     * @param array|null $except
     * @return int
     */
    public function getBlockCount(array $except = null): int
    {
        if(!$except) return count($this->getBlocks());
        $i = 0;
        foreach ($this->getBlocks() as $b) {
            foreach ($except as $eb) {
                if($b->getId() === $eb->getId() && $b->getDamage() === $eb->getDamage()) continue 2;
            }
            $i++;
        }
        return $i;
    }

}
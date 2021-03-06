<?php

declare(strict_types = 1);

namespace BlockHorizons\BlockSniper\brush\types;

use BlockHorizons\BlockSniper\brush\BaseType;
use pocketmine\level\Level;
use pocketmine\Player;

class CleanentitiesType extends BaseType {
	
	/*
	 * Clears all entities within the brush radius. This brush can not undo.
	 */
	public function __construct(Player $player, Level $level, array $blocks) {
		parent::__construct($player, $level, $blocks);
	}

	/**
	 * @return array
	 */
	public function fillShape(): array {
		foreach($this->blocks as $block) {
			foreach($this->level->getEntities() as $entity) {
				if($entity->distanceSquared($block) <= 1 && !($entity instanceof Player)) {
					$entity->close();
				}
			}
		}
		return [];
	}
	
	public function getName(): string {
		return "Clean Entities";
	}
}

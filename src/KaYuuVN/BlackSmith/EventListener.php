<?php
namespace KaYuuVN\BlackSmith;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\Listener;
use pocketmine\Item;
use pocketmine\block\Anvil;

/**
 * Class EventListener
 * @package KaYuuVN\BlackSmith
 */

Class EventListener implements Listener{
    
    /** @var BlackSmith */

    private $plugin;
    
    /**
    * EventListener constructor.
    * @param BlackSmith $plugin
    */

    public function __construct(Main $plugin)
    {
		$this->plugin = $plugin;
    }
    
    /**
    * @param PlayerInteractEvent $ev
    */

    public function onInteract(PlayerInteractEvent $ev) 
    {
        if ($ev->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
            if ($ev->getBlock() instanceof Anvil) {
                $ev->setCancelled();
                $this->plugin->Choose($ev->getPlayer(), 0);
            }
        }
    }
}

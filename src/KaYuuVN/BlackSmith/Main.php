<?php 

namespace KaYuuVN\BlackSmith;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\level\sound\AnvilUseSound;
use pocketmine\plugin\PluginBase;
use jojoe77777\FormAPI\{CustomForm, SimpleForm, ModalForm};

Class Main extends PluginBase
{
    public function onEnable():void 
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->exp = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
    }
   
    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args):bool
    {
		if (!($sender instanceof Player)) {
			$this->getLogger()->notice("Please Don't Use that command in here.");
			return true;
		}
        if ($cmd->getName() === "blacksmith" || $cmd->getName() === "bs") {
            $this->Choose($sender, 0);
        }
        return true;
    }

    public function Choose($sender, $status, $price = null, $len = null)
    {
        $form = new SimpleForm(function (Player $sender, $data) {
        	$result = $data;
			if ($result == null) {
				return true;
			}
			switch ($result) {
				case 0:
				break;

				case 1:
				$this->SetNameItem($sender);
				break;

				case 2:
				$this->SetLoreItem($sender);
				break;
				}
			});
		$form->setTitle("§l§8THỢ RÈN");
		if ($status == 0) {
		    $form->setContent(" Chọn hành động\n");
		} elseif($status == 1) {
		    $form->setContent("§c Bạn không có đủ tiền!");
		} elseif($status == 2) {
		    if ($len != 0) {
		    $form->setContent(" Tổng số tiền: §a".$price."\n§r Độ dài ký tự: §a".$len."\n");
		    } else {
		    	$form->setContent(" Tổng số tiền: §a".$price);
		    }
		}
		$form->addButton("§l§8BACK");
		$form->addButton("§l§8NAME");
        $form->addButton("§l§8LORE");
		$form->sendToPlayer($sender);
    }


	public function SetNameItem($sender) 
	{
        $form = new CustomForm(function (Player $sender, $data) {

		if ($data[1] == null){
			return $this->Choose($sender, 0);
		}

        $name = $sender->getName();
        $money = $this->exp->myMoney($sender);
		$cost = strlen($data[1])*100;
		if ($money >= $cost) {
            $item = $sender->getInventory()->getItemInHand();
            $item->setCustomname($data[1]);
            $sender->getInventory()->setItemInHand($item);
            $sender->getLevel()->addSound(new AnvilUseSound($sender));
            $this->Choose($sender, 2, $cost, strlen($data[1]));
            $this->exp->reduceMoney($sender, $cost);
        } else {
        	$this->Choose($sender, 1);
        }
            });
        $form->setTitle("§l§8THỢ RÈN [Đổi Tên]");
        $form->addLabel("");
		$form->addInput("§fĐổi tên vật phẩm");
		$form->sendToPlayer($sender);
	}
	
	public function SetLoreItem($sender)
	{
        $form = new CustomForm(function (Player $sender, $data) {

		if ($data[1] == null){
			return $this->Choose($sender, 0);
		}
        $name = $sender->getName();
        $money = $this->exp->myMoney($sender);
		$cost = strlen($data[1])*50;
		if ($money >= $cost) {
            $item = $sender->getInventory()->getItemInHand();
            $item->setLore(explode("\\n", $data[1]));
            $sender->getInventory()->setItemInHand($item);
            $sender->getLevel()->addSound(new AnvilUseSound($sender));
            $this->Choose($sender, 2, $cost, strlen($data[1]));
            $this->exp->reduceMoney($sender, $cost);
        } else {
        	$this->Choose($sender, 1);
        }

        	});
        $form->setTitle("§l§8THỢ RÈN [Đổi mô tả]");
        $form->addLabel("");
		$form->addInput("Đổi mô tả chi tiết vật phẩm");
		$form->sendToPlayer($sender);
	}
}
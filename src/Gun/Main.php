<?php

namespace Gun;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\level\particle\FlameParticle;
use pocketmine\level\sound\BlazeShootSound;
use pocketmine\level\sound\ExpPickupSound;
use pocketmine\item\Egg;
use pocketmine\level\sound\AnvilFallSound;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\item\Snowball;
use pocketmine\block\TNT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\level\Level;
use pocketmine\utils\TextFormat as T;

class Main extends PluginBase implements Listener{
	
	public $players = [];
    public $prefix = T::GRAY."[".T::YELLOW."Gun".T::WHITE."Shop".T::GRAY."]";
	public function onEnable(){
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->getLogger()->info(T::GOLD."-=[]".$this->prefix."".T::GOLD."[]=-");
    $this->getLogger()->info(T::GREEN."By: ".T::AQUA."@becraft_mcpe");
    $this->getLogger()->info(T::GOLD."-=[]".$this->prefix."".T::GOLD."[]=-");
    $this->getLogger()->info(" Enabled");
    @mkdir($this->getDataFolder());
    $config = new Config($this->getDataFolder()."Coin.yml", Config::YAML);
    $config->save();
    $prices = new Config($this->getDataFolder()."Prices.yml", Config::YAML, [
    "Ak47" => 300,
    "P90" => 500,
    "M79" => 710,
    "UZI" => 640,
    "Scarab-Knife" => 50,
    "Diamond-Ore" => 3,
    "Gold-Ore" => 2,
    "Redstone-Ore" => 2,
    "Iron-Ore" => 5,
    "Coal-Ore" => 1,
    "Emerald-Ore" => 2,
    "Snowball-Damage" => 2,
    "Egg-Damage" => 2,
    "Snowball-Bullet" => 50,
    "TNT-Bullet" => 100,
    "Egg-Bullet" => 60,
    "Lapis-Ore" => 2,
    "Coins-By-Kills" => 10
    ]);
    $prices->save();
     }
   
    public function setCoins(Player $player, int $amount){
    $this->players[$player->getName()] = $amount;
 	}
  
    public function getCoins(Player $player){
	return $this->players[$player->getName()];
 	}
   
    public function onJoin(\pocketmine\event\player\PlayerJoinEvent $e){
	$player = $e->getPlayer();
    $name = $player->getName(); 
    $config = new Config($this->getDataFolder()."Coin.yml", Config::YAML);
    if($config->get($player->getName(), $this->getCoins($player)) >= 1){
    $player->sendMessage(T::GRAY."[".T::YELLOW."Gun".T::WHITE."Shop".T::GRAY."]".T::GOLD." Your coins: ".T::GREEN."".$config->get($player->getName(), $this->getCoins($player)));
	}else{
		}
	}

    public function onDamage(EntityDamageEvent $event){
    	if($event instanceof EntityDamageByChildEntityEvent){
        $child = $event->getChild();
        $prices = new Config($this->getDataFolder()."Prices.yml", Config::YAML);   
        if($child instanceof \pocketmine\entity\Snowball){
        $event->setDamage($event->getDamage()+$prices->get("Snowball-Damage"));
    }
    if($child instanceof \pocketmine\entity\Egg){
        $event->setDamage($event->getDamage()+$prices->get("Egg-Damage"));
    }    
    }
    }
   
   public function onDeath(\pocketmine\event\player\PlayerDeathEvent $event){
   $config = new Config($this->getDataFolder()."Coin.yml", Config::YAML);
   $prices = new Config($this->getDataFolder()."Prices.yml", Config::YAML);   
   if($event->getEntity()->getLastDamageCause()->getDamager() instanceof Player){
   $entity = $event->getEntity();
   $damager = $event->getEntity()->getLastDamageCause()->getDamager();
   $this->setCoins($damager, $config->get($damager->getName(), $this->getCoins($damager))+$prices->get("Coins-By-Kills"));
   $damager->sendMessage(T::GOLD."+".T::GREEN."".$prices->get("Coins-By-Kills").T::GOLD." Coins");
   $config->set($damager->getName(), $this->getCoins($damager));
   $config->save();
   }
   }
   
   public function onBreak(\pocketmine\event\block\BlockBreakEvent $e){
   $player = $e->getPlayer();
   $block = $e->getBlock();
   $config = new Config($this->getDataFolder()."Coin.yml", Config::YAML);
   $prices = new Config($this->getDataFolder()."Prices.yml", Config::YAML);
   if($block->getId() == 15){
   $this->setCoins($player, $config->get($player->getName(), $this->getCoins($player))+$prices->get("Iron-Ore"));
   $player->sendMessage(T::GOLD."+".T::GREEN."".$prices->get("Iron-Ore").T::GOLD." Coins");
   $config->set($player->getName(), $this->getCoins($player));
   $config->save();
   }
   if($block->getId() == 16){
   $this->setCoins($player, $config->get($player->getName(), $this->getCoins($player))+$prices->get("Coal-Ore"));
   $player->sendMessage(T::GOLD."+".T::GREEN."".$prices->get("Coal-Ore").T::GOLD." Coins");
   $config->set($player->getName(), $this->getCoins($player));
   $config->save();
   }
   if($block->getId() == 14){
   $this->setCoins($player, $config->get($player->getName(), $this->getCoins($player))+$prices->get("Gold-Ore"));
   $player->sendMessage(T::GOLD."+".T::GREEN."".$prices->get("Gold-Ore").T::GOLD." Coins");
   $config->set($player->getName(), $this->getCoins($player));
   $config->save();
   }
   if($block->getId() == 21){
   $this->setCoins($player, $config->get($player->getName(), $this->getCoins($player))+$prices->get("Lapis-Ore"));
   $player->sendMessage(T::GOLD."+".T::GREEN."".$prices->get("Lapis-Ore").T::GOLD." Coins");
   $config->set($player->getName(), $this->getCoins($player));
   $config->save();
   }
   if($block->getId() == 56){
   $this->setCoins($player, $config->get($player->getName(), $this->getCoins($player))+$prices->get("Diamond-Ore"));
   $player->sendMessage(T::GOLD."+".T::GREEN."".$prices->get("Diamond-Ore").T::GOLD." Coins");
   $config->set($player->getName(), $this->getCoins($player));
   $config->save();
   }
   if($block->getId() == 73){
   $this->setCoins($player, $config->get($player->getName(), $this->getCoins($player))+$prices->get("Redstone-Ore"));
   $player->sendMessage(T::GOLD."+".T::GREEN."".$prices->get("Redstone-Ore").T::GOLD." Coins");
   $config->set($player->getName(), $this->getCoins($player));
   $config->save();
   }
   if($block->getId() == 129){
   $this->setCoins($player, $config->get($player->getName(), $this->getCoins($player))+$prices->get("Emerald-Ore"));
   $player->sendMessage(T::GOLD."+".T::GREEN."".$prices->get("Emerald-Ore").T::GOLD." Coins");
   $config->set($player->getName(), $this->getCoins($player));
   $config->save();
   }
   }

   public function onInteract(\pocketmine\event\player\PlayerInteractEvent $event){
   $player = $event->getPlayer();
   $item = $event->getItem();
   $config = new Config($this->getDataFolder()."Coin.yml", Config::YAML);
	$prices = new Config($this->getDataFolder()."Prices.yml", Config::YAML);
   if($item->getId() == 346 && $item->getCustomName() == T::GREEN."Ak47\n".T::GOLD."Bullet: ".T::YELLOW."Snowballs\n".T::GOLD."Damage: ".T::YELLOW."".$prices->get("Snowball-Damage")){
   $event->setCancelled();
   if($player->getInventory()->contains(new Snowball(0, 1))){
   $nbt = new CompoundTag("", [
							"Pos" => new ListTag("Pos", [
								new DoubleTag("", $player->x),
								new DoubleTag("", $player->y + $player->getEyeHeight()),
								new DoubleTag("", $player->z)
							]),
							"Motion" => new ListTag("Motion", [
								new DoubleTag("", -sin($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI)),
								new DoubleTag("", -sin($player->pitch / 180 * M_PI)),
								new DoubleTag("", cos($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI))
							]),
							"Rotation" => new ListTag("Rotation", [
								new FloatTag("", $player->yaw),
								new FloatTag("", $player->pitch)
							]),
						]);
						$f = 2;
						$snowball = Entity::createEntity("Snowball", $player->chunk, $nbt, $player);
						$snowball->setMotion($snowball->getMotion()->multiply($f));
						$snowball->getLevel()->addSound(new BlazeShootSound(new Vector3($player->x, $player->y, $player->z, $player->getLevel())));
						$player->getInventory()->removeItem(Item::get(Item::SNOWBALL, 0, 1));
		}else{
			$player->sendPopup(T::RED."You dont have more bullets!");
			}
	}
	if($item->getId() == 346 && $item->getCustomName() == T::GREEN."P90\n".T::GOLD."Bullet: ".T::YELLOW."Snowballs\n".T::GOLD."Damage: ".T::YELLOW."".$prices->get("Snowball-Damage")."\n".T::GOLD."Shoots: ".T::YELLOW."x2"){
		$event->setCancelled();
		if($player->getInventory()->contains(new Snowball(0, 1))){
		$nbt = new CompoundTag("", [
							"Pos" => new ListTag("Pos", [
								new DoubleTag("", $player->x),
								new DoubleTag("", $player->y + $player->getEyeHeight()),
								new DoubleTag("", $player->z)
							]),
							"Motion" => new ListTag("Motion", [
								new DoubleTag("", -sin($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI)),
								new DoubleTag("", -sin($player->pitch / 180 * M_PI)),
								new DoubleTag("", cos($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI))
							]),
							"Rotation" => new ListTag("Rotation", [
								new FloatTag("", $player->yaw),
								new FloatTag("", $player->pitch)
							]),
						]);
						$nbttwo = new CompoundTag("", [
							"Pos" => new ListTag("Pos", [
								new DoubleTag("", $player->x),
								new DoubleTag("", $player->y + $player->getEyeHeight()),
								new DoubleTag("", $player->z)
							]),
							"Motion" => new ListTag("Motion", [
								new DoubleTag("", -sin($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI)),
								new DoubleTag("", -sin($player->pitch / 180 * M_PI)),
								new DoubleTag("", cos($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI))
							]),
							"Rotation" => new ListTag("Rotation", [
								new FloatTag("", $player->yaw),
								new FloatTag("", $player->pitch)
							]),
						]);
						$f = 2;
						$snowball = Entity::createEntity("Snowball", $player->chunk, $nbt, $player);
						$snowball = Entity::createEntity("Snowball", $player->chunk, $nbttwo, $player);
						$snowball->setMotion($snowball->getMotion()->multiply($f));
						$snowball->getLevel()->addSound(new BlazeShootSound(new Vector3($player->x, $player->y, $player->z, $player->getLevel())));
						$player->getInventory()->removeItem(Item::get(Item::SNOWBALL, 0, 1));
		}else{
			$player->sendPopup(T::RED."You dont have more bullets!");
			}
	}
	   if($item->getId() == 346 && $item->getCustomName() == T::GREEN."M79\n".T::GOLD."Bullet: ".T::YELLOW."TNT\n".T::GOLD."Damage: ".T::YELLOW."high \n".T::GOLD."Shoots: ".T::YELLOW."x1"){
   $event->setCancelled();
   $itemid = Item::get(46, 0, 1);
   if($player->getInventory()->contains($itemid)){
   $nbt = new CompoundTag("", [
							"Pos" => new ListTag("Pos", [
								new DoubleTag("", $player->x),
								new DoubleTag("", $player->y + $player->getEyeHeight()),
								new DoubleTag("", $player->z)
							]),
							"Motion" => new ListTag("Motion", [
								new DoubleTag("", -sin($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI)),
								new DoubleTag("", -sin($player->pitch / 180 * M_PI)),
								new DoubleTag("", cos($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI))
							]),
							"Rotation" => new ListTag("Rotation", [
								new FloatTag("", $player->yaw),
								new FloatTag("", $player->pitch)
                            ]),
							]);
						$f = 2;
						$tnt = Entity::createEntity("PrimedTNT", $player->chunk, $nbt, true);
						$tnt->setMotion($tnt->getMotion()->multiply($f));
						$tnt->getLevel()->addParticle(new FlameParticle(new Vector3($tnt->getX(), $tnt->getY()+0.5, $tnt->getZ())));
						$tnt->getLevel()->addSound(new AnvilFallSound(new Vector3($player->x, $player->y, $player->z, $player->getLevel())));
						$player->getInventory()->removeItem(Item::get(Item::TNT, 0, 1));
						$tnt->spawnTo($player);
		}else{
			$player->sendPopup(T::RED."You dont have more bullets!");
			}
	}
	if($item->getId() == 346 && $item->getCustomName() == T::GREEN."UZI\n".T::GOLD."Bullet: ".T::YELLOW."Eggs\n".T::GOLD."Damage: ".T::YELLOW."".$prices->get("Egg-Damage")."\n".T::GOLD."Shoots: ".T::YELLOW."x1"){
			$event->setCancelled();
		if($player->getInventory()->contains(new Egg(0, 1))){
		$nbt = new CompoundTag("", [
							"Pos" => new ListTag("Pos", [
								new DoubleTag("", $player->x),
								new DoubleTag("", $player->y + $player->getEyeHeight()),
								new DoubleTag("", $player->z)
							]),
							"Motion" => new ListTag("Motion", [
								new DoubleTag("", -sin($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI)),
								new DoubleTag("", -sin($player->pitch / 180 * M_PI)),
								new DoubleTag("", cos($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI))
							]),
							"Rotation" => new ListTag("Rotation", [
								new FloatTag("", $player->yaw),
								new FloatTag("", $player->pitch)
							]),
						]);
						$nbttwo = new CompoundTag("", [
							"Pos" => new ListTag("Pos", [
								new DoubleTag("", $player->x),
								new DoubleTag("", $player->y + $player->getEyeHeight()),
								new DoubleTag("", $player->z)
							]),
							"Motion" => new ListTag("Motion", [
								new DoubleTag("", -sin($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI)),
								new DoubleTag("", -sin($player->pitch / 180 * M_PI)),
								new DoubleTag("", cos($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI))
							]),
							"Rotation" => new ListTag("Rotation", [
								new FloatTag("", $player->yaw),
								new FloatTag("", $player->pitch)
							]),
						]);
						$f = 2;
						$egg = Entity::createEntity("Egg", $player->chunk, $nbt, $player);
						$egg->setMotion($egg->getMotion()->multiply($f));
						$egg->getLevel()->addSound(new BlazeShootSound(new Vector3($player->x, $player->y, $player->z, $player->getLevel())));
						$player->getInventory()->removeItem(Item::get(Item::EGG, 0, 1));
		}else{
			$player->sendPopup(T::RED."You dont have more bullets!");
			}
	}
	} 
	
	public function ExplosionPrimeEvent(\pocketmine\event\entity\ExplosionPrimeEvent $event){
          $event->setBlockBreaking(false);
      }
          
public function onCommand(\pocketmine\command\CommandSender $sender, \pocketmine\command\Command $command, $label, array $args){
	$cmd = strtolower($command->getName());
	$config = new Config($this->getDataFolder()."Coin.yml", Config::YAML);
	$prices = new Config($this->getDataFolder()."Prices.yml", Config::YAML);
	switch($cmd){
		case "store":
		if(isset($args[0])){
			switch($args[0]){
				case "list":
				$sender->sendMessage(T::GRAY."/".T::GOLD."store buy <gun>".T::GRAY."(".T::YELLOW."Buy any type of gun".T::GRAY.")");
				$sender->sendMessage(T::GRAY."/".T::GOLD."store bullet <type> ".T::GRAY."(".T::YELLOW."Buy any type of bullet: egg, snowball, tnt".T::GRAY.")");
				$sender->sendMessage(T::GRAY."/".T::GOLD."store info ".T::GRAY."(".T::YELLOW."Check this plugin information".T::GRAY.")");
				$sender->sendMessage(T::GRAY."/".T::GOLD."store coins ".T::GRAY."(".T::YELLOW."Check all your coins".T::GRAY.")");
				$sender->sendMessage(T::GRAY."/".T::GOLD."store info ".T::GRAY."(".T::YELLOW."Check this plugin information".T::GRAY.")");
				$sender->sendMessage(T::GRAY."/".T::GOLD."store give <player> <amount> ".T::GRAY."(".T::YELLOW."Give coins to any player".T::GRAY.")");
				$sender->sendMessage(T::GRAY."/".T::GOLD."store remove <player> <amount> ".T::GRAY."(".T::YELLOW."Remove coins from any player".T::GRAY.")");
				$sender->sendMessage(T::GREEN."Available:\n".T::YELLOW."ak47 ".T::GRAY."=> ".T::GOLD."".$prices->get("Ak47")."$ ".T::GREEN."Coins ".T::GRAY."(".T::AQUA."Shoot snowballs to players!".T::GRAY.")\n".T::YELLOW."p90 ".T::GRAY."=> ".T::GOLD."".$prices->get("P90")."$ ".T::GREEN."Coins ".T::GRAY."(".T::AQUA."Shoot x2 snowballs to players!".T::GRAY.")\n".T::YELLOW."m79 ".T::GRAY."=> ".T::GOLD."".$prices->get("M79")."$ ".T::GREEN."Coins ".T::GRAY."(".T::AQUA."Launch grenates to players!".T::GRAY.")\n".T::YELLOW."uzi ".T::GRAY."=> ".T::GOLD."".$prices->get("UZI")."$ ".T::GREEN."Coins ".T::GRAY."(".T::AQUA."Shoot eggs to players!".T::GRAY.")\n".T::YELLOW."scarab ".T::GRAY."=> ".T::GOLD."".$prices->get("Scarab-Knife")."$ ".T::GREEN."Coins ".T::GRAY."(".T::AQUA."Use this knife to kill the players :D".T::GRAY.")");
				return true;
				break;
				
				case "info":
				$sender->sendMessage(T::WHITE."You".T::RED."Tube".T::WHITE." :".T::YELLOW." BEcraft GamePlay\n".T::GREEN."Version".T::WHITE." : ".T::YELLOW."0.5\n".T::BLUE."For any question ".T::AQUA."@becraft_mcpe");
				return true;
				break;
				
				case "coins":
				if($sender instanceof Player){
					$sender->sendMessage(T::GOLD."Your coins: ".T::GREEN."".$config->get($sender->getName(), $this->getCoins($sender)));
					}
					return true;
					break;
					
					case "give":
					if($sender->isOp()){
						$player = $sender->getServer()->getPlayer($args[1]);
						$amount = $args[2];
						if($player instanceof Player && $player->isOnline()){
						$this->setCoins($player, $config->get($player->getName(), $this->getCoins($player))+$amount);
						$config->set($player->getName(), $this->getCoins($player));
                        $config->save();
                        $player->sendMessage(T::YELLOW."You got ".T::GREEN."".$amount." ".T::YELLOW."by: ".T::AQUA."".$sender->getName()."\n".T::GOLD."Now your ".T::YELLOW."Coins ".T::GOLD."balance is: ".T::GREEN."".$config->get($player, $this->getCoins($player)));
                        $sender->sendMessage($player->getName()." ".T::GOLD."got ".T::GREEN."".$amount." ".T::GOLD."Coins");
						}else{
						$sender->sendMessage(T::RED."That player is not online, check other way...");
							}
						}else{
							$sender->sendMessage(T::RED."You dont have permission to use this command :P");
							}
						return true;
						break;
					
					case "remove":
					if($sender->isOp()){
						$player = $sender->getServer()->getPlayer($args[1]);
						$amount = $args[2];
						if($player instanceof Player && $player->isOnline()){
						$this->setCoins($player, $config->get($player->getName(), $this->getCoins($player))-$amount);
						$config->set($player->getName(), $this->getCoins($player));
                        $config->save();
                        $player->sendMessage(T::RED."You lost ".T::GREEN."".$amount." ".T::RED."taken by: ".T::AQUA."".$sender->getName()."\n".T::GOLD."Now your ".T::YELLOW."Coins ".T::GOLD."balance is: ".T::GREEN."".$config->get($player, $this->getCoins($player)));
                        $sender->sendMessage($player->getName()." ".T::RED."lost ".T::GREEN."".$amount." ".T::GOLD."Coins");
						}else{
						$sender->sendMessage(T::RED."That player is not online, check other way...");
							}
						}else{
							$sender->sendMessage(T::RED."You dont have permission to use this command :P");
							}
						return true;
						break;
						
					case "buy":
					if($sender instanceof Player){
					if($args[1] == "ak47"){
						if($config->get($sender->getName(), $this->getCoins($sender)) >= $prices->get("Ak47")){
							$ak = Item::get(346, 0, 1);
							$akname = $ak->setCustomName(T::GREEN."Ak47\n".T::GOLD."Bullet: ".T::YELLOW."Snowballs\n".T::GOLD."Damage: ".T::YELLOW."".$prices->get("Snowball-Damage"));
							$sender->getInventory()->addItem($akname);
							$this->setCoins($sender, $config->get($sender->getName(), $this->getCoins($sender))-$prices->get("Ak47"));
							$config->set($sender->getName(), $this->getCoins($sender));
							$config->save();
							$sender->sendMessage(T::GOLD."Congratulations! \n".T::GREEN."You bought".T::AQUA." AK47 ".T::GREEN."gun, so go and kill all players!");
							}else{
								$sender->sendMessage(T::RED."You don't have enough ".T::GOLD."Coins ".T::RED."to buy this item...");
								}
						}
						else if($args[1] == "p90"){
						if($config->get($sender->getName(), $this->getCoins($sender)) >= $prices->get("P90")){
							$p = Item::get(346, 0, 1);
							$pname = $p->setCustomName(T::GREEN."P90\n".T::GOLD."Bullet: ".T::YELLOW."Snowballs\n".T::GOLD."Damage: ".T::YELLOW."".$prices->get("Snowball-Damage")."\n".T::GOLD."Shoots: ".T::YELLOW."x2");
							$sender->getInventory()->addItem($pname);
							$this->setCoins($sender, $config->get($sender->getName(), $this->getCoins($sender))-$prices->get("P90"));
							$config->set($sender->getName(), $this->getCoins($sender));
							$config->save();
							$sender->sendMessage(T::GOLD."Congratulations! \n".T::GREEN."You bought".T::AQUA." P90 ".T::GREEN."gun, so go and kill all players!");
							}else{
								$sender->sendMessage(T::RED."You don't have enough ".T::GOLD."Coins ".T::RED."to buy this item...");
								}
						}
						else if($args[1] == "m79"){
						if($config->get($sender->getName(), $this->getCoins($sender)) >= $prices->get("M79")){
							$p = Item::get(346, 0, 1);
							$pname = $p->setCustomName(T::GREEN."M79\n".T::GOLD."Bullet: ".T::YELLOW."TNT\n".T::GOLD."Damage: ".T::YELLOW."high \n".T::GOLD."Shoots: ".T::YELLOW."x1");
							$sender->getInventory()->addItem($pname);
							$this->setCoins($sender, $config->get($sender->getName(), $this->getCoins($sender))-$prices->get("M79"));
							$config->set($sender->getName(), $this->getCoins($sender));
							$config->save();
							$sender->sendMessage(T::GOLD."Congratulations! \n".T::GREEN."You bought".T::AQUA." M79 ".T::GREEN."gun, so go and kill all players!");
							}else{
								$sender->sendMessage(T::RED."You don't have enough ".T::GOLD."Coins ".T::RED."to buy this item...");
								}
						}
						else if($args[1] == "uzi"){
						if($config->get($sender->getName(), $this->getCoins($sender)) >= $prices->get("UZI")){
							$p = Item::get(346, 0, 1);
							$pname = $p->setCustomName(T::GREEN."UZI\n".T::GOLD."Bullet: ".T::YELLOW."Eggs\n".T::GOLD."Damage: ".T::YELLOW."".$prices->get("Egg-Damage")."\n".T::GOLD."Shoots: ".T::YELLOW."x1");
							$sender->getInventory()->addItem($pname);
							$this->setCoins($sender, $config->get($sender->getName(), $this->getCoins($sender))-$prices->get("UZI"));
							$config->set($sender->getName(), $this->getCoins($sender));
							$config->save();
							$sender->sendMessage(T::GOLD."Congratulations! \n".T::GREEN."You bought".T::AQUA." UZI ".T::GREEN."gun, so go and kill all players!");
							}else{
								$sender->sendMessage(T::RED."You don't have enough ".T::GOLD."Coins ".T::RED."to buy this item...");
								}
						}
						else if($args[1] == "scarab"){
						if($config->get($sender->getName(), $this->getCoins($sender)) >= $prices->get("Scarab-Knife")){
							$p = Item::get(318, 0, 1);
							$pname = $p->setCustomName(T::GREEN."Scarab\n".T::GOLD."Type: ".T::YELLOW."Knife");
							$sender->getInventory()->addItem($pname);
							$this->setCoins($sender, $config->get($sender->getName(), $this->getCoins($sender))-$prices->get("Scarab-Knife"));
							$config->set($sender->getName(), $this->getCoins($sender));
							$config->save();
							$sender->sendMessage(T::GOLD."Congratulations! \n".T::GREEN."You bought".T::AQUA." Scarab ".T::GREEN."knife, so go and kill all players!");
							}else{
								$sender->sendMessage(T::RED."You don't have enough ".T::GOLD."Coins ".T::RED."to buy this item...");
								}
						}
						}
						return true;
						break;
						
						case "bullet":
						if($sender instanceof Player){
							if($args[1] == "snowball"){
								if($config->get($sender->getName(), $this->getCoins($sender)) >= $prices->get("Snowball-Bullet")){
									$sender->getInventory()->addItem(Item::get(Item::SNOWBALL, 0, 64));
									$this->setCoins($sender, $config->get($sender->getName(), $this->getCoins($sender))-$prices->get("Snowball-Bullet"));
							        $config->set($sender->getName(), $this->getCoins($sender));
							        $config->save();
							$sender->sendMessage(T::GOLD."You bough some ".T::GREEN."Snowballs".T::GOLD."Bullets");
									}else{
										$sender->sendMessage(T::RED."You don't have enough ".T::GOLD."Coins ".T::RED."to buy this item...");
										}
								}
								else if($args[1] == "egg"){
								if($config->get($sender->getName(), $this->getCoins($sender)) >= $prices->get("Egg-Bullet")){
									$sender->getInventory()->addItem(Item::get(344, 0, 64));
									$this->setCoins($sender, $config->get($sender->getName(), $this->getCoins($sender))-$prices->get("Egg-Bullet"));
							        $config->set($sender->getName(), $this->getCoins($sender));
							        $config->save();
							$sender->sendMessage(T::GOLD."You bough some ".T::GREEN."Eggs".T::GOLD."Bullets");
									}else{
										$sender->sendMessage(T::RED."You don't have enough ".T::GOLD."Coins ".T::RED."to buy this item...");
										}
										}
										else if($args[1] == "tnt"){
								if($config->get($sender->getName(), $this->getCoins($sender)) >= $prices->get("TNT-Bullet")){
									$sender->getInventory()->addItem(Item::get(46, 0, 10));
									$this->setCoins($sender, $config->get($sender->getName(), $this->getCoins($sender))-$prices->get("TNT-Bullet"));
							        $config->set($sender->getName(), $this->getCoins($sender));
							        $config->save();
							$sender->sendMessage(T::GOLD."You bough some ".T::GREEN."TNT".T::GOLD."Bullets");
									}else{
										$sender->sendMessage(T::RED."You don't have enough ".T::GOLD."Coins ".T::RED."to buy this item...");
										}
										}
										}
							return true;
							break;
					
				}//switch args 0
			}//isset
		        }//switch comd
	            }//command start
   }//end
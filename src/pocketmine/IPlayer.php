<?php

namespace pocketmine;

use pocketmine\permission\ServerOperator;

interface IPlayer extends ServerOperator
{

    public function isOnline();

    public function getName();

    public function isBanned();

    public function setBanned($banned);

    public function isWhitelisted();

    public function setWhitelisted($value);

    public function getPlayer();

    public function getFirstPlayed();

    public function getLastPlayed();

    public function hasPlayedBefore();
}

<?php

namespace Ideato\SSH;

interface ProxyInterface
{
    public function connect($host, $port);

    public function authByPassword($user, $pwd);
    public function authByPublicKey($user, $public_key_file, $private_key_file, $pwd);
    public function authByAgent($user);

    public function exec($cmd);
}
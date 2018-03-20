<?php

namespace app\models;

class Git
{

  public $url = 'https://github.com/wenhawk/fast_grub';

  public static function pull(){
    echo shell_exec('sh ../script.sh').'<br>';
  }

}

?>

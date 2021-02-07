<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2021/2/7
 * Time: 16:01
 */

namespace app\common\command;


use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class clearActive extends Command
{
    protected function configure()
    {
        $this->setName('clear_active')
            ->setDescription('清除管理活动记录')
            ->addOption('day', null, Option::VALUE_REQUIRED, "your name",60);
    }

    protected function execute(Input $input, Output $output)
    {
        $day = $input->getOption('day');
        dd(date('Y-m-d H:i:s',strtotime("-{$day} day")));
    }
}

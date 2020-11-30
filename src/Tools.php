<?php

namespace Blade\ServiceApp;

use Blade\Library\Config;
use Blade\Library\Console;

/**
 * Class Tools
 * @package Blade\ServiceApp
 */
class Tools
{
    /**
     * TODO：composer命令行脚本执行方法
     * - composer bsa help
     * - composer bsa init
     * - composer bsa require [serviceName]
     * - composer bsa remove [serviceName]
     */
    public static function script(): void
    {
        // TODO：命令行参数，0 composer文件路径，1 bsa，2 执行方法，3 参数...
        $action = (Console::get(2) ?? 'help') . 'Action';
        // TODO：执行方法是否合法，默认help
        if (!method_exists(__CLASS__, $action)) {
            $action = 'helpAction';
        }
        // TODO：执行
        call_user_func([__CLASS__, $action], Console::take(3));
    }

    /**
     * TODO：输出帮助文档
     */
    protected static function helpAction(): void
    {
        Console::println(
            "可用命令：\n%s\n%s\n%s\n%s",
            '- composer bsa help 帮助文档',
            '- composer bsa init 配置中控台',
            '- composer bsa require [serviceName] 引用微服务',
            '- composer bsa remove [serviceName] 移除微服务'
        );
    }

    /**
     * TODO：配置中控台
     */
    protected static function initAction(): void
    {
        // TODO：由于输入内容不多，所以采取单轮制输入方式
        $appName = Console::input('请输入应用名称：');
        // TODO：应用名必须为1~32位mb字符
        if (empty($appName) || 32 < mb_strlen($appName)) {
            Console::println('<R~！！应用名必须是1~32位长度字符，不限中英文数字和符号~>');
            exit(0);
        }
        // TODO：中控台地址
        $consoleUrl = Console::input('请输入中控台URL：');
        // TODO：验证中控台地址
        // code...
        // TODO：读取并写入配置文件
        $file = getcwd() . '/blade.json';
        $config = Config::read($file);
        $config->set('name', $appName);
        $config->set('consoleUrl', $consoleUrl);
        $config->set('appId', md5($appName));
        $config->set('secret', md5($consoleUrl));
        if (!Config::write($file, $config)) {
            Console::println('<R~！！配置文件写入失败，请检查文件【%s】是否有写入权限~>', $file);
            exit(0);
        } else {
            Console::println('配置完成');
        }
    }

    /**
     * TODO：引用微服务
     * @param string $name
     */
    protected static function requireAction(string $name): void
    {
        // TODO：读取配置
        $file = getcwd() . '/blade.json';
        $config = Config::read($file);
        // TODO：如果没有配置中控台时，让用户选择是否先配置中控台
        if (!$config->get('appId')) {
            $select = Console::input('当前未配置中控台，是否先配置[y/n]：');
            // TODO：配置中控台，默认/大小写y
            if (empty($select) || 'y' === strtolower($select)) {
                self::initAction();
                // TODO：重新读取配置
                $config = Config::read($file);
            }
        }
        if ($config->get('appId')) {
            // TODO：如果配置了中控台，则从中控台中获取微服务数据
            // code..
        } else {
            // TODO：没有配置中控台，则与微服务直连
            $interfaceUrl = Console::input('请输入微服务访问URL：');
            // code..
        }
    }
}
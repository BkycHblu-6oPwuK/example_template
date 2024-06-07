<?php
namespace Itb\Agents;

class Agent
{
    public static function AgentMethod()
    {

    }

    public static function exec()
    {
        static::AgentMethod();
        return '\\'.__METHOD__.'();';
    }
}
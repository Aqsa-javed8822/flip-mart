<?php

namespace RadiusTheme\SB\Dependencies\CodesVault\Howdyqb\Api;

interface UpdateInterface extends WhereClauseInterface
{
    function getSql();
    public function execute();
}

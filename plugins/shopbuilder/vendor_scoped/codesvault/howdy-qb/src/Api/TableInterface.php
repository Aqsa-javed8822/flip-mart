<?php

namespace RadiusTheme\SB\Dependencies\CodesVault\Howdyqb\Api;

interface TableInterface
{
    public function drop();
    public function dropIfExists();
    public function truncate();
}

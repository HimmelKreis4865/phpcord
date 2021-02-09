<?php

namespace phpcord\channel\embed\components;

use phpcord\channel\embed\ColorUtils;

interface ColorComponent {
    /**
     * Transfers a Component to a HEX Code
     *
     * @api
     *
     * @return string
     */
    public function toHex(): string;

    /**
     * Returns whether entered data or not
     *
     * @param mixed $data
     *
     * @return bool
     */
    public static function isValid($data): bool;
}



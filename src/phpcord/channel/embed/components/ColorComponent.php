<?php

namespace phpcord\channel\embed\components;

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
<?php

namespace phpcord\channel\embed\components;

class RGB implements ColorComponent {

    public const MIN_VAL = 0;
    public const MAX_VAL = 255;

    /* ---------| Color List for RGB Start |--------- */
    public const COLOR_RED = [255, 17, 0];
    public const COLOR_ORANGE = [255, 145, 0];
    public const COLOR_YELLOW = [255, 255, 0];
    public const COLOR_LIGHT_GREEN = [0, 255, 0];
    public const COLOR_GREEN = [0, 112, 0];
    public const AQUA = [0, 255, 255];
    public const LIGHT_BLUE = [0, 187, 255];
    public const DARK_BLUE = [0, 0, 255];
    public const WHITE = [255, 255, 255];
    public const BLACK = [0, 0, 0];

    /**
     * @param bool $asRGB
     *
     * @return array|RGB|null
     */
    public static function RANDOM(bool $asRGB = true) {
        $array = [mt_rand(self::MIN_VAL, self::MAX_VAL), mt_rand(self::MIN_VAL, self::MAX_VAL), mt_rand(self::MIN_VAL, self::MAX_VAL)];
        if ($asRGB) return self::fromArray($array);
        return $array;
    }

    /* ---------| Color List for RGB End |--------- */

    /** @var int $red */
    private $red;
    /** @var int $green */
    private $green;
    /** @var int $blue */
    private $blue;

    /**
     * RGB constructor.
     *
     * @param int|array $red
     * @param int $green
     * @param int $blue
     */
    public function __construct($red, int $green = 0, int $blue = 0) {
        if (is_array($red)) {
            $rgb = self::fromArray($red);
            if ($rgb === null) throw new \InvalidArgumentException("Could not parse rgb due to invalid input array!");
            $this->parseRGB($rgb);
            return;
        }
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
    }


    /**
     * @return int
     */
    public function getRed(): int {
        return $this->red;
    }

    /**
     * @return int
     */
    public function getGreen(): int {
        return $this->green;
    }

    /**
     * @return int
     */
    public function getBlue(): int {
        return $this->blue;
    }

    public function toArray(): array {
        return [$this->getRed(), $this->getGreen(), $this->getBlue()];
    }

    public function parseRGB(RGB $rgb) {
        $this->red = $rgb->red;
        $this->blue = $rgb->blue;
        $this->green = $rgb->green;
    }

    public static function fromArray(array $array): ?RGB {
        $array = array_filter($array, function ($key) {
            return (is_numeric($key) and (intval($key) >= self::MIN_VAL) and (intval($key) <= self::MAX_VAL));
        });
        if (count($array) < 3) return null;
        return new RGB(array_shift($array), array_shift($array), array_shift($array));
    }

    public function toHex(): string {
        return sprintf("#%02x%02x%02x", $this->red, $this->green, $this->blue);
    }

    public static function isValid($data): bool {
        return (is_array($data) and self::fromArray($data) instanceof static);
    }
}



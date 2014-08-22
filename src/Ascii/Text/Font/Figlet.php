<?php

namespace VitKutny\Ascii\Text\Font;

use VitKutny\Ascii\Text;
use VitKutny\Ascii\Text\Font;
use Nette\Utils\Strings;

final class Figlet implements Text\Font {

    const FIRST_ASCII_CHAR = 32;

    /**
     * @var array
     */
    private $file;

    /**
     * @var array
     */
    private $path;

    /**
     * @var array
     */
    private $metadata;

    /**
     * @param string $font
     * @throws Font\Exception
     */
    public function __construct($font) {
        $this->file = file($font);
        if (!$this->file) {
            throw new Font\Exception('Font not found');
        }
        $this->path = pathinfo($font);
        $this->parseMetadata();
    }

    /**
     * @return int
     */
    public function getHeight() {
        return $this->metadata['height'];
    }

    /**
     * @param string $character
     * @return array
     */
    public function getCharacter($character) {
        $offset = (ord($character) - self::FIRST_ASCII_CHAR) * $this->metadata['height'];
        $character = [];
        for ($row = 0; $row < $this->metadata['height']; $row++) {
            $character[] = str_split(str_replace([substr($this->metadata['type'], -1), '@', PHP_EOL], [' ', '', ''], $this->file[$offset + $row]));
        }
        return $character;
    }

    /**
     * @return string
     */
    public function getName() {
        return Strings::webalize($this->path['filename']);
    }

    private function parseMetadata() {
        $keys = ['type', 'height', 'baseLine', 'maxLength', 'oldLayout', 'comments', 'direction', 'fullLayout', 'codeTagCount'];
        $this->metadata = array_combine($keys, explode(" ", array_shift($this->file)));
        $this->file = array_slice($this->file, $this->metadata['comments']);
    }

}

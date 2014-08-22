<?php

namespace VitKutny\Ascii\Text;

interface Font {

    /**
     * @return int
     */
    public function getHeight();

    /**
     * @param string $character
     * @return array
     */
    public function getCharacter($character);

    /**
     * @return string
     */
    public function getName();
}

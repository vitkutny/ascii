<?php

namespace VitKutny\Ascii\Text;

use VitKutny\Ascii;
use VitKutny\Ascii\Text;

final class Renderer extends Ascii\Renderer {

    /**
     * @var string
     */
    private $text;

    /**
     * @var Text\Font
     */
    private $font;

    /**
     * @var Callable[]
     */
    private $filters = [];

    /**
     * @var Text\Wrapper
     */
    private $string;

    /**
     * @var Text\Wrapper
     */
    private $line;

    /**
     * @var Text\Wrapper
     */
    private $lineBreak;

    /**
     * @var Text\Wrapper
     */
    private $characterLine;

    /**
     * @var Text\Wrapper
     */
    private $characterSeparator;

    /**
     * @var Text\Wrapper
     */
    private $pixel;

    /**
     * @var boolean
     */
    private $consoleMode;

    /**
     * @param string $text
     */
    public function __construct($text) {
        $this->text = $text;
        $this->string = $this->line = $this->lineBreak = $this->characterLine = $this->characterSeparator = $this->pixel = Text\Wrapper::el();
    }

    /**
     * @return Text\Wrapper
     */
    public function getString() {
        $string = clone $this->string;
        for ($line = 0; $line < $this->font->getHeight(); $line++) {
            $string->add($this->getLine($line));
        }
        $string->class = [$string->class, 'ascii-string', 'ascii-font-' . $this->font->getName()];
        $html = $string->getHtml();
        foreach ($this->filters as $filter) {
            $html = call_user_func($filter, $html);
        }
        if ($this->consoleMode) {
            $string->setName(NULL);
            $html = strip_tags($html);
        }
        return $string->setHtml($html);
    }

    /**
     * @param int $row
     * @return Text\Wrapper
     */
    private function getLine($row) {
        $line = clone $this->line;
        $line->class = [$line->class, 'ascii-line'];
        foreach (str_split($this->text) as $key => $value) {
            $character = $this->font->getCharacter($value);
            if (!$character) {
                continue;
            }
            if ($key) {
                $line->add($this->characterSeparator);
            }
            $characterLine = $this->getCharacterLine($character[$row]);
            $characterLine->class = [$characterLine->class, 'ascii-character', 'ascii-character-' . ord($value)];
            $line->add($characterLine);
        }
        return $line->add($this->consoleMode ? PHP_EOL : $this->lineBreak);
    }

    /**
     * @param int $row
     * @return Text\Wrapper
     */
    private function getCharacterLine($row) {
        $line = clone $this->characterLine;
        foreach ($row as $value) {
            $line->add($this->getPixel($value));
        }
        return $line;
    }

    /**
     * @param string $value
     * @return Text\Wrapper
     */
    private function getPixel($value) {
        $pixel = clone $this->pixel;
        $pixel->class = [$pixel->class, 'ascii-pixel'];
        return $pixel->add($value);
    }

    /**
     * @param Callable $filter
     * @return Text\Renderer
     */
    public function addFilter(Callable $filter) {
        $this->filters[] = $filter;
        return $this;
    }

    /**
     * @param Text\Font $font
     * @return Text\Renderer
     */
    public function setFont(Text\Font $font) {
        $this->font = $font;
        return $this;
    }

    /**
     * @param Text\Wrapper $string
     * @return Text\Renderer
     */
    public function setStringWrapper(Text\Wrapper $string) {
        $this->string = $string;
        return $this;
    }

    /**
     * @param Text\Wrapper $line
     * @return Text\Renderer
     */
    public function setLineWrapper(Text\Wrapper $line) {
        $this->line = $line;
        return $this;
    }

    /**
     * @param Text\Wrapper $break
     * @return Text\Renderer
     */
    public function setLineBreak(Text\Wrapper $break) {
        $this->lineBreak = $break;
        return $this;
    }

    /**
     * @param Text\Wrapper $line
     * @return Text\Renderer
     */
    public function setCharacterLineWrapper(Text\Wrapper $line) {
        $this->characterLine = $line;
        return $this;
    }

    /**
     * @param Text\Wrapper $separator
     * @return Text\Renderer
     */
    public function setCharacterSeparator(Text\Wrapper $separator) {
        $this->characterSeparator = $separator;
        return $this;
    }

    /**
     * @param Text\Wrapper $pixel
     * @return Text\Renderer
     */
    public function setPixelWrapper(Text\Wrapper $pixel) {
        $this->pixel = $pixel;
        return $this;
    }

    /**
     * @return Text\Renderer
     */
    public function setConsoleMode() {
        $this->consoleMode = TRUE;
        return $this;
    }

    /**
     * @return Text\Renderer
     */
    public function render() {
        echo $this->getString();
        return $this;
    }

    /**
     * @return string
     */
    public function __tostring() {
        return (string) $this->getString();
    }

}

<?php

declare (strict_types=1);
/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FCFProVendor\Monolog\Handler;

use FCFProVendor\Monolog\Formatter\FormatterInterface;
use FCFProVendor\Monolog\Formatter\LineFormatter;
/**
 * Helper trait for implementing FormattableInterface
 *
 * This trait is present in monolog 1.x to ease forward compatibility.
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
trait FormattableHandlerTrait
{
    /**
     * @var FormatterInterface
     */
    protected $formatter;
    /**
     * {@inheritdoc}
     * @suppress PhanTypeMismatchReturn
     */
    public function setFormatter(\FCFProVendor\Monolog\Formatter\FormatterInterface $formatter) : \FCFProVendor\Monolog\Handler\HandlerInterface
    {
        $this->formatter = $formatter;
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function getFormatter() : \FCFProVendor\Monolog\Formatter\FormatterInterface
    {
        if (!$this->formatter) {
            $this->formatter = $this->getDefaultFormatter();
        }
        return $this->formatter;
    }
    /**
     * Gets the default formatter.
     *
     * Overwrite this if the LineFormatter is not a good default for your handler.
     */
    protected function getDefaultFormatter() : \FCFProVendor\Monolog\Formatter\FormatterInterface
    {
        return new \FCFProVendor\Monolog\Formatter\LineFormatter();
    }
}

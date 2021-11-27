<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FCFProVendor\Monolog\Handler;

use FCFProVendor\Monolog\Logger;
use FCFProVendor\Monolog\Formatter\NormalizerFormatter;
/**
 * Logs to a MongoDB database.
 *
 * usage example:
 *
 *   $log = new Logger('application');
 *   $mongodb = new MongoDBHandler(new \Mongo("mongodb://localhost:27017"), "logs", "prod");
 *   $log->pushHandler($mongodb);
 *
 * @author Thomas Tourlourat <thomas@tourlourat.com>
 */
class MongoDBHandler extends \FCFProVendor\Monolog\Handler\AbstractProcessingHandler
{
    protected $mongoCollection;
    public function __construct($mongo, $database, $collection, $level = \FCFProVendor\Monolog\Logger::DEBUG, $bubble = \true)
    {
        if (!($mongo instanceof \MongoClient || $mongo instanceof \Mongo || $mongo instanceof \FCFProVendor\MongoDB\Client)) {
            throw new \InvalidArgumentException('MongoClient, Mongo or MongoDB\\Client instance required');
        }
        $this->mongoCollection = $mongo->selectCollection($database, $collection);
        parent::__construct($level, $bubble);
    }
    protected function write(array $record)
    {
        if ($this->mongoCollection instanceof \FCFProVendor\MongoDB\Collection) {
            $this->mongoCollection->insertOne($record["formatted"]);
        } else {
            $this->mongoCollection->save($record["formatted"]);
        }
    }
    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter()
    {
        return new \FCFProVendor\Monolog\Formatter\NormalizerFormatter();
    }
}

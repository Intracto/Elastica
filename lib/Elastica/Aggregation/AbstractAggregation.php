<?php
namespace Elastica\Aggregation;

use Elastica\Exception\InvalidException;
use Elastica\NameableInterface;
use Elastica\Param;

abstract class AbstractAggregation extends Param implements NameableInterface
{
    /**
     * @var string The name of this aggregation
     */
    protected $_name;

    /**
     * @var array Subaggregations belonging to this aggregation
     */
    protected $_aggs = [];

    /**
     * @var array Metadata belonging to this aggregation
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/agg-metadata.html
     */
    protected $_meta = [];

    /**
     * @param string $name the name of this aggregation
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * Set the name of this aggregation.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->_name = $name;

        return $this;
    }

    /**
     * Retrieve the name of this aggregation.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Retrieve all subaggregations belonging to this aggregation.
     *
     * @return array
     */
    public function getAggs()
    {
        return $this->_aggs;
    }

    /**
     * Add a sub-aggregation.
     *
     * @param AbstractAggregation $aggregation
     *
     * @throws \Elastica\Exception\InvalidException
     *
     * @return $this
     */
    public function addAggregation(AbstractAggregation $aggregation)
    {
        if ($aggregation instanceof GlobalAggregation) {
            throw new InvalidException('Global aggregators can only be placed as top level aggregators');
        }

        $this->_aggs[] = $aggregation;

        return $this;
    }

    /**
     * Add metadata.
     *
     * @param mixed $metadata
     *   The metadata to add.
     *
     * @return $this
     */
    public function addMetaData($metadata) {
        $this->_meta = array_merge($this->_meta, (array) $metadata);

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        if (array_key_exists('global_aggregation', $array)) {
            // compensate for class name GlobalAggregation
            $array = ['global' => new \stdClass()];
        }
        if (count($this->_aggs)) {
            $array['aggs'] = $this->_convertArrayable($this->_aggs);
        }
        if (count($this->_meta)) {
            $array['meta'] = $this->_meta;
        }

        return $array;
    }
}

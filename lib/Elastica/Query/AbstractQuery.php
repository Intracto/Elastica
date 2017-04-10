<?php
namespace Elastica\Query;

use Elastica\Param;

/**
 * Abstract query object. Should be extended by all query types.
 *
 * @author Nicolas Ruflin <spam@ruflin.com>
 */
abstract class AbstractQuery extends Param
{
    /**
     * The name of the query.
     *
     * @var string
     */
    protected $_name;

    /**
     * Get the name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Set the name.
     *
     * @param string $_name
     *   The name to be set.
     *
     * @return $this
     */
    public function setName($_name)
    {
        $this->_name = $_name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $data = parent::toArray();
        if ($this->_name) {
            $baseName = $this->_getBaseName();
            if (is_array($data[$baseName])) {
                $data[$baseName]['_name'] = $this->_name;
            }
        }

        return $data;
    }
}

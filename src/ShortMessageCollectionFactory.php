<?php

namespace BahriCanli\Netgsm;

/**
 * Class ShortMessageCollectionFactory.
 */
class ShortMessageCollectionFactory implements ShortMessageCollectionFactoryInterface
{
    /**
     * Create a new short message collection.
     *
     * @return ShortMessageCollection
     */
    public function create()
    {
        return new ShortMessageCollection();
    }
}

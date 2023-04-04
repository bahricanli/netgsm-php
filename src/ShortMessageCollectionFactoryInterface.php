<?php

namespace BahriCanli\Netgsm;

/**
 * Interface ShortMessageCollectionFactoryInterface.
 */
interface ShortMessageCollectionFactoryInterface
{
    /**
     * Create a new short message collection.
     *
     * @return ShortMessageCollection
     */
    public function create();
}

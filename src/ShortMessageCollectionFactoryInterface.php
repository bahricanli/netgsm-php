<?php

namespace BahriCanli\netgsm;

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

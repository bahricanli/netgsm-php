<?php

namespace BahriCanli\JetSms\Test;

use BahriCanli\JetSms\ShortMessageCollection;
use Mockery as M;
use PHPUnit_Framework_TestCase;
use BahriCanli\JetSms\ShortMessageCollectionFactory;

class ShortMessageCollectionFactoryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        M::close();

        parent::tearDown();
    }

    public function test_it_creates_new_short_message_collections()
    {
        $shortMessageCollectionFactory = new ShortMessageCollectionFactory();

        $shortMessageCollection = $shortMessageCollectionFactory->create();

        $this->assertInstanceOf(ShortMessageCollection::class, $shortMessageCollection);
    }
}

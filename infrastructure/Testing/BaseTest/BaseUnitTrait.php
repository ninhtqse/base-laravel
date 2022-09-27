<?php

namespace Infrastructure\Testing\BaseTest;

trait BaseUnitTrait
{
    public function result($validate)
    {
        if ($validate->fails()) {
            $this->assertTrue($validate->fails());
        } else {
            $this->assertFalse($validate->fails());
        }
    }
}

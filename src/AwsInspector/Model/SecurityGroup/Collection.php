<?php

namespace AwsInspector\Model\SecurityGroup;

class Collection extends \SplObjectStorage
{

    public function getFirst() {
        $this->rewind();
        return $this->current();
    }

}
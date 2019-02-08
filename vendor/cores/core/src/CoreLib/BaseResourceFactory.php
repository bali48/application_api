<?php
namespace CoreLib;

class BaseResourceFactory
{
    public function __invoke($services)
    {
        return new BaseResource();
    }
}

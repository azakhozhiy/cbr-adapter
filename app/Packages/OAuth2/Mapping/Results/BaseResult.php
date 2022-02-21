<?php

namespace App\Packages\OAuth2\Mapping\Results;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

abstract class BaseResult implements Arrayable, Jsonable, JsonSerializable
{
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}

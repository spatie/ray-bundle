<?php

namespace Spatie\RayBundle\Payloads;

use Spatie\Ray\Payloads\Payload;

class ExecutedQueryPayload extends Payload
{
    protected array $query;

    public function __construct(array $query)
    {
        $this->query = $query;
    }

    public function getType(): string
    {
        return 'executed_query';
    }

    public function getContent(): array
    {
        return [
            'sql' => $this->query['sql'],
            'bindings' => [],
            'connection_name' => $this->query['connection_name'],
            'time' => $this->query['time'],

        ];
    }
}

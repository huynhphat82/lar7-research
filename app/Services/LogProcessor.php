<?php

namespace App\Services;

class LogProcessor
{
    /**
     * Customize log content
     *
     * @param  array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        // Add some extra information to record
        $record['extra'] = [
            'user_id' => auth()->user() ? auth()->user()->id : null,
            'origin' => request()->headers->get('origin'),
            'ip' => request()->server('REMOTE_ADDR'),
            'user_agent' => request()->server('HTTP_USER_AGENT')
        ];

        return $record;
    }
}

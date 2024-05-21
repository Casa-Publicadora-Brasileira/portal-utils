<?php

namespace CasaPublicadoraBrasileira\PortalUtils;

class NotificationHelper
{
    private $message;
    private $reference;
    private $userIds = [];
    private $grades = [];
    private $groups = [];
    private $groupUsers = [];

    public function __construct(string $message)
    {
        $this->message = $message;
    }


    public static function builder(string $message): NotificationHelper
    {
        return new NotificationHelper($message);
    }

    public function message(string $message): NotificationHelper
    {
        $this->message = $message;
        return $this;
    }

    public function reference(mixed $reference): NotificationHelper
    {
        $this->reference = $reference;
        return $this;
    }

    public function user(array $userIds): NotificationHelper
    {
        $this->userIds = $userIds;
        return $this;
    }

    public function grade(array $grades): NotificationHelper
    {
        $this->grades = $grades;
        return $this;
    }

    public function group(array $groups): NotificationHelper
    {
        $this->groups = $groups;
        return $this;
    }

    public function groupUser(array $groupUsers): NotificationHelper
    {
        $this->groupUsers = $groupUsers;
        return $this;
    }

    public function build()
    {
        return  [
            'message' => $this->message,
            'origin' => env('APP_NAME', 'unknown'),
            'reference' => $this->reference,
            'userId' => $this->userIds,
            'grade' => $this->grades,
            'group' => $this->groups,
            'groupUser' => $this->groupUsers
        ];
    }
}

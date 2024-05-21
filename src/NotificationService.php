<?php

namespace CasaPublicadoraBrasileira\PortalUtils;

class NotificationService
{
    private $message;
    private $origin;
    private $reference;
    private $userIds = [];
    private $grades = [];
    private $groups = [];
    private $groupUsers = [];

    public function __construct(string $message)
    {
        $this->message = $message;
    }


    public static function builder(string $message): NotificationService
    {
        return new NotificationService($message);
    }

    public function message(string $message): NotificationService
    {
        $this->message = $message;
        return $this;
    }

    public function origin(string $origin): NotificationService
    {
        $this->origin = $origin;
        return $this;
    }

    public function reference(mixed $reference): NotificationService
    {
        $this->reference = $reference;
        return $this;
    }

    public function user(array $userIds): NotificationService
    {
        $this->userIds = $userIds;
        return $this;
    }

    public function grade(array $grades): NotificationService
    {
        $this->grades = $grades;
        return $this;
    }

    public function group(array $groups): NotificationService
    {
        $this->groups = $groups;
        return $this;
    }

    public function groupUser(array $groupUsers): NotificationService
    {
        $this->groupUsers = $groupUsers;
        return $this;
    }

    public function build()
    {
        if(!empty($this->userIds)) {
            $userIds = $this->userIds;
        }

        if(!empty($this->grades)) {
            $grades = $this->grades;
        }

        if(!empty($this->groups)) {
            $groups = $this->groups;
        }

        if(!empty($this->groupUsers)) {
            $groupUsers = $this->groupUsers;
        }

        return  [
                    'message' => $this->message,
                    'origem' => $this->origin,
                    'reference' => [
                        'type' => $this->reference['type'],
                        'id' => $this->reference['id'],
                    ],
                    'userId' => $userIds ?? [],
                    'grade' => $grades ?? [],
                    'group' => $groups ?? [],
                    'groupUser' => $groupUsers ?? []
                ];
    }
}

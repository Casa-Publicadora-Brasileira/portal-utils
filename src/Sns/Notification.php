<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Sns;

use Aws\Result;
use CasaPublicadoraBrasileira\PortalUtils\Sns\NotificationService;
use Illuminate\Support\Str;

class Notification
{

    private $message;
    private $reference;
    private $responsibles;
    private $userIds = [];
    private $grades = [];
    private $groups = [];
    private $groupUsers = [];

    public function __construct() {}


    public static function notify(): Notification
    {
        return new Notification();
    }

    public function message(string $message): Notification
    {
        $this->message = $message;
        return $this;
    }

    public function reference(mixed $reference): Notification
    {
        $this->reference = $reference;
        return $this;
    }

    public function withResponsibles(bool $responsibles): Notification
    {
        $this->responsibles = $responsibles;
        return $this;
    }

    public function users(array $userIds): Notification
    {
        $this->userIds = $userIds;
        return $this;
    }

    public function grades(array $grades): Notification
    {
        $this->grades = $grades;
        return $this;
    }

    public function groups(array $groups): Notification
    {
        $this->groups = $groups;
        return $this;
    }

    public function groupsUser(array $groupUsers): Notification
    {
        $this->groupUsers = $groupUsers;
        return $this;
    }

    public function push(string $origin = null): ?Result
    {
        $data =   [
            'message' => $this->message,
            'origin' => Str::lower($origin ?? env('APP_NAME', 'unknown')),
            'reference' => $this->reference,
            'withResponsibles' => $this->responsibles,
            'userIds' => $this->safeArray($this->userIds),
            'grades' => $this->safeArray($this->grades),
            'groups' => $this->safeArray($this->groups),
            'groupsUser' => $this->safeArray($this->groupUsers)
        ];
        return NotificationService::notify($data);
    }

    private function safeArray($data)
    {
        return empty($data) ? [] : $data;
    }
}

<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Notifications;

use Aws\Result;
use CasaPublicadoraBrasileira\PortalUtils\Messaging\Topic;
use Illuminate\Support\Str;

class Notification
{
    private $message;
    private $reference;
    private $responsibles;
    private $origin;
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

    public function origin(OriginEnum $origin): Notification
    {
        $this->origin = $origin;

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

    public function push(): ?Result
    {
        $message = [
            'message' => $this->message,
            'origin' => Str::lower($this->safe($this->origin->value, env('APP_NAME', 'unknown'))),
            'reference' => $this->reference,
            'with_responsibles' => $this->responsibles || false,
            'user_ids' => $this->safe($this->userIds),
            'grades' => $this->safe($this->grades),
            'groups' => $this->safe($this->groups),
            'groups_user' => $this->safe($this->groupUsers),
        ];

        return Topic::send(arn: config('services.sns.topic'), message: $message);
    }

    private function safe($data, $default = [])
    {
        return empty($data) ? $default : $data;
    }
}

<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain;

/**
 * Class User (Aggregate)
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain
 */
final class User
{
    /** @var UserId */
    private $id;

    /** @var UserName */
    private $name;

    /**
     * User constructor.
     * @param UserId $id
     * @param UserName $name
     */
    public function __construct(UserId $id, UserName $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return UserId
     */
    public function id(): UserId
    {
        return $this->id;
    }

    /**
     * @return UserName
     */
    public function name(): UserName
    {
        return $this->name;
    }
}
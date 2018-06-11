<?php

namespace Atlassian\JiraRest\Helpers;

use Atlassian\JiraRest\Helpers\Agile\Board;
use Atlassian\JiraRest\Helpers\Agile\Boards;
use Atlassian\JiraRest\Helpers\Agile\Sprint;

class Agile
{
    protected $userId;

    public function __construct($userId = null)
    {
        $this->userId = $userId;
    }

    /**
     * @return \Atlassian\JiraRest\Helpers\Agile\Boards
     */
    public function boards()
    {
        return new Boards($this->userId);
    }

    /**
     * @param int $boardId
     *
     * @return \Atlassian\JiraRest\Helpers\Agile\Board
     */
    public function board($boardId)
    {
        return new Board($boardId, $this->userId);
    }

    /**
     * @param $sprintId
     *
     * @return \Atlassian\JiraRest\Helpers\Agile\Sprint
     */
    public function sprint($sprintId)
    {
        return new Sprint($sprintId, $this->userId);
    }
}
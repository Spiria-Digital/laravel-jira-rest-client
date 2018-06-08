<?php

namespace Atlassian\JiraRest\Helpers\Agile;

use Atlassian\JiraRest\Requests\Agile\Sprint\SprintRequest;

class Sprint
{

    /**
     * @var int
     */
    protected $sprint;

    /**
     * @var \Atlassian\JiraRest\Requests\Agile\Sprint\SprintRequest
     */
    protected $request;

    /**
     * Board constructor.
     *
     * @param int $sprintId
     */
    public function __construct($sprintId, $userId = null)
    {
        $this->sprint = $sprintId;
        $this->request = app(SprintRequest::class, ["userId" => $userId]);
    }

    /**
     * @param bool $assoc
     *
     * @return array|\stdClass
     * @throws \Atlassian\JiraRest\Exceptions\JiraClientException
     * @throws \Atlassian\JiraRest\Exceptions\JiraNotFoundException
     * @throws \Atlassian\JiraRest\Exceptions\JiraUnauthorizedException
     */
    public function get($assoc = true)
    {
        $response = $this->request->get($this->sprint);

        return json_decode($response->getBody(), $assoc);
    }
}

<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints;

use App\HmrcApi\Endpoints\ApiAgentAuthorisationTestSupport;
use Framework\Controller;

class AgentAuthorisationTestSupport extends Controller
{

    public function __construct(private ApiAgentAuthorisationTestSupport $apiAgentAuthorisationTestSupport) {}

    public function authorise()
    {
        $invitation_id = $this->request->get['id'];

        $this->apiAgentAuthorisationTestSupport->accept($invitation_id);
    }

    public function reject()
    {
        $invitation_id = $this->request->get['id'];

        $this->apiAgentAuthorisationTestSupport->reject($invitation_id);
    }
}

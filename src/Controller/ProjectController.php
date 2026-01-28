<?php
//Wrong namespace, chang to App/Controller
namespace Api\Controller;

use App\Model;
use App\Storage\DataStorage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController 
{
    /**
     * @var DataStorage
     */
    private $storage;

    public function __construct(DataStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param Request $request
     * 
     * @Route("/project/{id}", name="project", method="GET")
     */
    //improve method name, remove suffix action
    public function projectAction(Request $request)
    {
        //Improve all response with JsonResponse
        try {
            $project = $this->storage->getProjectById($request->get('id'));
            //Add HTTP Code
            //return new JsonResponse(['data' => $project->toJson()], 200);
            return new Response($project->toJson());
        } catch (Model\NotFoundException $e) {
            //return new JsonResponse(['error' => 'Project not found'], 404);
            return new Response('Not found', 404);
        } catch (\Throwable $e) {
            //Improve error message for debugging
            //return new JsonResponse(['error' => 'Something went wrong'], 500);
            return new Response('Something went wrong', 500);
        }
    }

    /**
     * @param Request $request
     *
     * @Route("/project/{id}/tasks", name="project-tasks", method="GET")
     */
    //improve method name, remove suffix action
    public function projectTaskPagerAction(Request $request)
    {
        //Validate for the id/limit/offset first
        //If id not valid, response error
        //$limit = (int)$request->get('limit') ?? 10;
        //$offset = (int)$request->get('offset') ?? 0;
        $tasks = $this->storage->getTasksByProjectId(
            $request->get('id'),
            $request->get('limit'),
            $request->get('offset')
        );
        //Improve all response with JsonResponse
        //Add HTTP Code
        return new Response(json_encode($tasks));
    }

    /**
     * @param Request $request
     *
     * Change method to POST follow README doc
     * @Route("/project/{id}/tasks", name="project-create-task", method="PUT")
     */
    //improve method name, remove suffix action
    public function projectCreateTaskAction(Request $request)
    {
        //Need to surround with try-catch because it can throw exception
        //NotFoundException => Project not found
        //Throwable => Internal server error
		$project = $this->storage->getProjectById($request->get('id'));
		if (!$project) {
            //Add status code for response (400)
			return new JsonResponse(['error' => 'Not found']);
		}
        //Add HTTP Code
		return new JsonResponse(
            //Avoid using superglobal $_REQUEST
            //Can decode the request first json_decode($request->getContent(), true)
            //Validate the data before create task
            //Add status code for response (201)
			$this->storage->createTask($_REQUEST, $project->getId())
		);
    }
}

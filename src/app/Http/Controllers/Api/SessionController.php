<?php

namespace Shrizzer\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use Shrizzer\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;
use Shrizzer\Exceptions\APIException;
use Shrizzer\Models\Session;
use Shrizzer\Repositories\SessionRepository;
use Shrizzer\Repositories\UrlRepository;
use Shrizzer\Services\SessionService;

/**
 * Class SessionController
 *
 * @package Shrizzer\Http\Controllers
 */
class SessionController extends Controller
{
    /**
     * @var SessionRepository
     */
    private $sessionRepository;

    /**
     * @var UrlRepository
     */
    private $urlRepository;

    /**
     * @var SessionService
     */
    private $sessionService;


    /**
     * @param SessionRepository $sessionRepository
     * @param UrlRepository $urlRepository
     * @param SessionService $sessionService
     */
    public function __construct(
        SessionRepository $sessionRepository,
        UrlRepository $urlRepository,
        SessionService $sessionService
    ) {
        $this->sessionRepository = $sessionRepository;
        $this->urlRepository = $urlRepository;
        $this->sessionService = $sessionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     * @throws APIException
     */
    public function index()
    {
        abort(404);

        $sessions = $this->sessionRepository->findAll();

        return Response::json($sessions);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     *
     * @throws APIException
     */
    public function show($id)
    {
        $session = $this->sessionRepository->getByKey($id);

        if (!$session) {
            throw new APIException('session not found', 404);
        }

        return Response::json($session);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     *
     * @throws APIException
     */
    public function store(Request $request)
    {
        $name = $request->get('name');
        $pw = $request->get('pw');

        if (!$name) {
            throw new APIException('name is required', 422);
        }

        if ($pw !== 'root12569') {
            // throw new APIException('password is not correct', 405);
        }

        $session = new Session();
        $session->name = $name;

        if (!$this->sessionRepository->save($session)) {
            throw new APIException('session could not be saved', 409);
        }

        $v = Validator::make(['url' => $request->get('url')], [
            'url' => 'required|url'
        ]);

        if ($v->fails()) {
            throw new APIException('the url is not valid', 422);
        }

        $urlParam = $request->get('url');

        if ($urlParam) {
            $url = $this->urlRepository->findOrCreateByUrl($urlParam);

            if ($url) {
                $this->sessionService->addUrlToSession($session, $url);
            }
        }

        return Response::json($session, 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     *
     * @throws APIException
     */
    public function update(Request $request, $id)
    {
        $session = $this->sessionRepository->getByKey($id);

        if (!$session) {
            throw new APIException('session not found', 404);
        }

        $name = $request->get('name');
        $description = $request->get('description');

        if (!$name) {
            throw new APIException('name must be set', 422);
        }

        $session->name = $name;
        $session->description = $description;
        $this->sessionRepository->save($session);

        return Response::json($session);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     *
     * @throws APIException
     */
    public function destroy($id)
    {
        if (!$this->sessionRepository->deleteByKey($id)) {
            throw new APIException('session not found', 404);
        }

        return Response::json(['success' => true]);
    }
}

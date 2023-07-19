<?php

namespace Winata\Core\Controller\Api;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use ResponseCode;
use Winata\Core\Http\Response;

class Controller
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * @param Arrayable|LengthAwarePaginator|CursorPaginator|array<int|string, mixed>|null $data
     * @param ResponseCode $rc
     * @param string|null $message
     * @return Response
     */
    public function response(
        Arrayable|LengthAwarePaginator|CursorPaginator|array|null $data = null,
        ResponseCode                                              $rc = ResponseCode::SUCCESS,
        string                                                    $message = null,
    ): Response
    {
        return new Response($rc, $data, $message);
    }
}
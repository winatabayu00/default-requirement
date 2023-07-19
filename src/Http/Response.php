<?php

namespace Winata\Core\Http;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Winata\Core\Concerns\Contracts\ResponseCode;

class Response implements Responsable
{
    /**
     * Response constructor.
     *
     * @param ResponseCode $code
     * @param Arrayable|array<int|string, mixed>|null $data
     * @param string|null $message
     */
    public function __construct(
        protected ResponseCode         $code = ResponseCode::SUCCESS,
        protected Arrayable|array|null $data = null,
        protected ?string              $message = null,
    )
    {
    }

    /**
     * Get response data.
     *
     * @return array<int|string, mixed>|null
     */
    public function getData(): ?array
    {
        return $this->data instanceof Arrayable ? $this->data->toArray() : $this->data;
    }

    /**
     * Get response message.
     *
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message ?? $this->code->message();
    }

    /**
     * Get response data.
     *
     * @return array<string, mixed>
     */
    public function getResponseData(): array
    {
        $resp = [
            'rc' => $this->code->name,
            'message' => $this->getMessage(),
            'timestamp' => now(),
        ];

        if ($this->data instanceof Paginator || $this->data instanceof CursorPaginator) {
            $paginatorPayload = $this->data->toArray();

            return array_merge(
                $resp,
                Arr::except($paginatorPayload, ['data']),
                ['payload' => $paginatorPayload['data']],
            );
        }

        if ($this->data instanceof Arrayable) {
            return array_merge($resp, ['payload' => $this->data->toArray()]);
        }

        return array_merge($resp, ['payload' => $this->data]);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \JsonException
     */
    public function toResponse($request): \Illuminate\Http\Response|JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        if ($request->expectsJson()) {
            return response()->json($this->getResponseData(), $this->code->httpCode());
        }

        return new \Illuminate\Http\Response(json_encode($this->getResponseData(), JSON_THROW_ON_ERROR), $this->code->httpCode());
    }
}

<?php

use ArchTech\Enums\From;
use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;
use Symfony\Component\HttpFoundation\Response;

enum ResponseCode
{
    use From;
    use Values;
    use Options;
    use InvokableCases;

    case SUCCESS;
    case ERR_VALIDATION;
    case ERR_AUTHENTICATION;
    case ERR_INVALID_IP_ADDRESS;
    case ERR_MISSING_SIGNATURE_HEADER;
    case ERR_INVALID_SIGNATURE_HEADER;
    case ERR_INVALID_OPERATION;
    case ERR_ENTITY_NOT_FOUND;
    case ERR_ROUTE_NOT_FOUND;
    case ERR_UNKNOWN;

    /**
     * Determine httpCode from response code.
     *
     * @return int
     */
    public function httpCode(): int
    {
        return match ($this) {
            self::SUCCESS => Response::HTTP_OK,

            self::ERR_MISSING_SIGNATURE_HEADER,
            self::ERR_INVALID_SIGNATURE_HEADER,
            self::ERR_INVALID_IP_ADDRESS,
            self::ERR_AUTHENTICATION => Response::HTTP_UNAUTHORIZED,

            self::ERR_VALIDATION => Response::HTTP_UNPROCESSABLE_ENTITY,

            self::ERR_INVALID_OPERATION => Response::HTTP_EXPECTATION_FAILED,

            self::ERR_ENTITY_NOT_FOUND,
            self::ERR_ROUTE_NOT_FOUND => Response::HTTP_NOT_FOUND,

            self::ERR_UNKNOWN => Response::HTTP_INTERNAL_SERVER_ERROR,

            default => Response::HTTP_BAD_REQUEST
        };
    }

    /**
     * Set error to readable message string.
     *
     * @return string
     */
    public function message(): string
    {
        return ucwords(strtolower(str_replace(['ERR_', '_'], ['', ' '], $this->name)));
    }
}

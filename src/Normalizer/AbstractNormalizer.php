<?php
declare(strict_types=1);


namespace App\Normalizer;

use App\Services\ExceptionNormalizerFormatterInterface;
use Exception;

abstract class AbstractNormalizer implements NormalizerInterface
{
    public function __construct(
        private array $exceptionTypes,
        protected ExceptionNormalizerFormatterInterface $exceptionNormalizerFormatter
    ) {}

    public function supports(Exception $exception): bool
    {
        return in_array($exception::class, $this->exceptionTypes);
    }
}
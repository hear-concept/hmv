<?php


namespace HearConcept\HMV;


use JetBrains\PhpStorm\Pure;

class HMVNumber
{
    const REGEX = '/^([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9])([0-9]+)$/';
    const REGEX_PLACEHOLDER = '/^([0-9]+)\.([0-9]+)\.([0-9xX]+)\.([0-9xX])([0-9xX]+)$/';

    /**
     * @var string|null
     */
    protected ?string $group = null;

    /**
     * @var string|null
     */
    protected ?string $applicationPlace = null;

    /**
     * @var string|null
     */
    protected ?string $subGroup = null;

    /**
     * @var string|null
     */
    protected ?string $type = null;

    /**
     * @var string|null
     */
    protected ?string $product = null;

    /**
     * HMVNumber constructor.
     * @param string|null $hmv
     */
    public function __construct(?string $hmv)
    {
        if (!is_null($hmv))
        {
            $regex = self::REGEX;
            preg_match($regex, $hmv, $matched, PREG_UNMATCHED_AS_NULL);

            $this->group = $matched[1] ?? null;
            $this->applicationPlace = $matched[2] ?? null;
            $this->subGroup = $matched[3] ?? null;
            $this->type = $matched[4] ?? null;
            $this->product = $matched[5] ?? null;
        }
    }

    /**
     * @return string
     */
    #[Pure]
    public function __toString(): string
    {
        if ($this->isNull())
            return '';

        return sprintf(
            "%s.%s.%s.%s%s",
            $this->group,
            $this->applicationPlace,
            $this->subGroup,
            $this->type,
            $this->product
        );
    }

    /**
     * Check if the HMV Number is null
     * @return bool
     */
    public function isNull(): bool
    {
        return !$this->group && !$this->applicationPlace && !$this->subGroup && !$this->type && !$this->product;
    }

    /**
     * Check if HMV number is tinnitus number
     * @return bool
     */
    #[Pure]
    public function isTinnitus(): bool
    {
        return !$this->isNull() && ($this->subGroup == "14" || $this->subGroup() == "24");
    }

    /**
     * @return string|null
     */
    public function group(): ?string
    {
        return $this->group;
    }

    /**
     * @return string|null
     */
    public function applicationPlace(): ?string
    {
        return $this->applicationPlace;
    }

    /**
     * @return string|null
     */
    public function subGroup(): ?string
    {
        return $this->subGroup;
    }

    /**
     * @return string|null
     */
    public function type(): ?string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function product(): ?string
    {
        return $this->product;
    }

    public static function validate(?string $number, bool $allowPlaceholder = false): bool
    {
        if (is_null($number))
            return false;

        if ($allowPlaceholder)
            preg_match(self::REGEX_PLACEHOLDER, $number, $matches);
        else
            preg_match(self::REGEX, $number, $matches);

        return !empty($matches);
    }
}

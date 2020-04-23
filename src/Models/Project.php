<?php
declare(strict_types=1);

namespace Quest1\Models;

class Project implements \JsonSerializable
{
    /**
     * @var int
     */
    private $id = 0;

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $link = '';

    /**
     * @var array
     */
    private $skills = [];

    /**
     * @var int
     */
    private $cost = 0;

    /**
     * @var string
     */
    private $currency = '';

    /**
     * @var string
     */
    private $employer_login = '';

    /**
     * @var string
     */
    private $employer_name = '';

    /**
     * Project constructor.
     *
     * @param \stdClass $data
     */
    public function __construct(\stdClass $data = null)
    {
        if (!$data) {
            return;
        }

        $this->setId((int) $data->id)
            ->setName($data->name ?? '')
            ->setLink($data->link ?? '')
            ->setSkills(json_decode($data->skills) ?? [])
            ->setCost((int) $data->cost)
            ->setCurrency($data->currency ?? '')
            ->setEmployerLogin($data->employer_login ?? '')
            ->setEmployerName($data->employer_name ?? '')
        ;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): Project
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): Project
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @param string $link
     *
     * @return self
     */
    public function setLink(string $link): Project
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return array
     */
    public function getSkills(): array
    {
        return $this->skills;
    }

    /**
     * @param array $skills
     *
     * @return self
     */
    public function setSkills(array $skills): Project
    {
        $this->skills = $skills;

        return $this;
    }

    /**
     * @param int $skill
     *
     * @return self
     */
    public function addSkill(int $skill): Project
    {
        $this->skills[] = $skill;

        return $this;
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }

    /**
     * @param int $cost
     *
     * @return self
     */
    public function setCost(int $cost): Project
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return self
     */
    public function setCurrency(string $currency): Project
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmployerLogin(): string
    {
        return $this->employer_login;
    }

    /**
     * @param string $employerLogin
     *
     * @return self
     */
    public function setEmployerLogin(string $employerLogin): Project
    {
        $this->employer_login = $employerLogin;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmployerName(): string
    {
        return $this->employer_name;
    }

    /**
     * @param string $employerName
     *
     * @return self
     */
    public function setEmployerName(string $employerName): Project
    {
        $this->employer_name = $employerName;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id'             => $this->getId(),
            'name'           => $this->getName(),
            'link'           => $this->getLink(),
            'skills'         => $this->getSkills(),
            'cost'           => $this->getCost(),
            'currency'       => $this->getCurrency(),
            'employer_login' => $this->getEmployerLogin(),
            'employer_name'  => $this->getEmployerName(),
        ];
    }
}

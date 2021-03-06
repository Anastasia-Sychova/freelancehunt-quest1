<?php
namespace Quest1\Database;

use Quest1\Errors\ConnectionError;
use Quest1\Errors\QueryError;
use Quest1\Models\Project;

/**
 * @method string real_escape_string(string $escapeString)
 */
class Query
{
    /**
     * @var Mysql|null
     */
    private $mysql;

    /**
     * Query constructor.
     * @param Mysql|null $mysql
     */
    public function __construct(Mysql $mysql = null)
    {
        if (is_null($mysql)) {
            $this->mysql = new Mysql();

            return;
        }
        $this->mysql = $mysql;
    }

    /**
     * @throws ConnectionError
     * @throws QueryError
     */
    public function dropAllSkills()
    {
        $this->mysql->query('TRUNCATE TABLE skills');
    }

    /**
     * @throws ConnectionError
     * @throws QueryError
     */
    public function dropAllProjects()
    {
        $this->mysql->query('TRUNCATE TABLE projects');
    }

    /**
     * @param \stdClass $skill
     *
     * @throws ConnectionError
     * @throws QueryError
     */
    public function createSkill(\stdClass $skill)
    {
        $this->mysql->query(sprintf(
            'INSERT INTO skills (id, `name`) VALUES (%d, "%s")',
            $skill->id,
            $this->mysql->real_escape_string($skill->name))
        );
    }

    /**
     * @return array
     *
     * @throws ConnectionError
     * @throws QueryError
     */
    public function getSkills(): array
    {
        $skills = [];
        $result = $this->mysql->query('SELECT * FROM skills ');
        while ($row = $result->fetch_assoc()) {
            $skills[$row['id']] = $row;
        }

        return $skills;
    }

    /**
     * @param Project $project
     *
     * @throws ConnectionError
     * @throws QueryError
     */
    public function createProject(Project $project)
    {
        $this->mysql->query(sprintf('
INSERT INTO projects
    (id, `name`, link, skills, cost, currency, employer_login, employer_name)
    VALUES (%1$d, "%2$s", "%3$s", "%4$s", %5$d, "%6$s", "%7$s", "%8$s")
    ON DUPLICATE KEY UPDATE
    `name` = "%2$s", link = "%3$s", skills = "%4$s", cost = %5$d, currency = "%6$s", employer_login = "%7$s", employer_name = "%8$s"
    ',
            $project->getId(),
            $this->mysql->real_escape_string($project->getName()),
            $this->mysql->real_escape_string($project->getLink()),
            $this->mysql->real_escape_string(json_encode($project->getSkills())),
            $project->getCost(),
            $this->mysql->real_escape_string($project->getCurrency()),
            $this->mysql->real_escape_string($project->getEmployerLogin()),
            $this->mysql->real_escape_string($project->getEmployerName())
        ));
    }

    /**
     * @param array $skills
     * @param int   $offset
     * @param int   $limit
     *
     * @return array
     *
     * @throws ConnectionError
     * @throws QueryError
     */
    public function getProjectsBySkills(array $skills, $offset = 0, $limit = 10)
    {
        $projects = [];
        $result = $this->mysql->query(sprintf(
            'SELECT * FROM projects WHERE %s ORDER BY id DESC LIMIT %d OFFSET %d',
            $this->makeSkillSearch($skills),
            $limit,
            $offset
        ));
        while ($row = $result->fetch_assoc()) {
            $projects[] = new Project((object)$row);
        }

        return $projects;
    }

    /**
     * @param array $skills
     * @param int $limit
     *
     * @return false|float
     *
     * @throws ConnectionError
     * @throws QueryError
     */
    public function getProjectsBySkillsPageCount(array $skills, $limit = 10)
    {
        $result = $this->mysql->query(sprintf(
            'SELECT count(id) FROM projects WHERE %s ORDER BY id DESC',
            $this->makeSkillSearch($skills)
        ));
        $count = (int)$result->fetch_row()[0];

        return ceil($count / $limit);
    }

    /**
     * @param array $skills
     * @param $currency
     * @param int $minCost
     * @param int $maxCost
     *
     * @return int
     *
     * @throws ConnectionError
     * @throws QueryError
     */
    public function getCostRangeData(array $skills, $currency, $minCost = 0, $maxCost = 0)
    {
        $costSearch = $minCost ? " AND cost > $minCost" : '';
        $costSearch = $maxCost ? $costSearch . " AND cost < $maxCost" : $costSearch;
        $result = $this->mysql->query(sprintf(
            'SELECT count(id) FROM projects WHERE (%s) AND currency="%s"%s ORDER BY id DESC',
            $this->makeSkillSearch($skills),
            $currency,
            $costSearch
        ));

        return (int)$result->fetch_row()[0];
    }

    /**
     * @param array $skills
     *
     * @return string
     */
    public function makeSkillSearch(array $skills): string
    {
        $search = [];
        foreach ($skills as $skill) {
            $search[] = "JSON_CONTAINS(skills, '$skill')";
        }

        return implode(' OR ', $search);
    }

    /**
     * @throws ConnectionError
     *
     * @throws QueryError
     */
    public function start()
    {
        $this->mysql->query('BEGIN');
    }

    /**
     * @throws ConnectionError
     *
     * @throws QueryError
     */
    public function commit()
    {
        $this->mysql->query('COMMIT');
    }

    /**
     * @throws ConnectionError
     *
     * @throws QueryError
     */
    public function rollback()
    {
        $this->mysql->query('ROLLBACK');
    }
}

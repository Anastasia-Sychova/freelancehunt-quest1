<?php
require_once './../vendor/autoload.php';

use Quest1\Integrations\Freelancehunt;
use Quest1\Models\Project;
use Quest1\Database\Query;

$freelancehunt = new Freelancehunt();
$query = new Query();

$skills = $freelancehunt->getSkills();
$pageNumber = 1;
$projectCount = 0;

try {
    $query->start();
    $query->dropAllSkills();
    foreach ($skills->data as $skill) {
        $query->createSkill($skill);
    }

    $query->dropAllProjects();
    do {
        $projects = $freelancehunt->getProjects($pageNumber);
        foreach ($projects->data as $rawProject) {
            if ($rawProject->attributes->status->id !== 11) {
                continue;
            }

            $employer = $rawProject->attributes->employer;
            $project = new Project();
            $project
                ->setId($rawProject->id)
                ->setName($rawProject->attributes->name)
                ->setLink($rawProject->links->self->web)
                ->setCost($rawProject->attributes->budget->amount ?? 0)
                ->setCurrency($rawProject->attributes->budget->currency ?? 'UAH')
                ->setEmployerName($employer ? "$employer->first_name $employer->last_name" : '')
                ->setEmployerLogin($employer ? ($employer->login ?? '') : '');
            foreach ($rawProject->attributes->skills as $skill) {
                $project->addSkill((int)$skill->id);
            }
            $query->createProject($project);
            $projectCount++;
        }
        $pageNumber++;
    } while ($projects->links->next);
    $query->commit();

    echo "$projectCount projects found";
} catch (\Throwable $exception) {
    $query->rollback();
    echo 'ERROR: ' . $exception->getMessage();
}

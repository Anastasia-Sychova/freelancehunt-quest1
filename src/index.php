<?php
declare(strict_types=1);

require_once './../vendor/autoload.php';
require_once ('Integrations/Freelancehunt.php');
require_once ('Database/Query.php');

use Quest1\Database\Query;
use Quest1\Integrations\Freelancehunt;
try {
    $freelancehunt = new Freelancehunt();
    $query = new Query();
    $skills = $query->getSkills();
    $filterSkills = $freelancehunt->getFilterSkills();
    $page = $_GET['page'] ? (int) $_GET['page'] : 1;
    $projects = $query->getProjectsBySkills($filterSkills, $page);
    $pageCount = $query->getProjectsBySkillsPageCount($filterSkills);
    $nextPage = $pageCount >= $page + 1 ? "?page=" . ($page + 1) : null;
    $prevPage = 0 < $page - 1 ? "?page=" . ($page - 1) : null;
    $groups = $freelancehunt->getPieGroups();
    $pieData = [];
    foreach ($groups as $group) {
        $pieData[$group['name']] = $query->getCostRangeData(
            $filterSkills,
            $group['currency'],
            $group['min'],
            $group['max']
        );
    }

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);
    $twig->display('index.html.twig', [
        'projects' => $projects,
        'skills'   => $skills,
        'nextPage' => $nextPage,
        'prevPage' => $prevPage,
        'pieData'  => json_encode($pieData),
    ]);
} catch (Exception $e) {
  die ('ERROR: ' . $e->getMessage());
}

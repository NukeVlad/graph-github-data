<?php

date_default_timezone_set('Asia/Ho_Chi_Minh');
set_time_limit(0);

require_once (rtrim(str_replace('\\', '/', __dir__), '/') . '/vendor/autoload.php');

function getArrayIssues($label = null)
{
    $owner = 'nukeviet';
    $repo = 'nukeviet';

    $from = 1483207200;
    $to = 1514743200;

    $client = new GitHubClient();
    $client->setCredentials('YourGithubUserEmail@mail.com', 'YourGithubPass');

    $client->setPage();
    $client->setPageSize(100);

    $arrayIssues = array();

    try {
        $issues = $client->issues->listIssues($owner, $repo, null, 'all', null, null, null, $label);
        while (true) {
            $page = $client->getPage();
            $isBreakLoop = false;

            foreach ($issues as $issue) {
                $issue_addtime = strtotime($issue->getCreatedAt());
                $issue_author = $issue->getUser()->getLogin();
                $issue_id  = $issue->getNumber();

                if ($issue_addtime < $from) {
                    $isBreakLoop = true;
                    break;
                }

                if ($issue_addtime < $to) {
                    $arrayIssues[$issue_id] = array(
                        'id' => $issue_id,
                        'creatTime' => $issue_addtime,
                        'creatUser' => $issue_author
                    );
                }
            }

            if ($isBreakLoop) {
                break;
            }

            if(!$client->hasNextPage()) {
                break;
            }

            $issues = $client->getNextPage();
            if($client->getPage() == $page) {
                break;
            }
        }
    } catch (Exception $e) {
        print_r($e->getMessage());
        die();
    }

    return $arrayIssues;
}

$allIssues = getArrayIssues();
$duplicateIssues = getArrayIssues('duplicate (lặp)');
$invalidIssues = getArrayIssues('invalid (ko hợp lệ)');
$questionIssues = getArrayIssues('question (hỏi)');
$wontfixIssues = getArrayIssues('wontfix (không sửa)');

$array_statistics = array();

foreach ($allIssues as $issue) {
    if (!isset($duplicateIssues[$issue['id']]) and !isset($invalidIssues[$issue['id']]) and !isset($questionIssues[$issue['id']]) and !isset($wontfixIssues[$issue['id']])) {
        if (!isset($array_statistics[$issue['creatUser']])) {
            $array_statistics[$issue['creatUser']] = 0;
        }
        $array_statistics[$issue['creatUser']]++;
    }
}

echo("<code><table border=\"1\">");
echo('<tr><th>Tác giả</th><th>Số báo lỗi được ghi nhận</th></tr>');

foreach ($array_statistics as $creatUser => $number) {
    echo('<tr><td>' . $creatUser . '</td><td>' . $number . '</td></tr>');
}

echo("</table></code>");


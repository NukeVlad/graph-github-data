<?php

date_default_timezone_set('Asia/Ho_Chi_Minh');
set_time_limit(0);

require_once (rtrim(str_replace('\\', '/', __dir__), '/') . '/vendor/autoload.php');

function getArrayCommits()
{
    $owner = 'nukeviet';
    $repo = 'nukeviet';

    $from = 1483207200;
    $to = 1514743200;

    $since = date('c', $from);
    $until = date('c', $to);

    $client = new GitHubClient();
    $client->setCredentials('YourGithubUserEmail@mail.com', 'YourGithubPass');

    $client->setPage();
    $client->setPageSize(100);

    $arrayCommits = array();

    try {
        $commits = $client->repos->commits->listCommitsOnRepository($owner, $repo, null, null, null, $since, $until);
        while (true) {
            $page = $client->getPage();

            foreach ($commits as $commit) {
                $commit_author_name = $commit->getCommit()->getAuthor()->getName();
                $commit_author_email = $commit->getCommit()->getAuthor()->getEmail();

                $commit_committer = $commit->getCommitter();

                if ($commit_committer == null) {
                    $commit_committer = '';
                } else {
                    $commit_committer = $commit_committer->getLogin();
                }

                $commit_message = $commit->getCommit()->getMessage();

                $arrayCommits[] = array(
                    'author_name' => $commit_author_name,
                    'author_email' => $commit_author_email,
                    'committer' => $commit_committer,
                    'message' => $commit_message
                );
            }

            if(!$client->hasNextPage()) {
                break;
            }

            $commits = $client->getNextPage();
            if($client->getPage() == $page) {
                break;
            }
        }
    } catch (Exception $e) {
        print_r($e->getMessage());
        die();
    }

    return $arrayCommits;
}

$allCommits = getArrayCommits();
$array_commit = array();
$Array_Link = array(
    'contact@vinades.vn' => 'vinades',
    'anhtu@vinades.vn' => 'anhtunguyen',
    'vuthao27@gmail.com' => 'vuthao',
    'phantandung92@gmail.com' => 'hoaquynhtim99',
    'anhyeuviolet@users.noreply.github.com' => 'anhyeuviolet',
    'hongoctrien@2mit.org' => 'mynukeviet',
    'thehung@vinades.vn' => 'thehung',
    'thao@vinades.vn' => 'vinades',
    'thinhwebhp@gmail.com' => 'trinhthinhvn',
    'nguyentiendat713@gmail.com' => 'anhyeuviolet',
    'contact@tdfoss.vn' => 'tdfoss',
    'kid.apt@gmail.com' => 'thangbv',
    'thuvp95@gmail.com' => 'thuvp1995',
    'h.tuyen1994@gmail.com' => 'hoang tuyen',
);

foreach ($allCommits as $commit) {
    // web-flow là các merge trên github
    if ($commit['committer'] != 'web-flow') {
        $commitKey = isset($Array_Link[$commit['author_email']]) ? $Array_Link[$commit['author_email']] : $commit['author_email'];
        if (!isset($array_commit[$commitKey])) {
            $array_commit[$commitKey] = 0;
        }
        $array_commit[$commitKey]++;
    }
}

echo("<code><table border=\"1\">");
echo('<tr><th>Tác giả</th><th>Số Commit</th></tr>');

foreach ($array_commit as $creatUser => $number) {
    echo('<tr><td>' . $creatUser . '</td><td>' . $number . '</td></tr>');
}

echo("</table></code>");


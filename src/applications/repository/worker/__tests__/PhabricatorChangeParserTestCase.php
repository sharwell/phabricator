<?php

final class PhabricatorChangeParserTestCase
  extends PhabricatorWorkingCopyTestCase {

  public function testGitParser() {
    $repository = $this->buildDiscoveredRepository('CHA');
    $viewer = PhabricatorUser::getOmnipotentUser();

    $commits = id(new DiffusionCommitQuery())
      ->setViewer($viewer)
      ->withRepositoryIDs(array($repository->getID()))
      ->execute();

    $this->expectChanges(
      $repository,
      $commits,
      array(
        // 8ebb73c add +x
        '8ebb73c3f127625ad090472f4f3bfc72804def54' => array(
          array(
            '/',
            null,
            null,
            DifferentialChangeType::TYPE_CHILD,
            DifferentialChangeType::FILE_DIRECTORY,
            0,
            1389892449,
          ),
          array(
            '/file_moved',
            null,
            null,
            DifferentialChangeType::TYPE_CHANGE,
            DifferentialChangeType::FILE_NORMAL,
            1,
            1389892449,
          ),
        ),

        // ee9c790 add symlink
        'ee9c7909e012da7d75e8e1293c7803a6e73ac26a' => array(
          array(
            '/',
            null,
            null,
            DifferentialChangeType::TYPE_CHILD,
            DifferentialChangeType::FILE_DIRECTORY,
            0,
            1389892436,
          ),
          array(
            '/file_link',
            null,
            null,
            DifferentialChangeType::TYPE_ADD,
            DifferentialChangeType::FILE_SYMLINK,
            1,
            1389892436,
          ),
        ),

        // 7260ca4 add directory file
        '7260ca4b6cec35e755bb5365c4ccdd3f1977772e' => array(
          array(
            '/',
            null,
            null,
            DifferentialChangeType::TYPE_CHILD,
            DifferentialChangeType::FILE_DIRECTORY,
            0,
            1389892408,
          ),
          array(
            '/dir',
            null,
            null,
            DifferentialChangeType::TYPE_ADD,
            DifferentialChangeType::FILE_DIRECTORY,
            1,
            1389892408,
          ),
          array(
            '/dir/subfile',
            null,
            null,
            DifferentialChangeType::TYPE_ADD,
            DifferentialChangeType::FILE_NORMAL,
            1,
            1389892408,
          ),
        ),

        // 1fe783c move a file
        '1fe783cf207c1e5f3e01650d2d9cb80b8a707f0e' => array(
          array(
            '/',
            null,
            null,
            DifferentialChangeType::TYPE_CHILD,
            DifferentialChangeType::FILE_DIRECTORY,
            0,
            1389892388,
          ),
          array(
            '/file',
            null,
            null,
            DifferentialChangeType::TYPE_MOVE_AWAY,
            DifferentialChangeType::FILE_NORMAL,
            1,
            1389892388,
          ),
          array(
            '/file_moved',
            '/file',
            '1fe783cf207c1e5f3e01650d2d9cb80b8a707f0e',
            DifferentialChangeType::TYPE_MOVE_HERE,
            DifferentialChangeType::FILE_NORMAL,
            1,
            1389892388,
          ),
        ),

        // 376af8c copy a file
        '376af8cd8f5b96ec55b7d9a86ccc85b8df8fb833' => array(
          array(
            '/',
            null,
            null,
            DifferentialChangeType::TYPE_CHILD,
            DifferentialChangeType::FILE_DIRECTORY,
            0,
            1389892377,
          ),
          array(
            '/file',
            null,
            null,
            DifferentialChangeType::TYPE_COPY_AWAY,
            DifferentialChangeType::FILE_NORMAL,
            0,
            1389892377,
          ),
          array(
            '/file_copy',
            '/file',
            '376af8cd8f5b96ec55b7d9a86ccc85b8df8fb833',
            DifferentialChangeType::TYPE_COPY_HERE,
            DifferentialChangeType::FILE_NORMAL,
            1,
            1389892377,
          ),
        ),

        // ece6ea6 changed a file
        'ece6ea6c6836e8b11a103e21707b8f30e6840c94' => array(
          array(
            '/',
            null,
            null,
            DifferentialChangeType::TYPE_CHILD,
            DifferentialChangeType::FILE_DIRECTORY,
            0,
            1389892352,
          ),
          array(
            '/file',
            null,
            null,
            DifferentialChangeType::TYPE_CHANGE,
            DifferentialChangeType::FILE_NORMAL,
            1,
            1389892352,
          ),
        ),

        // 513103f added a file
        '513103f65b8413dd2f1a1b5c1d4852a4a598540f' => array(
          array(
            '/',
            null,
            null,
            DifferentialChangeType::TYPE_CHILD,
            DifferentialChangeType::FILE_DIRECTORY,
            // This is the initial commit and technically created this
            // directory; arguably the parser should figure this out and
            // mark this as a direct change.
            0,
            1389892330,
          ),
          array(
            '/file',
            null,
            null,
            DifferentialChangeType::TYPE_ADD,
            DifferentialChangeType::FILE_NORMAL,
            1,
            1389892330,
          ),
        ),
      ));
  }

  public function testMercurialParser() {
    $repository = $this->buildDiscoveredRepository('CHB');
    $viewer = PhabricatorUser::getOmnipotentUser();

    $commits = id(new DiffusionCommitQuery())
      ->setViewer($viewer)
      ->withRepositoryIDs(array($repository->getID()))
      ->execute();

    $this->expectChanges(
      $repository,
      $commits,
      array(
        '970357a2dc4264060e65d68e42240bb4e5984085' => array(
          array(
            '/',
            null,
            null,
            DifferentialChangeType::TYPE_CHILD,
            DifferentialChangeType::FILE_DIRECTORY,
            0,
            1390249395,
          ),
          array(
            '/file_moved',
            null,
            null,
            DifferentialChangeType::TYPE_CHANGE,
            DifferentialChangeType::FILE_NORMAL,
            1,
            1390249395,
          ),
        ),

        'fbb49af9788e5dbffbc05a060b680df1fd457be3' => array(
          array(
            '/',
            null,
            null,
            DifferentialChangeType::TYPE_CHILD,
            DifferentialChangeType::FILE_DIRECTORY,
            0,
            1390249380,
          ),
          array(
            '/file_link',
            null,
            null,
            DifferentialChangeType::TYPE_ADD,
            // TODO: This is not correct, and should be FILE_SYMLINK. See
            // note in the parser about this. This is a known bug.
            DifferentialChangeType::FILE_NORMAL,
            1,
            1390249380,
          ),
        ),

        '0e8d3465944c7ed7a7c139da7edc652cf80dba69' => array(
          array(
            '/',
            null,
            null,
            DifferentialChangeType::TYPE_CHILD,
            DifferentialChangeType::FILE_DIRECTORY,
            0,
            1390249342,
          ),
          array(
            '/dir',
            null,
            null,
            DifferentialChangeType::TYPE_ADD,
            DifferentialChangeType::FILE_DIRECTORY,
            1,
            1390249342,
          ),
          array(
            '/dir/subfile',
            null,
            null,
            DifferentialChangeType::TYPE_ADD,
            DifferentialChangeType::FILE_NORMAL,
            1,
            1390249342,
          ),
        ),

        '22c75131ff15c8a44d7a729c4542b7f4c8ed27f4' => array(
          array(
            '/',
            null,
            null,
            DifferentialChangeType::TYPE_CHILD,
            DifferentialChangeType::FILE_DIRECTORY,
            0,
            1390249320,
          ),
          array(
            '/file',
            null,
            null,
            DifferentialChangeType::TYPE_MOVE_AWAY,
            DifferentialChangeType::FILE_NORMAL,
            1,
            1390249320,
          ),
          array(
            '/file_moved',
            '/file',
            '22c75131ff15c8a44d7a729c4542b7f4c8ed27f4',
            DifferentialChangeType::TYPE_MOVE_HERE,
            DifferentialChangeType::FILE_NORMAL,
            1,
            1390249320,
          ),
        ),

        'd9d252df30cb7251ad3ea121eff30c7d2e36dd67' => array(
          array(
            '/',
            null,
            null,
            DifferentialChangeType::TYPE_CHILD,
            DifferentialChangeType::FILE_DIRECTORY,
            0,
            1390249308,
          ),
          array(
            '/file',
            null,
            null,
            DifferentialChangeType::TYPE_COPY_AWAY,
            DifferentialChangeType::FILE_NORMAL,
            0,
            1390249308,
          ),
          array(
            '/file_copy',
            '/file',
            'd9d252df30cb7251ad3ea121eff30c7d2e36dd67',
            DifferentialChangeType::TYPE_COPY_HERE,
            DifferentialChangeType::FILE_NORMAL,
            1,
            1390249308,
          ),
        ),

        '1fc0445d5e3d0f33e9dcbb68bbe419a847460d25' => array(
          array(
            '/',
            null,
            null,
            DifferentialChangeType::TYPE_CHILD,
            DifferentialChangeType::FILE_DIRECTORY,
            0,
            1390249294,
          ),
          array(
            '/file',
            null,
            null,
            DifferentialChangeType::TYPE_CHANGE,
            DifferentialChangeType::FILE_NORMAL,
            1,
            1390249294,
          ),
        ),

        '61518e196efb7f80700333cc0d00634c2578871a' => array(
          array(
            '/',
            null,
            null,
            DifferentialChangeType::TYPE_ADD,
            DifferentialChangeType::FILE_DIRECTORY,
            1,
            1390249286,
          ),
          array(
            '/file',
            null,
            null,
            DifferentialChangeType::TYPE_ADD,
            DifferentialChangeType::FILE_NORMAL,
            1,
            1390249286,
          ),
        ),
      ));
  }

  private function expectChanges(
    PhabricatorRepository $repository,
    array $commits,
    array $expect) {

    switch ($repository->getVersionControlSystem()) {
      case PhabricatorRepositoryType::REPOSITORY_TYPE_GIT:
        $parser = 'PhabricatorRepositoryGitCommitChangeParserWorker';
        break;
      case PhabricatorRepositoryType::REPOSITORY_TYPE_MERCURIAL:
        $parser = 'PhabricatorRepositoryMercurialCommitChangeParserWorker';
        break;
      default:
        throw new Exception(pht('No support yet.'));
    }

    foreach ($commits as $commit) {
      $commit_identifier = $commit->getCommitIdentifier();
      $expect_changes = idx($expect, $commit_identifier);

      if ($expect_changes === null) {
        $this->assertEqual(
          $commit_identifier,
          null,
          pht(
            'No test entry for commit "%s" in repository "%s"!',
            $commit_identifier,
            $repository->getCallsign()));
      }

      $parser_object = newv($parser, array(array()));
      $changes = $parser_object->parseChangesForUnitTest($repository, $commit);

      $path_map = id(new DiffusionPathQuery())
        ->withPathIDs(mpull($changes, 'getPathID'))
        ->execute();
      $path_map = ipull($path_map, 'path');

      $target_commits = array_filter(mpull($changes, 'getTargetCommitID'));
      if ($target_commits) {
        $commits = id(new DiffusionCommitQuery())
          ->setViewer(PhabricatorUser::getOmnipotentUser())
          ->withIDs($target_commits)
          ->execute();
        $target_commits = mpull($commits, 'getCommitIdentifier', 'getID');
      }

      $dicts = array();
      foreach ($changes as $key => $change) {
        $target_path = idx($path_map, $change->getTargetPathID());
        $target_commit = idx($target_commits, $change->getTargetCommitID());

        $dicts[$key] = array(
          $path_map[(int)$change->getPathID()],
          $target_path,
          $target_commit,
          (int)$change->getChangeType(),
          (int)$change->getFileType(),
          (int)$change->getIsDirect(),
          (int)$change->getCommitSequence(),
        );
      }

      $dicts = ipull($dicts, null, 0);
      $expect_changes = ipull($expect_changes, null, 0);
      ksort($dicts);
      ksort($expect_changes);

      $this->assertEqual($expect_changes, $dicts);
    }
  }


}

<?php

require_once __DIR__ . '/../vendor/autoload.php';

$f3 = Base::instance();

$f3->set('yaml', new DB\Yig(__DIR__ . '/tmp/test/', DB\Yig::FORMAT_YAML));
$f3->set('csv', new DB\Yig(__DIR__ . '/tmp/test/', DB\Yig::FORMAT_CSV));

class MemberYaml extends \DB\Yig\Mapper {
    public function __construct() {
        parent::__construct( Base::instance()->get('yaml'), 'team.yaml' );
    }
}

class MemberCsv extends \DB\Yig\Mapper {
    public function __construct() {
        parent::__construct( Base::instance()->get('csv'), 'team.csv' );
    }
}


$test = new Test();

$team=array(
    'Jimmy'   => array('name' => 'Jimmy', 'age'=>26,'instr'=>'guitars', 'fact'=>'help
is
very awesome'),
    'Robert'  => array('fact' => "people love
him", 'name' => 'Robert', 'age'=>82,'instr'=>'vocals'),
  'John'    => array('name' => 'John', 'age'=>28,'instr'=>'drums'),
  'Anna'    => array('name' => 'Anna', 'age' => 18,            'instr'=>'keyboards'),
  'Brady'    => array('name' => 'Brady',            'instr'=>'keyboard'),
);

foreach(['MemberCsv', 'MemberYaml'] as $className) {
	$query = new $className;
	foreach($query->find() as $line) {
		$line->erase();
	}
	foreach($team as $line) {
	    $mapper = new $className;
	    $mapper->copyFrom($line);
	    $mapper->save();
	}
	$mapper = new $className;
	$membersQuery = new $className;
	$member = $membersQuery->load(['@name = ?', 'Brady']);
	$member->correctAnswers = [1,2,7, 11];
	$member->save();

	$members = $membersQuery->find();
	$membersWithAge = $membersQuery->count(['@age != ""']);
	$test->expect($membersWithAge == 4, $className . ": 4 members with age listed");
	$test->expect(
		($membersQuery
			->load(['@name = ?', 'Brady'])
			->instr
		) == 'keyboard',
		$className . ": Brady's instrument is the 'keyboard'"
	);
}

foreach ($test->results() as $result) {
    echo $result['text']."\n";
    if ($result['status'])
        echo 'Pass';
    else
        echo 'Fail ('.$result['source'].')';
    echo "\n";
}




# F3-Yig

Extension to acces YAML and csv files in Fatfree using the f3 jig syntax.

example:

<code>
<?php
class MemberYaml extends \DB\Yig\Mapper {
    public function __construct() {
        parent::__construct( new DB\Yig(__DIR__ . '/tmp/test/'), 'team.yaml' );
    }
}

class MemberCsv extends \DB\Yig\Mapper {
    public function __construct() {
        parent::__construct( new DB\Yig(__DIR__ . '/tmp/test/', DB\Yig::FORMAT_CSV), 'team.csv' );
    }
}
$memberQuery = new MemberCsv;
$allMembers = $memberQuery->find();
</code>

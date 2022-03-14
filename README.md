# F3-Yig

Extension to acces YAML and csv files in Fatfree using the f3 jig syntax.

example:

```
/*
use Yaml format for saving
*/
class MemberYaml extends \DB\Yig\Mapper {
    public function __construct() {
        parent::__construct( new DB\Yig('tmp/'), 'team.yaml' );
    }
}

/*
use Csv format for saving
*/
class MemberCsv extends \DB\Cig\Mapper {
    public function __construct() {
        parent::__construct( new DB\Cig('tmp/'), 'team.csv' );
    }
}

$memberQuery = new MemberCsv;
$allMembers = $memberQuery->find();

```

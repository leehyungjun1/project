<?
error_reporting( E_ALL );
ini_set( "display_errors", 1 );

interface Human {
    public function walk(int $cnt) : array;
}

class Marathone implements human {
    private $steps;

    public function getSteps(): int {
        return $this->steps;
    }

    public function setSteps(int $steps): void {

    }

    public function walk(int $cnt): array {
        $rows = [];
        for($i = 0; $i < $cnt; $i++) {
            $rows[] = "Step : ".$i;
        }
        return $rows;
    }
}

$marathone = new Marathone();
$rst = $marathone->walk(3);

print_r($rst);



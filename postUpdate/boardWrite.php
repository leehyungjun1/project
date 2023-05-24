<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );

class BoardWrite
{
    //ID, TITLE, Contents, WriteDate ( 오늘 날짜를 입력 함.)

    //private $Id;
    //private $Title;
    // private $Contents;

    public function insertData(array $str)
    {
        $rows = [];

        foreach ($_POST as $key => $val) {
            $rows[$key] = $val;
        }

        try {
            $this->chkData($rows);
        } catch (Exception $e) {
            echo "잘못된 곳 : ". $e->getMessage();
        }   
        
        return $rows;
    }

    private function chkData(array $rows)
    {
        $id = trim($rows['id']);
        $title = trim($rows['title']);
        $contents = trim($rows['contents']);

        foreach ($rows as $k => $v) {
            if($v == null) {
                throw new Exception ($k ." 정보가 없습니다.");
            }
        }

    }
}

// class MessyCsvParserException extends Exception
// {
//     public function __construct(string $message, int $code = 0, Throwable $previous = null)
//     {
//         parent::__construct($message, $code, $previous);
//     }

//     public function __toString()
//     {
//         return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
//     }
// }



$_POST['id'] = "1";
$_POST['title'] = "안녕하세요";
$_POST['contents'] = "게시판 만들고 있는 중입니다.";

$boardWrite = new BoardWrite();
$rst = $boardWrite->insertData($_POST);
 print_r($rst);


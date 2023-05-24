<?php

class CsvParse {
    public function parse(string $path) {

        try{
            $rows = $this->path($path);
            $chkCsvs = [];

            foreach ($rows as $value) {
                $chkCsv = $this->chkCsv($value);
                $chkCsvs[] = $chkCsv;
            }

        }           
        catch (MessyCsvParserException $e) {
            echo "CSV 파일 파싱 중 오류 발생: " . $e->getMessage();
        }

        return $chkCsvs;
        
    }

    private function chkCsv(array $row) :userDto
    {
        $userDto = [];

        $id = trim($row['id']);
        $firstName = trim($row['first_name']);
        $lastName = trim($row['last_name']);
        $birthDate = trim($row['birth_date']);

        $chkBirthDate = DateTime::createFromFormat('Y-m-d', trim($row['birth_date']));
        if (!$birthDate || $chkBirthDate->format('Y-m-d') !== $birthDate ) {
            throw new Exception('유효하지 않은 생년월일 형식입니다.');
        }

        $booksIds = explode('|', trim($row['books_ids']));
        //return $result = array($id, $firstName, $lastName, $chkBirthDate, $booksIds);
        return new userDto($id, $firstName, $lastName, $chkBirthDate, $booksIds);
    }

    private function path(string $path): array //해당 경로 불러오기.
    {
        $file = fopen($path, 'r');
        $rows = [];
        if($file) {
            $header = fgetcsv($file);

            while(($row = fgetcsv($file)) !== false) {
                $rows[] = array_combine($header, $row);
            }
            
            fclose($file);    
        } else {
            throw new Exception("CSV 파일을 열 수 없습니다: $path");
        }
        return $rows;
    }
}


class userDto {
    public $id;
    public $firstName;
    public $lastName;
    public $birthDate;
    public $bookIds;


    public function __construct( int $id, string $firstName, string $lastName, DateTime $birthDate, array $bookIds) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthDate = $birthDate;
        $this->bookIds = $bookIds;
    }
}

class MessyCsvParserException extends Exception {
    public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

$csvParse = new CsvParse();
$data = $csvParse->parse('./1.csv');

echo json_encode($data);
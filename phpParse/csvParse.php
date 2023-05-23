<?php
class MessyCsvParser
{
    public function parse(string $path): array
    {
        try {
            $rows = $this->loadCsvFile($path);
            $userDTOs = [];

            foreach ($rows as $row) {
                $userDTO = $this->convertToUserDTO($row);
                $userDTOs[] = $userDTO;
            }

            return $userDTOs;
        } catch (MessyCsvParserException $e) {
            echo "CSV 파일 파싱 중 오류 발생: " . $e->getMessage();
            return [];
        }
    }

    private function loadCsvFile(string $path): array
    {
        
        $rows = [];

        $file = fopen($path, 'r');
       if ($file) {
            $header = fgetcsv($file); // 첫 번째 행을 헤더로 읽어옵니다.

            while (($row = fgetcsv($file)) !== false) {
                $rows[] = array_combine($header, $row);
            }

            fclose($file);
        } else {
            throw new MessyCsvParserException("CSV 파일을 열 수 없습니다: $path");
        }

        return $rows;
    }

    private function convertToUserDTO(array $row): UserDTO
    {
        $id = intval($row['id']);
        $firstName = trim($row['first_name']);
        $lastName = trim($row['last_name']);

        $birthDate = DateTime::createFromFormat('Y-m-d', trim($row['birth_date']));
        if (!$birthDate || $birthDate->format('Y-m-d') !== trim($row['birth_date'])) {
            throw new MessyCsvParserException('유효하지 않은 생년월일 형식입니다.');
        }

        $bookIds = explode('|', trim($row['books_ids']));

        return new UserDTO($id, $firstName, $lastName, $birthDate, $bookIds);
    }
}

class MessyCsvParserException extends Exception
{
    public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

class UserDTO
{
    public $id;
    public $firstName;
    public $lastName;
    public $birthDate;
    public $bookIds;

    public function __construct(int $id, string $firstName, string $lastName, DateTime $birthDate, array $bookIds)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthDate = $birthDate;
        $this->bookIds = $bookIds;
    }
}

$csvParse = new MessyCsvParser();
$data = $csvParse->parse('./2.csv');


<?php

namespace Task\classes\utils;

use SplFileObject;
use Task\classes\exceptions\EmptyFileException;
use Task\classes\exceptions\NoFileException;

class CsvConverter
{
    private $fileName;
    /** @var SplFileObject $fileObject*/
    private $fileObject;
    private $fileNameConverter;
    private $headers;
    private $tableName;
    private $parseText;
    private $templateInsert;
    private $columns;
    private $numberGeneratedFields;

    public function import(
        string $fileName,
        string $fileNameConverter,
        string $tableName,
        array $columns,
        array $numberGeneratedFields = null
    ): void {
        $this->fileName = $fileName;
        $this->fileNameConverter = $fileNameConverter;
        $this->tableName = "`{$tableName}`";
        $this->columns = $columns;
        $this->numberGeneratedFields = $numberGeneratedFields;

        if (!file_exists($this->fileName)) {
            throw new NoFileException('Файл не существует');
        }

        $this->fileObject = new SplFileObject($this->fileName);
        if ($this->fileObject->getSize() === 0) {
            throw new EmptyFileException('Файл пустой');
        }

        if (empty($this->columns)) {
            throw new EmptyFileException('Пустой массив заголовков');
        }

        $this->fileObject->setFlags(SplFileObject::READ_CSV);

        $this->headers = $this->getHeader($this->columns);

        $this->parseText = !empty($this->numberGeneratedFields) ?
            $this->getParseValue($this->fileObject, $this->numberGeneratedFields) :
            $this->getParseValue($this->fileObject);

        $this->templateInsert = $this->collectFullRequest($this->tableName, $this->headers, $this->parseText);
        $this->writeToFile($this->templateInsert, $this->fileNameConverter, 'w');
        $this->writeToFile($this->templateInsert, 'generalDataSet.sql', 'a', PHP_EOL);
    }

    /**
     * Функция парсинга файла
     * @param SplFileObject $object
     * @param null $numbers
     * @return string|null
     * @throws EmptyFileException
     */
    private function getParseValue(SplFileObject $object, $numbers = null): ?string
    {
        $lineText = null;
        $text = null;
        foreach ($this->getNextLine() as $data) {
            if ($object->key() != 0) {
                $lineText = implode(', ', array_map(function ($item) {
                    $item = !empty($item) ? "'{$item}'" : null;
                    return $item;
                }, $data));
            }
            $text .= $this->getFormatData($lineText, $numbers);
        }
        if (empty($text)) {
            throw new EmptyFileException('Нет данных для записи');
        }
        return $this->removeWasteCharacters($text);
    }

    /**
     * Функция обработки строк для вставки в запрос
     * @param string|null $data
     * @param array|null $countField
     * @return string
     */
    public function getFormatData(?string $data, ?array $countField = null): ?string
    {
        $textInsert = null;
        if (!empty($data) && !empty($countField)) {
            $textInsert = '(' . $data . ', ' .
                $this->createStringFromNumbers($countField) .'), ' . "\r" . "\t";
        } elseif (!empty($data) && is_null($countField)) {
            $textInsert = '(' . $data . '), ' . "\r" . "\t";
        }

        return $textInsert;
    }

    /**
     * Функция обработки заголовков
     * @param array $headers
     * @return string|null
     */
    private function getHeader(array $headers): ?string
    {
        if (!empty($headers)) {
            return implode(', ', array_map(function ($item) {
                return "`{$item}`";
            }, $headers));
        }
        return null;
    }

    /**
     * Функция удаления лишних знаков в конце строки
     * @param string $text
     * @return string
     */
    private function removeWasteCharacters(string $text): string
    {
        return substr(rtrim($text), 0, -1);
    }

    /**\
     * Функция прохода по файлу
     * @return iterable
     */
    private function getNextLine(): iterable
    {
        while (!$this->fileObject->eof()) {
            yield $this->fileObject->fgetcsv(',');
        }
    }

    /**
     * Функция создания строки из сгенерированных цифр
     * @param array $numbers массив с цифрами
     * @return string
     */
    private function createStringFromNumbers(array $numbers): string
    {
        $data = '';
        foreach ($numbers as $number) {
            $data .= $this->getRandomValue($number) . ', ';
        }

        return $this->removeWasteCharacters($data);
    }

    /**
     * Функиця получения случайного числа из диапозона
     * @param int $countRow максимальное число из диапозона
     * @return int рандомное число из диапозона
     */
    private function getRandomValue(int $countRow): int
    {
        return rand(1, $countRow);
    }

    /**
     * Функция сборки всего запроса
     * @param string $tableName имя таблицы
     * @param string $column колокни таблицы
     * @param string $data данные колонок
     * @return string итоговый запрос
     */
    private function collectFullRequest(string $tableName, string $column, string $data): string
    {
        return "INSERT INTO {$tableName} "
            . "\r" . "\t({$column})" . "\r"
            . "VALUES " . "\r" . "\t" . $data;
    }

    /**
     * Функция записи в файл
     * @param string $data текст
     * @param string $fileName имя файла
     * @param string $mode режим записи
     * @param string|null $format возможность форматирования
     */
    private function writeToFile(string $data, string $fileName, string $mode, string $format = null): void
    {
        $file = fopen($fileName, $mode);
        if ($format) {
            fwrite($file, $data .';' . $format);
        } else {
            fwrite($file, $data .';');
        }
    }
}

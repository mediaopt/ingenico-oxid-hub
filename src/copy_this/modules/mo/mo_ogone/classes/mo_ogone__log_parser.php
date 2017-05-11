<?php


use Bcremer\LineReader\LineReader;

class mo_ogone__log_parser
{

    /**
     * @param  string  $file
     * @param  array   $filters
     * @return array
     */
    public function parseLogFile($file, array $filters = [])
    {
        $result = [];
        foreach (LineReader::readLines($file) as $line) {
            $entry = $this->parseLine($line);
            if ($this->accept($entry, $filters)) {
                $result[] = $entry;
            }
        }
        return $result;
    }

    /**
     * parse a log entry that uses the default monolog line formatter format
     * [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n
     * @param string $line
     * @return array
     */
    private function parseLine($line)
    {
        $pattern = '/\[([^\[]*)\] \w+.(\w+): ([^\[]+) ([\[\{].*[\]\}]) ([\[\{].*[\]\}])/';
        preg_match($pattern, $line, $data);
        if (!isset($data[0])) {
            return [
                'raw' => $line,
            ];
        }
        return [
            'date' => $data[1],
            'level' => $data[2],
            'message' => $data[3],
            'context' => json_decode($data[4], true),
            'extra' => json_decode($data[5], true),
        ];
    }

    /**
     * checks if a logEntry fulfills all filter settings
     * @param $logEntry
     * @param array $filters
     * @return bool
     */
    public function accept($logEntry, $filters)
    {
        foreach ($filters as $key => $val) {
            $found = $this->recursive_array_search($val, $logEntry[$key]);
            if (!$found) {
                return false;
            }
        }
        return true;
    }

    /**
     * searches for a substring in an array or another string
     * @param string $needle
     * @param array|string $haystack
     * @return bool
     */
    protected function recursive_array_search($needle,$haystack) {
        if (is_array($haystack)) {
            $found = false;
            foreach($haystack as $key => $value) {
                $found = $this->recursive_array_search($needle, $value);
                if ($found) {
                    break;
                }
            }
            return $found;
        }
        return false !== strpos($haystack, $needle);
    }
}
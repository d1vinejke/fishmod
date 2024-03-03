<?php
require_once "DatabaseConnector.php";

class RequestHandler
{
    private $connection;

    public function __construct()
    {
        $this->connection = new DatabaseConnector('127.0.0.1', 'fish-mod', 'root', '', 'utf8mb4', '3306');
    }

    public function ExecuteRequest($requestData, $typeQuery, $tableName, $condition = null, $leftJoin = null)
    {
        $tempInsertRows = '';
        $tempUpdateRows = '';
        $tempSelectRows = '';
        $values = [];
        foreach ($requestData as $key => $value) {
            $tempInsertRows .= $key . ',';
            $tempUpdateRows .= $key . ' = ?,';
            $tempSelectRows .= $key . ',';
            if ($key == 'date' && $value == 'auto')
                $value = date('Y-m-d', strtotime('today'));
            $values[] = $value;
        }
        $countValues = count($values);
        $unpreparedStr = str_repeat('?,', $countValues);
        $unpreparedValues = rtrim($unpreparedStr, ',');
        $tableRowsInsert = rtrim($tempInsertRows, ',');
        $tableRowsUpdate = rtrim($tempUpdateRows, ',');
        $tableRowsSelect = rtrim($tempSelectRows, ',');

        $types = [
            'INSERT' => 'INSERT INTO ' . $tableName . ' (' . $tableRowsInsert . ') VALUES (' . $unpreparedValues . ')',
            'UPDATE' => 'UPDATE ' . $tableName . ' SET ' . $tableRowsUpdate . ' ' . $condition,
            'SELECT' => 'SELECT ' . $tableRowsSelect . ' FROM ' . $tableName . ' ' . $leftJoin . ' ' . $condition
        ];
        $topListQuery = $this->connection->connect()->query('
            SELECT login, playTime
            FROM results as t0
                     LEFT JOIN users as t1 ON t1.user_id = t0.user_id
            ORDER BY playTime DESC
            LIMIT 3;
        ')->fetch_all(MYSQLI_ASSOC);

        $prepareQuery = $this->connection->connect()->prepare($types[$typeQuery]);
        try {
            if ($tableName == 'results') {
                return [
                    'status' => $prepareQuery->execute($values),
                    'topList' => $topListQuery
                ];
            }
            $execute = $prepareQuery->execute($values);

            $getUserId = $this->connection->connect()->query("
                SELECT user_id FROM users
                WHERE login = '$values[0]'
            ")->fetch_all(MYSQLI_ASSOC);

            return [
                'status' => $execute,
                'userData' => $getUserId[0],
                'topList' => $topListQuery
            ];
        } catch (Exception $exception) {
            if ($prepareQuery->errno == 1062) {
                $dupe = $this->connection->connect()->prepare('SELECT user_id FROM users WHERE login = ?');
                $dupe->bind_param("s", $values[0]);
                $dupe->execute();
                return ['error' => 'Такой пользователь уже существует!', 'userData' => $dupe->get_result()->fetch_assoc()];
            }
            return ['error' => $prepareQuery->error_list];
        }
    }
}


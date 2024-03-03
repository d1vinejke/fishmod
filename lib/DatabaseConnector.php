<?php

class DatabaseConnector
{
    /**
     * Параметры для подключения к БД
     * host = '127.0.0.1';       //Адрес подключения, по умолчанию на локальной среде ВСЕГДА 127.0.0.1 или localhost. (localhost = 127.0.0.1)
     * db = 'fish-mod';          //Название самой БД
     * user = 'root';            //Пользователь БД
     * password = '';            //Пароль от БД
     * port = 3306;              //Порт подключения к базе, по умолчанию он ВСЕГДА 3306 в случае с MySQL
     * charset = 'utf8mb4';      //Кодировка
     */
    protected $connection;
    private string $host, $db, $user, $password, $charset;
    private int $port;

    public function __construct($host, $db, $user, $password, $charset, $port) //Этот метод выполнится тогда, когда будет создан объект этого класса, то есть класса -> DatabaseConnector
    {
        $this->host = $host;
        $this->db = $db;
        $this->user = $user;
        $this->password = $password;
        $this->charset = $charset;
        $this->port = $port;
    }

    public function connect()
    {
        //Инициализируем подключение
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->db, $this->port);
        $this->connection->set_charset($this->charset); //Ставим по умолчанию кодировку, дабы избежать проблем с кракозябрами (а-ля кириллица при json_decode)
        $this->connection->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1); //Получаем данные соответствующих типов из БД, а не только СТРОКИ
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); //Ограничим пулл ошибок, лишь нужными
        return $this->connection;
    }


    /**
     * @return string[]
     * Использование __sleep() состоит в завершении работы над данными, ждущими обработки или других подобных задач очистки.
     * Кроме того, этот метод может быть полезен, когда есть очень большие объекты, которые нет необходимости полностью сохранять
     */
    public function __sleep()
    {
        return array('host', 'db', 'user', 'password', 'charset', 'port');
    }

    /**
     * @return void
     * Использование __wakeup() заключается в восстановлении любых соединений с базой данных,
     * которые могли быть потеряны во время операции сериализации и выполнения других операций повторной инициализации
     */
    public function __wakeup()
    {
        $this->connect();
    }
}


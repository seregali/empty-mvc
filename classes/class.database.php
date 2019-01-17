<?
defined('_VALID') or die();

class Database
{
    private $pdo = null;

    function __construct()
    {
        try {
            $this->pdo = new PDO("sqlite:db/db.sqlite3");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Ошибка :'.$e->getMessage();
        }
    }

    function query($query, $data = null, &$intoObject = null)
    {
        try {
            $sth = $this->pdo->prepare($query);
            if ($intoObject)
                $sth->setFetchMode(PDO::FETCH_INTO, $intoObject);

            if ($data)
                foreach ($data as $dataKey => $dataField) {
                    if (is_array($dataField))
                        unset($data[$dataKey]);
                }

            $sth->execute($data);
            return $sth;
        } catch (PDOException $e) {
            echo 'Ошибка :'.$e->getMessage();
            return null;
        }
    }
}
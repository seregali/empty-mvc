<?
defined('_VALID') or die();

abstract class entity
{
    var $id = "";

    function get($id = null)
    {
        $class = get_called_class();
        if ($id) {
            $App = App::get();
            if ($App->checkObjectCache($id)) {
                map($App->getObjectFromCache($id), $this);
                return true;
            } else {
                $query = "SELECT * FROM `" . $class . "` WHERE (`id` = :id)";
                $sth = $App->db->query($query, Array('id' => $id));
                $sth->setFetchMode(PDO::FETCH_INTO, $this);
                if ($sth->fetch()) {
                    $App->setObjectToCache($this);
                    return true;
                }
                return false;
            }
        }
        return false;
    }

    static function getAll($sort = false, $field = null, $opt = null)
    {
        $class = get_called_class();

        $query = "SELECT * FROM `" . $class . "`";

        if ($sort) {
            $query .= " ORDER BY `".$field."` ".$opt."";
        }

        $sth = App::get()->db->query($query);

        if ($rows = $sth->fetchAll(PDO::FETCH_CLASS, $class)) {
            return $rows;
        }
        return Array();
    }

    function getByAlias($alias)
    {
        $class = get_called_class();
        
        $query = "SELECT * FROM `" . $class . "` WHERE BINARY `alias` = :alias";

        $sth = App::get()->db->query($query, Array("alias" => $alias), $this);

        if ($row = $sth->fetch()) {
            return true;
        }

        return false;
    }

    function save()
    {
        $App = App::get();
        $class = get_class($this);
        $classVars = get_class_vars($class);

        if (!empty($this->id)) {
            $query = "
			UPDATE
				`" . $class . "`
			SET ";
            foreach ($classVars as $name => $value) {
                $query .= "`" . $name . "` = :$name,";
            }

            $query[strlen($query) - 1] = ' ';

            $query .= "
			WHERE
				`id` = :id";

            $App->db->query($query, (array)$this);

        } else {
            $this->id = guid();

            $query = "
			INSERT INTO
				`" . $class . "` ( ";

            foreach ($classVars as $name => $value) {
                $query .= " `" . $name . "` ,";
            }

            $query[strlen($query) - 1] = ' ';

            $query .= ")
			VALUES ( ";

            foreach ($classVars as $name => $value) {
                $query .= " :$name ,";
            }

            $query[strlen($query) - 1] = ' ';

            $query .= " ) ";

            $App->db->query($query, (array)$this);
        }

        $App->setObjectToCache($this);
        return $this->id;
    }

    function getImages()
    {
        $query = "SELECT * FROM `image` WHERE(`objectId` = :objectId) ORDER BY `ordering`";
        $sth = App::get()->db->query($query, Array("objectId" => $this->id));

        while ($row = $sth->fetchObject("image")) {
            $this->images[] = $row;
        }
    }

    function delete()
    {
        $query = "DELETE FROM `" . get_class($this) . "` WHERE `id` = :id";
        $this->getImages();

        $class = get_class($this);
        $classVars = get_class_vars($class);

        foreach ($classVars as $key => $value) {
            $path = ABS_PATH . "/uploads/". $class ."/" . $this->$key;
            if (is_file($path)) {
                unlink($path);
            }
        }

        if (count($this->images)) {
            foreach ($this->images as $key => $img) {
                $img->delete();
            }
        }

        App::get()->db->query($query, Array("id" => $this->id));
    }
}
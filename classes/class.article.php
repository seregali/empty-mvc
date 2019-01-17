<?
defined('_VALID') or die();

class article extends entity
{
    var $alias = "";
    var $title = "";
    var $intro = "";
    var $text = "";
    var $parentId = "";
    var $datetime = "";
    var $ordering = 0;

    function __construct($alias = null, $id = null)
    {
        if ($alias) {
            if (!$this->getByAlias($alias))
                throw new Exception();

        } elseif ($id) {
            if (!$this->get($id))
                throw new Exception();
        }
    }

    function getArticles($page = null)
    {
        $query = "SELECT * FROM `article` WHERE `parentId` = :parentId";

        if ($page) {
            $query .= " LIMIT " . ($page - 1) * ARTICLES_PER_PAGE . ", " . ARTICLES_PER_PAGE;
        }

        $sth = App::get()->db->query($query, Array("parentId" => $this->id));

        while ($row = $sth->fetchObject("article")) {
            $this->articles[] = $row;
        }
    }

    function getByAlias($alias)
    {
        $query = "SELECT * FROM `article` WHERE `alias` = :alias";

        $sth = App::get()->db->query($query, Array("alias" => $alias), $this);

        if ($row = $sth->fetch()) {
            return true;
        }

        return false;
    }

    static function getStructure()
    {
        $rowArray = Array();

        $query = "SELECT * FROM `article` ORDER BY `parentId`, `ordering`";
        $sth = App::get()->db->query($query);

        while ($row = $sth->fetchObject("article")) {
            $rowArray[$row->id] = $row;
        }

        foreach ($rowArray as $article) {
            $found = false;
            foreach ($rowArray as $subitem) {
                if ($article->id == $subitem->parentId) {
                    $found = true;
                    $article->articles[] = $subitem;
                    unset($rowArray[$subitem->id]);
                } else {
                    if ($found)
                        break;
                }
            }
        }

        return $rowArray;
    }

    function show()
    {
        if (count($this->articles)) {
            ?>
            <ul>
                <?
                foreach ($this->articles as $article) {
                    ?>
                    <li data-id="<?= $article->id ?>">
                        <a href="article/edit/<?= $article->id ?>" class="<?= count($article->articles) ? 'strong' : '' ?>">
                            <?= strlen($article->title) ? $article->title : "Пустой заголовок" ?>
                        </a>
                        <?
                        $article->show();
                        ?>
                    </li>
                <?
                }
                ?>
            </ul>
        <?
        }
    }
}
<?
defined('_VALID') or die();

class articleController extends controller
{
    var $templates = Array(
        'show' => Array('inner', 0),
        'all' => Array('inner', 0),
        'viewlist' => Array('admin', 2),
        'edit' => Array('admin', 2, Array(
            "static/libs/summernote/summernote.css",
            "static/libs/summernote/summernote-bs4.css",
            "static/libs/summernote/summernote.min.js",
            "static/libs/summernote/summernote-bs4.min.js",
            "static/libs/jquery-fileupload/jquery.fileupload.js",
            "static/libs/jquery-fileupload/jquery.fileupload.css",
            "static/libs/jquery-fileupload/jquery.fileupload-ui.css"
        )),
        'delete' => Array('admin', 2),
        'sort' => Array('empty', 2)
    );

    function all()
    {
        parent::view();
    }

    function show()
    {
        parent::view();
    }

    function viewlist()
    {
        parent::view();
    }

    function edit()
    {
        if (isset($_POST["id"])) {
            $id = getParam($_REQUEST, "id");
            $image = getParam($_REQUEST, "image");
            $new = getParam($_REQUEST, "new");

            $article = new article(null, $id);

            $deleteImage = getParam($_REQUEST, "deleteImage");

            getRequestObject($article);
            getRequestFiles($article, $deleteImage);

            $article->intro = getParam($_REQUEST, "intro", null, ALLOW_RAW);
            $article->text = getParam($_REQUEST, "text", null, ALLOW_RAW);

            if ($article->alias == "")
                $article->alias = getAlias($article->title);

            if (!$article->datetime) {
                $article->datetime = time();
            }

            $article->save();

            if (count($image)) {
                foreach ($image as $key => $i) {
                    $img = new image($i);
                    if ($img->objectId == "") {
                        $img->objectId = $article->id;
                        $img->save();
                    }
                }
            }
            if ($new) {
                redirect("article/addto/".$article->parentId);
            } else {
                redirect("article/viewlist");
            }
            
        } else {
            parent::view();
        }
    }

    function delete()
    {
        $id = getParam($_REQUEST, "id");

        try {
            $a = new article(null, $id);
            $a->getArticles();
            if (count($a->articles)) {
                foreach ($a->articles as $key => $article) {
                    $article->delete();
                }
            }
            $a->delete();
            redirect("article/viewlist");
        } catch (Exception $e) {

        }
    }

    function sort()
    {
        $data = getParam($_REQUEST, "data");

        if (!is_array($data))
            return;

        foreach ($data as $index => $item) {
            $article = new article(null, $item);
            $article->ordering = $index;
            $article->save();
        }
    }
}
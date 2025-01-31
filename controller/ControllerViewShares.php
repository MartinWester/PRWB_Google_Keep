<?php
require_once "framework/Controller.php";
require_once "model/Note.php";
require_once "model/User.php";
require_once "model/TextNote.php";
require_once "model/ChecklistNote.php";
require_once "model/Shares.php";

class ControllerViewShares extends Controller {
    //accueil du controlleur.
    public function index() : void {
        $noteId = $_GET["param1"];
        $user = $this->get_user_or_redirect();
        if (isset($noteId) && is_numeric($noteId)) {
            if ($user->isOwner($noteId)) {
                $users = User::getAllUsersExeptOne($user->id);
                $shares = Shares::getAllSharesByNoteId($noteId);
                $sharesUsers = [];
                foreach ($shares as $usershare) {
                    array_push($sharesUsers, User::getById($usershare->getUser())->full_name);
                }
                $isSharesByUsers = [];
                foreach($users as $u) {
                    array_push($isSharesByUsers, !Shares::isSharedBy($noteId, $u->getId()));
                }

            (new View("viewshares"))->show(["user"=>$user, "users"=>$users, "shares"=>$shares, "sharesUsers"=>$sharesUsers, "isSharesByUsers"=>$isSharesByUsers, "noteId"=>$noteId]);
            } else {
                (new View("error"))->show(["error"=>$error = "You may not acces to this note."]);
            }
            
        } else {
            (new View("error"))->show(["error"=>$error = "This note doesn't exist."]);
        }
       
    }

    public function swapRole () : void {
        $share = Shares::getSharesByNoteIdAndUser($_GET["param1"], $_POST["iduser"]);
        if ($share->isEditor()) {
            $share->setEditor(0);
        } else {
            $share->setEditor(1);
        }
        $share->persist();
        $this->redirect("viewshares", "index", $_GET["param1"]);
    }

    public function delete () : void {
        $share = Shares::getSharesByNoteIdAndUser($_GET["param1"], $_POST["iduser"]);
        Shares::delete($share->getNote(), $share->getUser());
        $share->persist();
        $this->redirect("viewshares", "index", $_GET["param1"]);
    }

    public function add () : void {
        if ($_POST["user"] != "-User-" && $_POST["permission"] != "-Permission-") {
            $share = new Shares($_GET["param1"], $_POST["user"], $_POST["permission"]);
            Shares::add($share);
            $share->persist();
            $this->redirect("viewshares", "index", $_GET["param1"]);
        }
        $this->redirect("viewshares", "index", $_GET["param1"]);
    }
}
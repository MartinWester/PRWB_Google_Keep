<?php
require_once "framework/Controller.php";

class ControllerViewNotes extends Controller {
    //accueil du controlleur.
    public function index() : void {
        (new View("viewnotes"))->show();
    }

    //Button de création d'une nouvelle note
    public function  add_text_note() : void {
        (new View("addtextnote"))->show();
    }

     //Button de création d'une nouvelle note
     public function  add_checklist_note() : void {
        (new View("addchecklistnote"))->show();
    }

     //Button de création d'une nouvelle note
     public function  tempviewshares() : void {
        (new View("viewshares"))->show();
    }
}
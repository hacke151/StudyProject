<?php

namespace Views;

class MainView {

    public function get() {
        include("Templates/MainPage.html");
    }
    
    public function error404() {
        echo '404';
    }
}

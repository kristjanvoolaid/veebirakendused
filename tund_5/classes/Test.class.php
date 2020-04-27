<?php
    class Test {
        // Properties ehk muutujad
        private $secretNum = 33;
        public $number = 7;

        function __construct() {
            echo "Laeti klass";
            echo "Salajane number on: " . $this->secretNum;
            echo "Avalik number on: " . $this->number; 
        }

        function __destruct() {
            echo "Klass lõpetab";
        }

        public function reveal() {
            echo "Salajane number on: " . $this->secretNum;
        }

    }

